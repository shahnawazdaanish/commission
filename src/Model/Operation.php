<?php

namespace Dove\Commission\Model;

use DateTime;

/**
 * Class Operation
 * @package Dove\Commission\Model
 */
class Operation
{
    private $operationAt;
    private $clientID;
    private $clientType;
    private $operationType;
    private $amount;
    private $currency;

    private $fee;

    public function __construct(array $rawOperation = array())
    {
        $this->setOperationAt($rawOperation[0] ?? '');
        $this->setClientID($rawOperation[1] ?? '');
        $this->setClientType($rawOperation[2] ?? '');
        $this->setOperationType($rawOperation[3] ?? '');
        $this->setAmount($rawOperation[4] ?? '');
        $this->setCurrency($rawOperation[5] ?? '');
    }


    /**
     * @return DateTime
     */
    public function getOperationAt(): DateTime
    {
        return $this->operationAt;
    }

    /**
     * @param string $operationAt
     */
    public function setOperationAt(string $operationAt)
    {
        $this->operationAt = DateTime::createFromFormat('Y-m-d', $operationAt);
    }

    /**
     * @return int
     */
    public function getClientID(): int
    {
        return $this->clientID;
    }

    /**
     * @param int $clientID
     */
    public function setClientID(int $clientID)
    {
        $this->clientID = $clientID;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return double
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount(string $amount)
    {
        $this->amount = (double)$amount;
    }

    /**
     * @return string
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * @param string $operationType
     */
    public function setOperationType(string $operationType = null)
    {
        $this->operationType = $operationType;
    }

    /**
     * @return string
     */
    public function getClientType(): string
    {
        return $this->clientType;
    }

    /**
     * @param string $clientType
     */
    public function setClientType(string $clientType)
    {
        $this->clientType = $clientType;
    }

    public function getFee()
    {
        return $this->fee;
    }

    public function setFee($fee)
    {
        $this->fee = $fee;
    }
}
