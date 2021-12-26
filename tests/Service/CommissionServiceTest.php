<?php

namespace Dove\Commission\Tests\Service;

use Dove\Commission\Service\CommissionService;
use Dove\Commission\Utility\ApplicationUtility;
use PHPUnit\Framework\TestCase;

class CommissionServiceTest extends TestCase
{
    private $memory = [];

    /**
     * @return array
     */
    public function testCommissionOne(): array
    {
        $input = '2014-12-31,4,private,withdraw,1200.00,EUR';
        $expectedOutput = '0.60';

        $stringToOperation = ApplicationUtility::readSingleLineToOperation($input);
        $commissionService = new CommissionService();
        $output = $commissionService->calculateOperations($this->memory, $stringToOperation);
        if (isset($output[0])) {
            self::assertSame($expectedOutput, $output[0]);
        }
        return $this->memory;
    }
}