<?php

namespace App\Services\Bybit;

use App\Services\SourceService;

class BybitService implements SourceService
{
    protected BybitApiInterface $api;

    public function __construct(BybitApiInterface $bybitApi)
    {
        $this->api = $bybitApi;
    }

    public function getTotalBalance(): float
    {
        $results = [];
        $results[] =  $this->api->getWalletBalance([
            'accountType' => AccountTypeEnum::UNIFIED->name
        ]);

        // TODO не работает "accountType only support UNIFIED."
//        $fund = $this->api->getWalletBalance([
//            'accountType' => AccountTypeEnum::FUND->name
//        ]);

        return array_reduce($results, function ($carry, $account) {
            foreach ($account['list'] as $item) {
                $carry += $item['totalEquity'];
            }
            return $carry;
        }, 0);
    }
}
