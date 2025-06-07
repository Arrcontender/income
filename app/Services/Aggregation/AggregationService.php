<?php

namespace App\Services\Aggregation;

use App\Services\Bybit\BybitService;
use App\Services\SourceService;
use App\Services\TInvest\TInvestService;

class AggregationService
{
    /**
     * @return array<SourceService>
     */
    protected function registerServices(): array
    {
        return [
            BybitService::class,
            TInvestService::class
        ];
    }

    public function getTotalBalance(): int
    {
        return array_reduce($this->registerServices(), function ($carry, string $service) {
            $carry += app($service)->getTotalBalance();
            return $carry;
        }, 0);

    }
}
