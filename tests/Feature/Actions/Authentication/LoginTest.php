<?php

use App\Actions\Authentication\LoginUser;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
});

it('can login a user with valid credentials', function () {
    $action = new LoginUser;

    $action->handle('test@example.com', 'password');

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user()->id)->toBe($this->user->id);
});

it('can login a user with remember me enabled', function () {
    $action = new LoginUser;

    $action->handle('test@example.com', 'password', true);

    expect(auth()->check())->toBeTrue()
        ->and(auth()->viaRemember())->toBeFalse() // Initially false as cookie not set in testing
        ->and(auth()->user()->id)->toBe($this->user->id);
});

it('throws validation exception for invalid credentials', function () {
    $action = new LoginUser;

    expect(fn () => $action->handle('test@example.com', 'wrong-password'))
        ->toThrow(ValidationException::class);
});

it('throws validation exception for non-existent user', function () {
    $action = new LoginUser;

    expect(fn () => $action->handle('nonexistent@example.com', 'password'))
        ->toThrow(ValidationException::class);
});

it('implements rate limiting after too many attempts', function () {
    $action = new LoginUser;
    Event::fake();

    // Attempt login 6 times (exceeding the 5 attempt limit)
    for ($i = 0; $i < 6; $i++) {
        try {
            $action->handle('test@example.com', 'wrong-password');
        } catch (ValidationException $e) {
            continue;
        }
    }

    // Verify lockout event was fired
    Event::assertDispatched(Lockout::class);

    // Verify rate limiting is in effect
    expect(RateLimiter::tooManyAttempts(
        'test@example.com|'.request()->ip(),
        5
    ))->toBeTrue();
});

it('clears rate limiting after successful login', function () {
    $action = new LoginUser;

    // First, make some failed attempts
    for ($i = 0; $i < 3; $i++) {
        try {
            $action->handle('test@example.com', 'wrong-password');
        } catch (ValidationException $e) {
            continue;
        }
    }

    // Then login successfully
    $action->handle('test@example.com', 'password');

    // Verify rate limiting was cleared
    expect(RateLimiter::tooManyAttempts(
        'test@example.com|'.request()->ip(),
        5
    ))->toBeFalse();
});
