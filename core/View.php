<?php

namespace Minamell\Minamell;

class View
{
    /**
     * @param mixed $view
     * @param mixed $data
     */
    public static function render($view, $data = []): void
	{
		extract($data);
		require __DIR__ . '/../../../view/' . $view . '.php';
	}
}
