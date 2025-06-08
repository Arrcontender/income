<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BybitController;
use App\Http\Controllers\TInvestController;
use App\Http\Controllers\AggregationController;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    Route::get('bybit/get_balance', [BybitController::class, 'getBalance']);
    Route::get('t_invest/get_balance', [TInvestController::class, 'getBalance']);
    Route::get('aggregation/get_total_balance', [AggregationController::class, 'getTotalBalance']);
});

