<?php

namespace Dove\Commission\Utility;

trait Helpers
{
    public static function config($key)
    {
        try {
            $keys = explode('.', $key);
            if (count($keys) > 1) {
                $array = include __DIR__ . "/../Config/" . $keys[0] . '.php';
                return $array[$keys[1]];
            }
        } catch (\Exception $exception) {
        }
        return null;
    }
}
