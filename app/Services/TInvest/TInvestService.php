<?php

namespace App\Services\TInvest;

use App\Services\SourceService;
use GuzzleHttp\Exception\GuzzleException;

class TInvestService implements SourceService
{
    protected TInvestApiInterface $api;

    public function __construct(TInvestApiInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @throws \HttpException
     * @throws GuzzleException
     * @throws \Exception
     */
    public function getTotalBalance(): float
    {
        $ids = $this->api->getAccounts();
        $portfoliosData = $this->api->getPortfolio($ids);

        return array_reduce($portfoliosData, function ($carry, $portfolio) {
            $carry += $portfolio['totalAmountShares']['units'];
            $carry += $portfolio['totalAmountBonds']['units'];
            $carry += $portfolio['totalAmountEtf']['units'];
            $carry += $portfolio['totalAmountCurrencies']['units'];
            $carry += $portfolio['totalAmountFutures']['units'];
            return $carry;
        }, 0);
    }
}
