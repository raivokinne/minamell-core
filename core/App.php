<?php

namespace Minamell\Minamell;

class App
{
    protected static $container;
    /**
     * @return void
     * @param mixed $container
     */
    public static function setContainer($container): void
    {
        self::$container = $container;
    }

    public static function container()
    {
        return self::$container;
    }
}

