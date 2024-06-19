<?php

namespace Minamell\Minamell\Commands;

class Server
{
    public function __construct()
    {
        $this->run();
    }
    /**
     * @return void
     */
    public function run(): void
    {
        require BASE_PATH . 'bootstrap.php';
    }

    public function start(): void
    {
        $host = '127.0.0.1';
        $port = 8000;
        $documentRoot = BASE_PATH . 'public';

        $command = sprintf('php -S %s:%d -t %s', $host, $port, $documentRoot);
        passthru($command);
    }
}
