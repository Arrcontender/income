<?php

namespace App\Services\TInvest;

class TInvestApiMock implements TInvestApiInterface
{

    public function getAccounts(): array
    {
        return [
            '1',
            '2',
            '3'
        ];
    }

    public function getPortfolio(array $accountsIds): array
    {
        return [
            [
                'totalAmountShares' => [
                    'units' => 10
                ],
                'totalAmountBonds' => [
                    'units' => 10
                ],
                'totalAmountEtf' => [
                    'units' => 10
                ],
                'totalAmountCurrencies' => [
                    'units' => 0
                ],
                'totalAmountFutures' => [
                    'units' => 10
                ]
            ]
        ];
    }
}
