<?php

namespace App\Services\Bybit;


enum AccountTypeEnum: string
{
    case UNIFIED = 'Unified Trading Account';
    case FUND = 'Funding Account';
}
