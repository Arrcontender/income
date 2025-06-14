<?php

namespace App\Http\Controllers;

use App\Services\Bybit\AccountTypeEnum;
use App\Services\TInvest\TInvestService;

class TInvestController
{
    protected TInvestService $service;
    public function __construct(TInvestService $service)
    {
        $this->service = $service;
    }

    public function getBalance()
    {
        return response()->json(['t_invest_total' => $this->service->getTotalBalance()]);
    }
}
