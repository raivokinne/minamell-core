<?php

namespace Core\Middleware;

class Guest
{
    /**
     * @return void
     */
    public function handle(): void
    {
        if ($_SESSION['user'] ?? false) {
            header('Location: /');
            die();
        }
    }
}
