<?php

namespace Dove\Commission\Parser;

abstract class Parser
{
    abstract public function getOperations($fileName);

    protected function isValidFileExtension($fileName, $type): bool
    {
        return strpos($fileName, '.' . $type) !== false;
    }
}
