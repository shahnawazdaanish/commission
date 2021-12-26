<?php

namespace Dove\Commission\Model\ActionType;

use Dove\Commission\Config\Rate;
use Dove\Commission\Model\ActionTypeInterface;
use Dove\Commission\Model\ClientInterface;

class Deposit implements ActionTypeInterface
{
    private $clientType;

    public function getChargeRate(): float
    {
        return Rate::get("deposit_rate") / 100; // from percent to actual value
    }

    public function setClientType(ClientInterface $clientType)
    {
        // TODO: Implement setClientType() method.
    }
}