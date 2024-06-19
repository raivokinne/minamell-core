<?php

namespace Minamell\Minamell;

class Crafter
{
    protected $commands = [];

    private function __construct()
    {
        $this->registerCommand('list', [$this, 'listCommands']);
        $this->registerCommand('make:controller', [$this, 'makeController']);
        $this->registerCommand('migrate:up', [$this, 'migrateUp']);
        $this->registerCommand('migrate:down', [$this, 'migrateDown']);
        $this->registerCommand('make:migration', [$this, 'makeMigration']);
        $this->registerCommand('make:model', [$this, 'makeModel']);
        $this->registerCommand('make:view', [$this, 'makeView']);
    }
    /**
     * @return void
     * @param mixed $argv
     */
    public function run($argv): void
    {
        $command = $argv[1] ?? 'list';

        if (isset($this->commands[$command])) {
            call_user_func($this->commands[$command], array_slice($argv, 2));
        } else {
            echo "Command '$command' not found.\n";
            $this->listCommands();
        }
    }
    /**
     * @return void
     * @param mixed $name
     * @param callable(): mixed $callback
     */
    private function registerCommand($name, callable $callback): void
    {
        $this->commands[$name] = $callback;
    }
    /**
     * @return void
     */
    private function listCommands(): void
    {
        echo "Available commands:\n";
        foreach (array_keys($this->commands) as $command) {
            echo " - $command\n";
        }
    }
    /**
     * @return void
     * @param mixed $args
     */
    private function makeController($args): void
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

        namespace App\Controllers;

        use function Minamell\Minamell\view;

        view('$name', [
            'title' => '$name',
        ]);

        EOT;

        file_put_contents("app/controllers/$name.php", $template);

        echo "Controller created successfully.\n";

        $this->listCommands();
    }
    /**
     * @return void
     * @param mixed $args
     */
    private function migrateUp($args): void
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
    /**
     * @return void
     * @param mixed $args
     */
    private function migrateDown($args): void
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
    /**
     * @return void
     * @param mixed $args
     */
    private function makeMigration($args): void
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
    /**
     * @return void
     * @param mixed $args
     */
    private function makeModel($args): void
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
            private static string \$table = '$name';
        }
        EOT;

        file_put_contents("app/Models/$className.php", $template);

        echo "Model '$className' created successfully in 'app/Models/$className.php'.\n";
    }
    /**
     * @return void
     * @param mixed $args
     */
    private function makeView($args): void
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


