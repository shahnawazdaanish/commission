<?php

namespace Dove\Commission\Service;

use Dove\Commission\Utility\Helpers;
use Dove\Commission\Utility\Utils;

class CommissionService
{
    use Helpers;

    private $operations;
    private $sortedOperations;

    public function __construct($operations)
    {
        $this->operations = $operations;
    }

    public function processOperations(): array
    {
        $updatedOperation = [];
        if (is_array($this->operations) && count($this->operations) > 0) {
            $commission = self::config("app.commissions");

            $userHistory = [];
            foreach ($this->operations as $operation) {
                if (isset($commission[$operation->getClientType()][$operation->getOperationType()])) {
                    $rate = $commission[$operation->getClientType()][$operation->getOperationType()];
                    if (is_numeric($rate)) {
                        $operation->setFee($this->calcFee($operation->getAmount(), $rate));
                    } elseif (isset($rate['history']) && $rate['history']) {
                        $amount = $this->calculateWaiverFee($operation, $rate, $userHistory);
                        $operation->setFee($this->calcFee($amount, $rate['charge']));
                    }
                }
                $updatedOperation[] = $operation;
            }
        }
        return $updatedOperation;
    }

    private function calcFee($amount, $rate): string
    {
        return Utils::roundDecimal($amount * ($rate / 100));
    }

    private function calculateWaiverFee($operation, $rate, &$history): float
    {
        $client = $operation->getClientId();
        $spanConfig = $rate['history'];
        $operationType = $operation->getOperationType();

        if (
            isset($history[$client])
            && $history[$client]['operationType'] === $operationType
            && $this->isWithinSpanTime($history[$client], $operation)
        ) {
            $history[$client]['next'] = Utils::nextDateOccurrence($operation->getOperationAt(), $spanConfig);
            $history[$client]['operationType'] = $operationType;
            $history[$client]['sum'] += Utils::toBaseCurrency($operation->getAmount(), $operation->getCurrency());
            $history[$client]['count'] += 1;
        } else {
            $history[$client]['next'] = Utils::nextDateOccurrence($operation->getOperationAt(), $spanConfig);
            $history[$client]['operationType'] = $operationType;
            $history[$client]['sum'] = Utils::toBaseCurrency($operation->getAmount(), $operation->getCurrency());
            $history[$client]['count'] = 1;
        }

        if ($this->checkIfCountExceeded($history[$client]['count'], $rate)) {
            return $operation->getAmount();
        }

        return $this->getAmountToChargeAfterWaiver($history[$client]['sum'], $operation, $rate);
    }

    private function isWithinSpanTime($history, $operation): bool
    {
        return isset($history['next']) && $history['next'] > $operation->getOperationAt();
    }

    private function checkIfCountExceeded($count, $config): bool
    {
        return $count > ($config['waiver_count'] ?? 0);
    }

    private function getAmountToChargeAfterWaiver($sum, $operation, $config): float
    {
        $baseAmount = Utils::toBaseCurrency($operation->getAmount(), $operation->getCurrency());
        $remainder = $sum - Utils::toBaseCurrency(
            ($config['max_waiver_amount'] ?? 0),
            ($config['waiver_currency'] ?? 'EUR')
        );

        if ($remainder < 0) {
            return 0;
        }

        if ($remainder > $baseAmount) {
            return $operation->getAmount();
        }
        return Utils::toOperationCurrency($remainder, $operation->getCurrency());
    }
}
