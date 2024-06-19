<?php

namespace Minamell\Minamell\Functions;

class Functions
{
    /**
     * @return void
     * @param mixed $args
     */
    public static function dd(...$args): void
    {
        echo '<pre>';
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo '</pre>';
        die;
    }
    /**
     * @return void
     * @param mixed $view
     * @param mixed $data
     */
    public static function view($view, $data = []): void
    {
        extract($data);
        require BASE_PATH . 'view/' . $view . '.php';
    }
    /**
     * @return void
     * @param mixed $path
     */
    public static function redirect($path): void
    {
        header('Location: ' . $path);
        exit;
    }
    /**
     * @return string
     * @param mixed $method
     */
    public static function method($method): string
    {
        return "<input type='hidden' name='_method' value='$method'>";
    }
}
