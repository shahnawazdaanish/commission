<?php

namespace Dove\Commission\Model\ChargeRule;

use Dove\Commission\Model\CurrencyInterface;
use Dove\Commission\Model\Operation;
use Dove\Commission\Service\RateConverterService;
use Dove\Commission\Utility\ApplicationUtility;

class PrivateWithdrawRule
{
    const MAX_WAIVER_COUNT = 3;
    const MAX_WAIVER_AMOUNT_IN_EURO = 1000.00;

    public function calculateChargingAmount(Operation $operation, &$memory): float
    {
        $clientID = $operation->getUserID();
        $rawCurrencyAmount = $operation->getAmount();

        $user = $this->getAttributeInfo($clientID, $memory);
        $nextWeekDay = ApplicationUtility::nextFirstDayOfWeek($operation->getOperationAt()); // next first day of week

        if (empty($user) || ($operation->getOperationAt() >= $user['nextWeekDate'])) { // if current date is max, reset
            $user = $this->resetUserAttribute($clientID, $nextWeekDay, $memory);
        }

        $isCountExceeds = $this->checkMaxCountExceeds($user['count'], $operation); // maximum operation count per user
        if ($isCountExceeds) {
            return $rawCurrencyAmount;
        }

        // Initializing required variables for calculation
        $amountToChargeEuro = 0;
        $maxWaivedAmountEuro = self::MAX_WAIVER_AMOUNT_IN_EURO;
        $consumedAmountEuro = $user['consumedWaiverAmountEuro'];
        $amountEuro = $this->convertInEuro($operation->getCurrency(), $rawCurrencyAmount, $memory);

        if (($consumedAmountEuro + $amountEuro) <= $maxWaivedAmountEuro) { // if under waiver limit
            $waiveAmountEuro = $amountEuro;
        } else { // if forecasted amount is surpassing waiver limit
            $waiveAmountEuro = $maxWaivedAmountEuro - $consumedAmountEuro;
            $amountToChargeEuro = $amountEuro - $waiveAmountEuro;
        }

        // Store calculated information for next operation by user id.
        $userAttribute = array(
            'count' => $user['count'] + 1,
            'consumedWaiverAmountEuro' => $consumedAmountEuro + $waiveAmountEuro,
            'nextWeekDate' => $nextWeekDay
        );
        $this->setAttributeInfo($clientID, $userAttribute, $memory);

        // Converting chargeable amount in actual currency
        return $this->convertFromEuro($operation->getCurrency(), $amountToChargeEuro, $memory);
    }

    public function getAttributeInfo($id, &$memory): array
    {
        return $memory["operation"][$id] ?? [];
    }

    public function resetUserAttribute($id, $nextWeekDate = null, &$memory = null): array
    {
        $userAttribute = array(
            'count' => 0,
            'nextWeekDate' => $nextWeekDate ?? null,
            'consumedWaiverAmountEuro' => 0
        );
        $this->setAttributeInfo($id, $userAttribute, $memory);
        return $userAttribute;
    }

    public function setAttributeInfo($id, $data, &$memory)
    {
        $memory["operation"][$id] = $data;
    }

    private function checkMaxCountExceeds(int $count, Operation $operation): bool
    {
        return $count >= self::MAX_WAIVER_COUNT;
    }

    public function convertInEuro(CurrencyInterface $currency, $amount, &$memory): float
    {
        return RateConverterService::convertInEuro($currency, $amount, $memory);

        // @TODO: Comment out to use predefined rate
        /*if ($currency instanceof EUR) {
            return $amount;
        }

        if ($currency instanceof USD) {
            return $amount / 1.1497;
        }

        if ($currency instanceof JPY) {
            return $amount / 129.53;
        }
        return $amount;*/
    }

    public function convertFromEuro(CurrencyInterface $currency, $amount, &$memory): float
    {
        return RateConverterService::convertInCurrencyFromEuro($currency, $amount, $memory);

        // @TODO: comment out if wish to use predefined rate
        /*if ($currency instanceof EUR) {
            return $amount;
        }

        if ($currency instanceof USD) {
            return $amount * 1.1497;
        }

        if ($currency instanceof JPY) {
            return $amount * 129.53;
        }
        */
    }
}
