<?php


namespace Bancard\Util;


abstract class Util {
	public static function encodeParameters($params)
	{
		$flattenedParams = self::flattenParams($params);
		$entries = [];

		foreach ($flattenedParams as $param) {
			list($k, $v) = $param;
			array_push($entries, self::urlEncode($k) . '=' . self::urlEncode($v));
		}

		return implode('&', $entries);
	}

	public static function json($params)
	{
		return json_encode($params);
	}

	public static function urlEncode($key)
	{
		$s = urlencode($key);

		$s = str_replace('%5B', '[', $s);

		return str_replace('%5D', ']', $s);
	}

	public static function flattenParams($params, $parentKey = null)
	{
		$result = [];

		foreach ($params as $key => $value) {
			$calculatedKey = $parentKey ? "{$parentKey}[{$key}]" : $key;

			if (self::isList($value)) {
				$result = \array_merge($result, self::flattenParamsList($value, $calculatedKey));
			} elseif (\is_array($value)) {
				$result = \array_merge($result, self::flattenParams($value, $calculatedKey));
			} else {
				\array_push($result, [$calculatedKey, $value]);
			}
		}

		return $result;
	}

	public static function isList($array)
	{
		if (!\is_array($array)) {
			return false;
		}
		if ($array === []) {
			return true;
		}
		if (\array_keys($array) !== \range(0, \count($array) - 1)) {
			return false;
		}

		return true;
	}

	public static function flattenParamsList($value, $calculatedKey)
	{
		$result = [];

		foreach ($value as $i => $elem) {
			if (self::isList($elem)) {
				$result = \array_merge($result, self::flattenParamsList($elem, $calculatedKey));
			} elseif (\is_array($elem)) {
				$result = \array_merge($result, self::flattenParams($elem, "{$calculatedKey}[{$i}]"));
			} else {
				\array_push($result, ["{$calculatedKey}[{$i}]", $elem]);
			}
		}

		return $result;
	}
}