<?php

namespace App\Services\Bybit;

use GuzzleHttp\Exception\GuzzleException;

class BybitService
{
    protected BybitApi $api;

    public function __construct(BybitApi $bybitApi)
    {
        $this->api = $bybitApi;
    }

    /**
     * @throws \HttpException
     * @throws GuzzleException
     */
    public function getWalletBalance(AccountTypeEnum $accountType, ?CoinEnum $coin = null): array
    {
        $params = [
            'accountType' => $accountType->name
        ];
        if ($coin) {
            $params['coin'] = $coin->name;
        }

        return $this->api->getWalletBalance($params);
    }
}
