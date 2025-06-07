<?php

use Illuminate\Support\Facades\Route;

Route::get('bybit/get_balance', [\App\Http\Controllers\BybitController::class, 'getWalletBalance']);
