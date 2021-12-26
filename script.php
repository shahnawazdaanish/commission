<?php

require_once "./vendor/autoload.php";

use Dove\Commission\Service\CommissionService;
use Dove\Commission\Utility\ApplicationUtility;


/*
 * Get file name argument from CLI
 */
$file_name = isset($argv) && is_array($argv) && !empty($argv[1]) ? $argv[1] : null;

if (!$file_name) {
    throw new \RuntimeException("File name is not present or invalid");
}

try {
    $memory = [];
    $operations = ApplicationUtility::readCsvToArrayOfOperations($file_name);

    $commissionService = new CommissionService();
    $results = $commissionService->calculateOperations($memory, ...$operations);

    foreach ($results as $result) {
        echo $result . "\n";
    }
} catch (Exception $exception) {
    echo $exception->getMessage();
    die();
}