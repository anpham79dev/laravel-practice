<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::apiResource('shops', ShopController::class);
