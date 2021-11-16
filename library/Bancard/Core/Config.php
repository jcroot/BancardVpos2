<?php


namespace Bancard\Core;


class Config {
	/**
	 *
	 * Get defined configuration constants.
	 *
	 */
	public static function get($key)
	{
        $key = strtoupper($key);
		return $_ENV[$key];
	}
}