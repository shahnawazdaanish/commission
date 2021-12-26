<?php

namespace Dove\Commission\Model\ActionType;

use Dove\Commission\Config\Rate;
use Dove\Commission\Model\ActionTypeInterface;
use Dove\Commission\Model\Client\BusinessClient;
use Dove\Commission\Model\ClientInterface;

class Withdraw implements ActionTypeInterface
{
    private $clientType;

    public function getChargeRate(): float
    {
        if ($this->clientType instanceof BusinessClient) {
            return Rate::get("withdraw_business_rate") / 100;
        }

        return Rate::get("withdraw_private_rate") / 100;
    }

    public function setClientType(ClientInterface $clientType)
    {
        $this->clientType = $clientType;
    }
}