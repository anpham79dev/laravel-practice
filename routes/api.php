<?php

use App\Http\Controllers\RentalItemController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::apiResource('shops', ShopController::class);

Route::get('rental-items', [RentalItemController::class, 'index']);
