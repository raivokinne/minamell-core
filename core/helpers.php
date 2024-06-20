<?php

namespace Minamell\Minamell;

/**
 * @return void
 * @param mixed $args
 */
function dd(...$args): void
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
function view($view, $data = []): void
{
	extract($data);
	require '../view/' . $view . '.php';
}
/**
 * @return void
 * @param mixed $path
 */
function redirect($path): void
{
	header('Location: ' . $path);
	exit;
}
/**
 * @return string
 * @param mixed $method
 */
function method($method): string
{
	return "<input type='hidden' name='_method' value='$method'>";
}
