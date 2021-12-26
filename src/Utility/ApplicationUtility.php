<?php

namespace Dove\Commission\Utility;

use DateTime;
use Dove\Commission\Model\Operation;

class ApplicationUtility
{

    /**
     * Converts CSV file contents to PHP set of arrays
     *
     * a static function
     * @param $file_name string it takes filename to search in root path
     * @return array
     */
    public static function readCsvToArrayOfOperations(string $file_name): array
    {
        $csv_values = file_get_contents($file_name);
        $lines = explode(PHP_EOL, $csv_values);

        $array = array();
        foreach ($lines as $line) {
            $rawOperation = str_getcsv($line);
            $operation = self::buildOperationFromArray($rawOperation);

            $array[] = $operation;
        }

        return $array;
    }

    public static function buildOperationFromArray(array $rawOperation): Operation
    {
        $operation = new Operation();
        $operation->setOperationAt($rawOperation[0]);
        $operation->setUserID($rawOperation[1]);
        $operation->setUserType($rawOperation[2]);
        $operation->setType($rawOperation[3]);
        $operation->setAmount($rawOperation[4]);
        $operation->setCurrency($rawOperation[5]);
        $operation->chainLinkage();
        return $operation;
    }

    public static function readSingleLineToOperation(string $line): Operation
    {
        $rowOperation = str_getcsv($line);
        return self::buildOperationFromArray($rowOperation);
    }

    public static function convertUpToTwoDecimalsWithCeiling(float $number): string
    {
        $number = self::ceilUpToTwoDecimals($number);
        return number_format($number, 2, '.', '');
    }

    public static function ceilUpToTwoDecimals($number): float
    {
        return ceil($number * 100) / 100;
    }

    public static function nextFirstDayOfWeek(DateTime $transaction_date): DateTime
    {
        $date = clone($transaction_date);
        return $date->setISODate($date->format('o'), $date->format('W') + 1);
    }
}