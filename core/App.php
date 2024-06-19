<?php

namespace Minamell\Minamell;

class App
{
    protected static $container;

    public static function setContainer($container)
    {
        self::$container = $container;
    }

    public static function container()
    {
        return self::$container;
    }
}

