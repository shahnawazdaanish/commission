<?php

namespace Dove\Commission\Service;

interface RateConverterInterface
{
    public function convert($fromCurrency, $toCurrency, $amount);
}