<?php

namespace Core;

class Migrations
{
    public static function up($name)
    {
        $db = App::container()->resolve(Database::class);

        $connection = $db::$connection;

        $connection->exec(file_get_contents(BASE_PATH . '/database/migrations/' . $name . '.up.sql'));

        return true;
    }

    public static function down($name)
    {
        $db = App::container()->resolve(Database::class);

        $connection = $db::$connection;

        $connection->exec(file_get_contents(BASE_PATH . '/database/migrations/' . $name . '.down.sql'));

        return true;
    }
}
