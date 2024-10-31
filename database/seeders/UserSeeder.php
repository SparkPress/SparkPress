<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = new User();
        $user->name = 'Administrator';
        $user->email = 'test@test.com';
        $user->password = Hash::make('password');
        $user->save();
    }
}
