<?php

namespace App\Http\Controllers;

use App\Services\Aggregation\AggregationService;
use GuzzleHttp\Exception\GuzzleException;

class AggregationController extends Controller
{
    protected AggregationService $service;
    public function __construct(AggregationService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws \HttpException
     * @throws GuzzleException
     */
    public function getTotalBalance()
    {
        return response()->json(['total_balance' => $this->service->getTotalBalance()]);
    }
}
