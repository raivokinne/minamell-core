<?php

namespace Minamell\Minamell;

class View
{
    /**
     * The base directory where view files live.
     * Defaults to <project-root>/view but can be overridden via View::setBasePath().
     *
     * @var string|null
     */
    protected static ?string $basePath = null;

    // -------------------------------------------------------------------------
    // Configuration
    // -------------------------------------------------------------------------

    /**
     * Set the base path for views.
     *
     * Call this once in bootstrap/app.php:
     *   View::setBasePath(BASE_PATH . '/view');
     *
     * @param string $path  Absolute path to the views directory
     * @return void
     */
    public static function setBasePath(string $path): void
    {
        static::$basePath = rtrim($path, '/\\');
    }

    /**
     * Resolve the base path.
     *
     * Falls back to walking up from this file's location if no explicit path
     * was configured, but an explicit setBasePath() call is always preferred.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        if (static::$basePath !== null) {
            return static::$basePath;
        }

        return dirname(__DIR__, 4) . '/view';
    }

    // -------------------------------------------------------------------------
    // Rendering
    // -------------------------------------------------------------------------

    /**
     * Render a view and output it directly.
     *
     * Supports dot-notation for subdirectories:
     *   View::render('auth.login')  →  view/auth/login.php
     *
     * @param string $view  View name, e.g. 'welcome' or 'auth.login'
     * @param array  $data  Variables to extract into the view's scope
     * @return void
     *
     * @throws \InvalidArgumentException When the view file does not exist
     */
    public static function render(string $view, array $data = []): void
    {
        $path = static::resolve($view);

        extract($data, EXTR_SKIP);
        require $path;
    }

    /**
     * Render a view and return its output as a string.
     *
     * Useful when you need to capture a view's output, e.g. for emails or
     * embedding one view inside another.
     *
     *   $html = View::make('emails.welcome', ['name' => 'Raivo']);
     *
     * @param string $view
     * @param array  $data
     * @return string
     */
    public static function make(string $view, array $data = []): string
    {
        ob_start();
        static::render($view, $data);
        return ob_get_clean();
    }

    /**
     * Resolve a view name to its absolute file path.
     *
     * @param string $view
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected static function resolve(string $view): string
    {
        $relative = str_replace('.', DIRECTORY_SEPARATOR, $view);
        $path     = static::getBasePath() . DIRECTORY_SEPARATOR . $relative . '.php';

        if (!file_exists($path)) {
            throw new \InvalidArgumentException(
                "View [{$view}] not found. Looked in: {$path}"
            );
        }

        return $path;
    }
}
