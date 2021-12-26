<?php

namespace Dove\Commission\Model\Currency;

use Dove\Commission\Model\CurrencyInterface;

class JPY implements CurrencyInterface
{
    public function getCurrencyCode(): string
    {
        return "JPY";
    }
}