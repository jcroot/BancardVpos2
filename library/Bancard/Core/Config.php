<?php


namespace Bancard\Core;


class Config {

	const STAGING_PUBLIC_KEY = "";

	const STAGING_PRIVATE_KEY = "";

	const PRODUCTION_PUBLIC_KEY = "";

	const PRODUCTION_PRIVATE_KEY = "";

	const APPLICATION_ENV = "staging"; //production when are'u ready

	/**
	 *
	 * Get defined configuration constants.
	 *
	 */
	public static function get($key)
	{
		return constant(strtoupper('self::' . $key));
	}
}