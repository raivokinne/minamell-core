<?php

namespace Minamell\Minamell;

class Migrations
{
    /**
     * @return bool
     * @param mixed $name
     */
    public static function up($name): bool
    {
        $db = App::container()->resolve(Database::class);

        $connection = $db::$connection;

        $connection->exec(file_get_contents(BASE_PATH . '/database/migrations/' . $name . '.up.sql'));

        return true;
    }
    /**
     * @return bool
     * @param mixed $name
     */
    public static function down($name): bool
    {
        $db = App::container()->resolve(Database::class);

        $connection = $db::$connection;

        $connection->exec(file_get_contents(BASE_PATH . '/database/migrations/' . $name . '.down.sql'));

        return true;
    }
}
