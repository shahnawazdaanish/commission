<?php

namespace Dove\Commission\Model;

interface CurrencyInterface
{
    public function getCurrencyCode(): string;
}