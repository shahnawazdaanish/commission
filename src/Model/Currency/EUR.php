<?php

namespace Dove\Commission\Model\Currency;

use Dove\Commission\Model\CurrencyInterface;

class EUR implements CurrencyInterface
{
    public function getCurrencyCode(): string
    {
        return "EUR";
    }
}