<?php

namespace Dove\Commission\Model;

interface ActionTypeInterface
{
    public function getChargeRate(): float;

    public function setClientType(ClientInterface $clientType);
}