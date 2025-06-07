<?php

namespace App\Http\Controllers;

use App\Services\Bybit\AccountTypeEnum;
use App\Services\Bybit\BybitService;

class BybitController extends Controller
{
    protected BybitService $service;
    public function __construct(BybitService $service)
    {
        $this->service = $service;
    }

    public function getWalletBalance()
    {
        dd($this->service->getWalletBalance(AccountTypeEnum::UNIFIED));
    }
}
