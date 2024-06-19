<?php

namespace Minamell\Minamell\Commands;

class Server
{
    public function start(): void
    {
        $host = '127.0.0.1';
        $port = 8000;
        $documentRoot = 'public';

        $command = sprintf('php -S %s:%d -t %s', $host, $port, $documentRoot);
        passthru($command);
    }
}
