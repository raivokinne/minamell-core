<?php

namespace Core\Middleware;

class Auth
{
    /**
     * @return void
     */
    public function handle(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            die();
        }
    }
}
