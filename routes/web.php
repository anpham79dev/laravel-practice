<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => [
    'message' => 'pong',
    'time' => now()->toIso8601String(),
]);

Route::prefix('api')->group(function () {
    Route::resource('/shop', ShopController::class)->only(['index']);
});
