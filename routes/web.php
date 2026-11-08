<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::prefix('api')->group(function () {
    Route::get('/ping', fn () => [
        'message' => 'pong',
        'time' => now()->toIso8601String(),
    ]);
});
