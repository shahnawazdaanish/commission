<?php

namespace Dove\Commission\Parser;

use Dove\Commission\Model\Operation;

class CsvParser extends Parser
{
    private $fileExtension = "csv";

    /**
     * @param $fileName
     * @return mixed
     */
    public function getOperations($fileName): array
    {
        $isValidFileType = $this->isValidFileExtension($fileName, $this->fileExtension);
        if ($isValidFileType) {
            $lines = $this->readCsv($fileName);

            $operations = [];
            foreach ($lines as $line) {
                $rawOperation = str_getcsv($line);
                $operations[] = new Operation($rawOperation);
            }
            return $operations;
        }
        throw new \RuntimeException("File extension mismatching, expect {$this->fileExtension}");
    }

    public function readCsv($fileName)
    {
        try {
            $csvRows = file_get_contents($fileName);
            if (is_string($csvRows) && !empty($csvRows)) {
                return explode(PHP_EOL, $csvRows);
            }
            throw new \RuntimeException("No data in the csv file");
        } catch (\Exception $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
    }
}
