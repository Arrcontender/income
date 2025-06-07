<?php

use Illuminate\Support\Facades\Route;

Route::get('bybit/get_balance', [\App\Http\Controllers\BybitController::class, 'getBalance']);
Route::get('t_invest/get_balance', [\App\Http\Controllers\TInvestController::class, 'getBalance']);
Route::get('aggregation/get_total_balance', [\App\Http\Controllers\AggregationController::class, 'getTotalBalance']);

