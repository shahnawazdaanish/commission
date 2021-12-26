<?php

namespace Dove\Commission\Config;

abstract class Config
{
    abstract public static function get($key);

    abstract protected static function configs();
}