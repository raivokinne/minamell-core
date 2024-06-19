<?php

namespace Core;

class Crafter
{
    protected $commands = [];

    public function __construct()
    {
        $this->registerCommand('list', [$this, 'listCommands']);
        $this->registerCommand('make:controller', [$this, 'makeController']);
        $this->registerCommand('migrate:up', [$this, 'migrateUp']);
        $this->registerCommand('migrate:down', [$this, 'migrateDown']);
        $this->registerCommand('make:migration', [$this, 'makeMigration']);
        $this->registerCommand('make:model', [$this, 'makeModel']);
        $this->registerCommand('make:view', [$this, 'makeView']);
    }

    public function run($argv)
    {
        $command = $argv[1] ?? 'list';

        if (isset($this->commands[$command])) {
            call_user_func($this->commands[$command], array_slice($argv, 2));
        } else {
            echo "Command '$command' not found.\n";
            $this->listCommands();
        }
    }

    public function registerCommand($name, callable $callback)
    {
        $this->commands[$name] = $callback;
    }

    public function listCommands()
    {
        echo "Available commands:\n";
        foreach (array_keys($this->commands) as $command) {
            echo " - $command\n";
        }
    }

    public function makeController($args)
    {
        $name = $args[0] ?? '';

        if (empty($name)) {
            echo "Please specify a name.\n";
            return;
        }

        if (file_exists("app/controllers/$name.php")) {
            echo "File already exists.\n";
            return;
        }

        $template = <<<EOT
        <?php

        view('$name', [
            'title' => '$name',
        ]);

        EOT;

        file_put_contents("app/controllers/$name.php", $template);

        echo "Controller created successfully.\n";

        $this->listCommands();
    }

    public function migrateUp($args)
    {
        $name = $args[0] ?? '';

        if (empty($name)) {
            echo "Please specify a name.\n";
            return;
        }

        if (!file_exists('database/migrations/$name.up.sql')) {
            echo "Migration file not found.\n";
            return;
        }

        $migrations = new Migrations();
        $migrations->up($name);
    }

    public function migrateDown($args)
    {
        $name = $args[0] ?? '';

        if (empty($name)) {
            echo "Please specify a name.\n";
            return;
        }

        if (!file_exists('database/migrations/$name.down.sql')) {
            echo "Migration file not found.\n";
            return;
        }

        $migrations = new Migrations();
        $migrations->down($name);
    }

    public function makeMigration($args)
    {
        $name = $args[0] ?? '';

        if (empty($name)) {
            echo "Please specify a name.\n";
            return;
        }

        if (file_exists("database/migrations/$name.sql")) {
            echo "File already exists.\n";
            return;
        }

        $templateUp = <<<EOT
        CREATE TABLE IF NOT EXISTS `$name` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        ) ;
        EOT;

        file_put_contents("database/migrations/$name.up.sql", $templateUp);

        $templateDown = <<<EOT
        DROP TABLE IF EXISTS `$name`;
        EOT;

        file_put_contents("database/migrations/$name.down.sql", $templateDown);

        echo "Migration created successfully.\n";
    }

    public function makeModel($args)
    {
        $name = $args[0] ?? '';

        if (empty($name)) {
            echo "Please specify a name.\n";
            return;
        }

        $className = ucfirst($name);

        if (file_exists("app/Models/$className.php")) {
            echo "File already exists.\n";
            return;
        }

        $template = <<<EOT
        <?php

        namespace App\Models;

        use Core\Model;

        class $className extends Model
        {
            public static string \$table = '$name';
        }
        EOT;

        file_put_contents("app/Models/$className.php", $template);

        echo "Model '$className' created successfully in 'app/Models/$className.php'.\n";
    }

    public function makeView($args)
    {
        $name = $args[0] ?? '';

        if (empty($name)) {
            echo "Please specify a name.\n";
            return;
        }


        if (file_exists("view/$name.php")) {
            echo "File already exists.\n";
            return;
        }

        $template = <<<EOT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="/build/output.css">
            <script src="/build/main.js" defer></script>
            <title><?= \$title ?></title>
        </head>
        <body>
            <h1>Hello $name!</h1>
        </body>
        </html>
        EOT;

        file_put_contents("view/$name.php", $template);

        echo "View '$name' created successfully in 'view/$name.php'.\n";
    }
}


