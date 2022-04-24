<?php

require_once "./vendor/autoload.php";

use Dove\Commission\Parser\CsvParser;
use Dove\Commission\Service\CommissionService;


/**
 * Get File Name
 * */
$fileName = isset($argv) && is_array($argv) && !empty($argv[1]) ? $argv[1] : null;
if (!$fileName) {
    throw new \RuntimeException("File name is not present or invalid");
}

/**
 * Extract Operations
 */
$inputOperations = [];
if (strpos($fileName, ".csv") !== false) {
    $csvParser = new CsvParser();
    $inputOperations = $csvParser->getOperations($fileName);
} else {
    throw new \RuntimeException("File format is not supported");
}
/**
 * Calculate Commission
 */
$commissionService = new CommissionService($inputOperations);
$updatedOperations = $commissionService->processOperations();

foreach ($updatedOperations as $operation) {
    echo $operation->getFee() . "\n";
}