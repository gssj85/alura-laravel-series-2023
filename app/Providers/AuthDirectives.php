<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthDirectives extends ServiceProvider
{
    public function register()
    {
        Blade::if('guest', function () {
            return !Route::is('login') && Auth::guest();
        });
    }
}
