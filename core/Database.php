<?php

namespace Core;

use PDO;

class Database
{
    public static PDO $connection;
    /**
     * @param mixed $config
     */
    public static function connect($config): PDO
    {
        if (!$config) {
            die('config not found');
        }

        $dsn = $config['driver'] . ":host=" . $config['host'] . ";dbname=" . $config['database'] . ";charset=" . $config['charset'];

        try {
            self::$connection = new PDO($dsn, $config['username'], $config['password']);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return self::$connection;
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
}
