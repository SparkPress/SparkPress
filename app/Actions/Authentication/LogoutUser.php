<?php

namespace App\Actions\Authentication;

use Auth;
use Lorisleiva\Actions\Concerns\AsAction;
use Session;

class LogoutUser
{
    use AsAction;

    public function handle(): void
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();
    }

    public function asController()
    {
        $this->handle();
        return redirect()->route('home');
    }
}
