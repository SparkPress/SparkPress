<?php

namespace App\Actions\Authentication;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;
use RateLimiter;
use Str;
use Validator;

class LoginUser
{
    use AsAction;

    public function handle(string $email, string $password, bool $remember = false): void
    {
        $key = Str::transliterate(Str::lower($email).'|'.request()->ip());

        // Handle rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            event(new Lockout(request()));
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Attempt login
        if (! auth()->attempt(['email' => $email, 'password' => $password], $remember)) {
            RateLimiter::hit($key);
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // We've logged in, so we can clear the rate limiter
        RateLimiter::clear($key);
    }

    public function asController()
    {
        $data = Validator::validate(request()->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $this->handle($data['email'], $data['password'], $data['remember'] ?? false);
        return redirect()->intended('/dashboard');
    }
}
