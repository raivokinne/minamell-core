<?php

namespace Minamell\Minamell;

class Helpers
{
    /**
     * Redirect to a given URL.
     *
     * @param string $url
     * @param int    $statusCode
     * @return void
     */
    public static function redirect(string $url, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit();
    }

    /**
     * Dump the given value(s) and die.
     *
     * @param mixed ...$values
     * @return void
     */
    public static function dd(mixed ...$values): void
    {
        foreach ($values as $value) {
            echo '<pre>';
            var_dump($value);
            echo '</pre>';
        }
        die();
    }

    /**
     * Dump the given value(s) without dying.
     *
     * @param mixed ...$values
     * @return void
     */
    public static function dump(mixed ...$values): void
    {
        foreach ($values as $value) {
            echo '<pre>';
            var_dump($value);
            echo '</pre>';
        }
    }

    /**
     * Sanitize a string to prevent XSS.
     *
     * @param string $value
     * @return string
     */
    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Get a value from an array using dot notation.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public static function arrayGet(array $array, string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Check if the current request is an AJAX / fetch request.
     *
     * @return bool
     */
    public static function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Return the base URL of the application.
     *
     * @param string $path
     * @return string
     */
    public static function url(string $path = ''): string
    {
        $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

        return $scheme . '://' . $host . $basePath . '/' . ltrim($path, '/');
    }

    /**
     * Get the current request URI path.
     *
     * @return string
     */
    public static function currentUrl(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Check whether the given string is a valid email address.
     *
     * @param string $email
     * @return bool
     */
    public static function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Generate a random token string.
     *
     * @param int $length
     * @return string
     */
    public static function token(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Convert a string to slug format.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function slug(string $string, string $separator = '-'): string
    {
        $string = mb_strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9\s\-]/', '', $string);
        $string = preg_replace('/[\s\-]+/', $separator, $string);

        return trim($string, $separator);
    }

    /**
     * Truncate a string to a given length, appending a suffix if truncated.
     *
     * @param string $value
     * @param int    $limit
     * @param string $end
     * @return string
     */
    public static function truncate(string $value, int $limit = 100, string $end = '...'): string
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, $limit)) . $end;
    }

    /**
     * Respond with a JSON payload and exit.
     *
     * @param mixed $data
     * @param int   $statusCode
     * @return void
     */
    public static function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    /**
     * Abort the request with an HTTP status code and optional message.
     *
     * @param int    $statusCode
     * @param string $message
     * @return void
     */
    public static function abort(int $statusCode = 404, string $message = ''): void
    {
        http_response_code($statusCode);

        $defaultMessages = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error',
        ];

        $message = $message ?: ($defaultMessages[$statusCode] ?? 'Error');

        echo "<h1>{$statusCode} – {$message}</h1>";
        exit();
    }
}
