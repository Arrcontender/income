<?php

namespace App\Services\TInvest;

interface TInvestApiInterface
{
    public function getAccounts(): array;

    public function getPortfolio(array $accountsIds): array;
}
