<?php

namespace Dove\Commission\Tests\Service;

use Dove\Commission\Model\Operation;
use Dove\Commission\Service\CommissionService;
use PHPUnit\Framework\TestCase;

class CommissionServiceTest extends TestCase
{

    public function testCommissionOne()
    {
        $operations = [
            new Operation(['2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR']),
            new Operation(['2015-01-01', 4, 'private', 'withdraw', 1000.00, 'EUR'])
        ];
        $expectedOutput = [
            '0.60',
            '3.00'
        ];

        $commissionService = new CommissionService($operations);
        $updatedOperation = $commissionService->processOperations();

        foreach ($updatedOperation as $index => $operation) {
            self::assertSame($expectedOutput[$index], $operation->getFee());
        }
    }
}