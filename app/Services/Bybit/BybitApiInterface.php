<?php

namespace App\Services\Bybit;

interface BybitApiInterface
{
    public function getWalletBalance(?array $params = []): array;
}
