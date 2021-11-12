<?php


namespace Bancard\Core;


interface ClientInterface {

	public static function request($method, $url, $headers, $params);
}