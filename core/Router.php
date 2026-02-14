<?php

namespace Minamell\Minamell;

use Minamell\Minamell\Middleware\Middleware;

class Router
{
    protected static $routes = [];

    /**
     * Add a route to the router.
     *
     * @param string $method
     * @param string $url
     * @param mixed  $controller  Closure | [ClassName::class, 'method'] | 'file.php'
     * @return Router
     */
    protected static function add(string $method, string $url, $controller): Router
    {
        self::$routes[] = [
            'method'     => $method,
            'url'        => $url,
            'controller' => $controller,
            'middleware' => null,
        ];

        return new static;
    }

    /**
     * Define a GET route.
     *
     * @param string $url
     * @param mixed  $controller
     * @return Router
     */
    public static function get(string $url, $controller): Router
    {
        return self::add('GET', $url, $controller);
    }

    /**
     * Define a POST route.
     *
     * @param string $url
     * @param mixed  $controller
     * @return Router
     */
    public static function post(string $url, $controller): Router
    {
        return self::add('POST', $url, $controller);
    }

    /**
     * Define a PUT route.
     *
     * @param string $url
     * @param mixed  $controller
     * @return Router
     */
    public static function put(string $url, $controller): Router
    {
        return self::add('PUT', $url, $controller);
    }

    /**
     * Define a PATCH route.
     *
     * @param string $url
     * @param mixed  $controller
     * @return Router
     */
    public static function patch(string $url, $controller): Router
    {
        return self::add('PATCH', $url, $controller);
    }

    /**
     * Define a DELETE route.
     *
     * @param string $url
     * @param mixed  $controller
     * @return Router
     */
    public static function delete(string $url, $controller): Router
    {
        return self::add('DELETE', $url, $controller);
    }

    /**
     * Attach middleware to the last added route.
     *
     * @param mixed $key
     * @return Router
     */
    public static function only($key): Router
    {
        self::$routes[array_key_last(self::$routes)]['middleware'] = $key;

        return new static;
    }

    /**
     * Dispatch the router and call the corresponding controller.
     *
     * Supported controller formats:
     *   1. Closure                      → called with URI parameters
     *   2. [ClassName::class, 'method'] → class instantiated, method called
     *
     * @param string $method
     * @param string $url
     * @return void
     */
    public static function dispatch(string $method, string $url): void
    {
        foreach (self::$routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            $pattern = str_replace('/', '\/', $route['url']);
            $pattern = preg_replace('/\{([^\/]+)\}/', '(?P<$1>[^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (!preg_match($pattern, $url, $matches)) {
                continue;
            }

            $parameters = array_filter(
                $matches,
                fn($key) => !is_int($key),
                ARRAY_FILTER_USE_KEY
            );

            Middleware::resolve($route['middleware'] ?? null);

            if ($route['controller'] instanceof \Closure) {
                call_user_func_array($route['controller'], $parameters);
                return;
            }

            if (is_array($route['controller'])) {
                [$class, $action] = $route['controller'];

                if (!class_exists($class)) {
                    self::abort(500, "Controller class [{$class}] not found.");
                }

                $instance = new $class();

                if (!method_exists($instance, $action)) {
                    self::abort(500, "Method [{$action}] not found on [{$class}].");
                }

                call_user_func_array([$instance, $action], $parameters);
                return;
            }

            self::abort(500, 'Unsupported controller type.');
            return;
        }

        self::abort(404, 'Route not found.');
    }

    /**
     * Get the previous URL from the Referer header.
     *
     * @return string
     */
    public static function previousUrl(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '/';
    }

    /**
     * Abort the request with a specific HTTP status code.
     *
     * @param int    $code
     * @param string $message
     * @return void
     */
    public static function abort(int $code = 404, string $message = ''): void
    {
        http_response_code($code);
        die($message);
    }
}
