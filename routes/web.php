<?php

use App\Actions\Authentication\LogoutUser;
use App\Livewire\Authentication\Login;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get("/", HomePage::class)->name('home');
Route::get("/login", Login::class)->name('login');
Route::get("/logout", LogoutUser::class)->name('logout');
