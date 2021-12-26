<?php

namespace Dove\Commission\Model;

use DateTime;
use Dove\Commission\Model\Currency\EUR;
use Dove\Commission\Service\RateConverterService;

/**
 * Class Operation
 * @package Dove\Commission\Model
 */
class Operation
{
    const NAME_SPACE_PREFIX = 'Dove\Commission\Model\\';
    const CLIENT_PATH = self::NAME_SPACE_PREFIX . 'Client\\';
    const ACTION_TYPE_PATH = self::NAME_SPACE_PREFIX . 'ActionType\\';
    const CURRENCY_PATH = self::NAME_SPACE_PREFIX . 'Currency\\';

    private $operationAt;
    private $userID;
    private $userType;
    private $type;
    private $amount;
    private $currency;

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
    public function getUserID(): int
    {
        return $this->userID;
    }

    /**
     * @param int $userID
     */
    public function setUserID(int $userID)
    {
        $this->userID = $userID;
    }

    public function getCurrencyAmountInEuro(): float
    {
        $isCurrentCurrencyInEuro = $this->getCurrency() instanceof EUR;

        if ($isCurrentCurrencyInEuro) {
            return $this->getAmount();
        }

        return RateConverterService::convert($this->getCurrency(), new EUR(), $this->getAmount());
    }

    /**
     * @return CurrencyInterface
     */
    public function getCurrency(): CurrencyInterface
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $currencyClass = self::CURRENCY_PATH . ucwords($currency);
        $this->currency = new $currencyClass();
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

    public function chainLinkage()
    {
        $this->getType()->setClientType($this->getUserType());
    }

    /**
     * @return ActionTypeInterface
     */
    public function getType(): ActionTypeInterface
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type = null)
    {
        $typeClass = self::ACTION_TYPE_PATH . ucwords($type);
        $this->type = new $typeClass($this);
    }

    /**
     * @return ClientInterface
     */
    public function getUserType(): ClientInterface
    {
        return $this->userType;
    }

    /**
     * @param string $userType
     */
    public function setUserType(string $userType)
    {
        $clientClass = self::CLIENT_PATH . ucwords($userType) . 'Client';
        $this->userType = new $clientClass();
    }


}