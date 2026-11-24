<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => [
    'message' => 'pong',
    'time' => now()->toIso8601String(),
]);
