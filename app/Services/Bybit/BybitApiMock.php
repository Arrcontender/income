<?php

namespace App\Services\Bybit;

class BybitApiMock implements BybitApiInterface
{
    public function getWalletBalance(?array $params = []): array
    {
        return [
            'list' => [
                [
                    'totalEquity' => 15
                ]
            ]
        ];
    }
}
