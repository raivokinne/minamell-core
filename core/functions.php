<?php

function dd(...$args)
{
    echo '<pre>';
    foreach ($args as $arg) {
        var_dump($arg);
    }
    echo '</pre>';
    die;
}

function view($view, $data = [])
{
    extract($data);
    require BASE_PATH . 'view/' . $view . '.php';
}

function redirect($path)
{
    header('Location: ' . $path);
    exit;
}

function method($method)
{
    return "<input type='hidden' name='_method' value='$method'>";
}
