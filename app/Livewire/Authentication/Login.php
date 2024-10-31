<?php

namespace App\Livewire\Authentication;

use App\Actions\Authentication\LoginUser;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    public function mount() {
        if (auth()->check()) {
            $this->redirect(route("home"));
        }
    }

    public function authenticate()
    {
        $this->validate();
        LoginUser::run($this->email, $this->password, $this->remember);
        $this->redirectIntended(route("home"));
    }
}
