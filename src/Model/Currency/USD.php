<?php

namespace Dove\Commission\Model\Currency;

use Dove\Commission\Model\CurrencyInterface;

class USD implements CurrencyInterface
{
    public function getCurrencyCode(): string
    {
        return "USD";
    }
}