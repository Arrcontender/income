<?php

namespace App\Http\Controllers;

use App\Services\Bybit\AccountTypeEnum;
use App\Services\Bybit\BybitService;
use GuzzleHttp\Exception\GuzzleException;

class BybitController extends Controller
{
    protected BybitService $service;
    public function __construct(BybitService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws \HttpException
     * @throws GuzzleException
     */
    public function getBalance()
    {
        echo json_encode(['bybit_total' => $this->service->getTotalBalance()]);
    }
}
