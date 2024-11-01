<?php

use App\Actions\Authentication\LogoutUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

it('logs out the user', function () {
    expect(Auth::check())->toBeTrue();
    LogoutUser::run();
    expect(Auth::check())->toBeFalse();
});
