<?php

class View
{
	public static function render($view, $data = [])
	{
		extract($data);
		require '../view/' . $view . '.php';
	}

	public static function exists($view)
	{
		return file_exists('../view/' . $view . '.php');
	}

	public static function first($views, $data)
	{
		foreach ($views as $view) {
			if (self::exists($view)) {
				self::render($view, $data);
				return;
			}
		}
	}
}
