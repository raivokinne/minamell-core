<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{
    protected $routes = [];
    /**
     * @return Router
     * @param mixed $method
     * @param mixed $url
     * @param mixed $controller
     */
    protected function add($method, $url, $controller): Router
    {
        $this->routes[] = [
            'method' => $method,
            'url' => $url,
            'controller' => $controller
        ];

        return $this;
    }
    /**
     * @return Router
     * @param mixed $url
     * @param mixed $controller
     */
    public function get($url, $controller): Router
    {
        return $this->add('GET', $url, $controller);
    }
    /**
     * @return Router
     * @param mixed $url
     * @param mixed $controller
     */
    public function post($url, $controller): Router
    {
        return $this->add('POST', $url, $controller);
    }

    /**
     * @return Router
     * @param mixed $url
     * @param mixed $controller
     */
    public function put($url, $controller): Router
    {
        return $this->add('PUT', $url, $controller);
    }

    /**
     * @return Router
     * @param mixed $url
     * @param mixed $controller
     */
    public function patch($url, $controller): Router
    {
        return $this->add('PATCH', $url, $controller);
    }

    /**
     * @return Router
     * @param mixed $url
     * @param mixed $controller
     */
    public function delete($url, $controller): Router
    {
        return $this->add('DELETE', $url, $controller);
    }
    /**
     * @return Router
     * @param mixed $key
     */
    public function only($key): Router
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }
    /**
     * @return void
     * @param mixed $method
     * @param mixed $url
     */
    public function dispatch($method, $url): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method)) {
                $pattern = str_replace('/', '\/', $route['url']);
                $pattern = preg_replace('/\{([^\/]+)\}/', '(?<$1>[^\/]+)', $pattern);
                $pattern = '/^' . $pattern . '$/';

                if (preg_match($pattern, $url, $matches)) {
                    $parameters = [];
                    foreach ($matches as $key => $value) {
                        if (!is_int($key)) {
                            $parameters[$key] = $value;
                        }
                    }

                    Middleware::resolve($route['middleware'] ?? null);

                    $controllerPath = BASE_PATH . '/app/controllers/' . $route['controller'];
                    if (file_exists($controllerPath)) {
                        extract($parameters);
                        require $controllerPath;
                    } else {
                        $this->abort(404);
                    }
                    return;
                }
            }
        }

        $this->abort(404);
    }
    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }
    /**
     * @return void
     * @param mixed $code
     */
    public function abort($code = 404): void
    {
        http_response_code($code);

        require BASE_PATH . 'view/errors/' . $code . '.php';

        die();
    }
}
