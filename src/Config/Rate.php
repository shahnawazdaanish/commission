<?php

namespace Dove\Commission\Config;

class Rate extends Config
{

    public static function get($key): string
    {
        $configs = self::configs();

        if (array_key_exists($key, $configs)) {
            return $configs[$key];
        }
        return "";
    }

    protected static function configs(): array
    {
        return [
            'deposit_rate' => 0.03,
            'withdraw_private_rate' => 0.3,
            'withdraw_business_rate' => 0.5
        ];
    }
}