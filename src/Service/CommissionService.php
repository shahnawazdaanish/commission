<?php

namespace Dove\Commission\Service;

use Dove\Commission\Model\ActionType\Withdraw;
use Dove\Commission\Model\ChargeRule\PrivateWithdrawRule;
use Dove\Commission\Model\Client\PrivateClient;
use Dove\Commission\Model\Operation;
use Dove\Commission\Utility\ApplicationUtility;

class CommissionService
{
    /**
     * Commission Service
     * @param array $memory
     * @param Operation ...$operations
     * @return array
     */
    public function calculateOperations(array &$memory, Operation ...$operations): array
    {
        $results = [];
        foreach ($operations as $operation) {
            $chargingAmount = $operation->getAmount();
            $charge = $operation->getType()->getChargeRate();


            if ($operation->getType() instanceof Withdraw && $operation->getUserType() instanceof PrivateClient) {
                $rule = new PrivateWithdrawRule();
                $chargingAmount = $rule->calculateChargingAmount($operation, $memory);
            }

            $commissionFee = $chargingAmount * $charge;

            $results[] = ApplicationUtility::convertUpToTwoDecimalsWithCeiling($commissionFee);
        }
        return $results;
    }

}