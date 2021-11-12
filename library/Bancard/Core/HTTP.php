<?php


namespace Bancard\Core;

use Bancard\Exception\ApiConnectionException;
use Bancard\Util\CaseInsensitiveArray;
use Bancard\Util\Util;

if (!defined('CURL_SSLVERSION_TLSv1_2')) {
	define('CURL_SSLVERSION_TLSv1_2', 6);
}
// @codingStandardsIgnoreEnd

// Available since PHP 7.0.7 and cURL 7.47.0
if (!defined('CURL_HTTP_VERSION_2TLS')) {
	define('CURL_HTTP_VERSION_2TLS', 4);
}

class HTTP extends Util implements ClientInterface {

	public static function request($method, $url, $headers, $params)
	{
		$method = strtolower($method);
		$rcode = 0;
		$errno = 0;
		$message = null;

		$curl = curl_init($url);
		if ('get' === $method) {
			curl_setopt($curl, CURLOPT_HTTPGET, 1);

			if (count($params) > 0) {
				$encoded = self::encodeParameters($params);
				$url = "{$url}?{$encoded}";
			}
			curl_setopt($curl, CURLOPT_URL, $url);

		} else if ('post' === $method) {
			curl_setopt($curl, CURLOPT_POST, true);
			if (is_array($params) && count($params)> 0) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, self::json($params));
			}
		} else if ('delete' === $method) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
			if (is_array($params) && count($params)> 0) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, self::json($params));
			}
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		$rheaders = new CaseInsensitiveArray();
		$headerCallback = function ($curl_object, $header_line) use (&$rheaders) {
			if (false === strpos($header_line, ':')) {
				return strlen($header_line);
			}
			list($key, $value) = explode(':', trim($header_line), 2);
			$rheaders[trim($key)] = trim($value);

			return strlen($header_line);
		};

		curl_setopt($curl, CURLOPT_HEADERFUNCTION, $headerCallback);
		$response = curl_exec($curl);

		if (false === $response) {
			$errno = curl_errno($curl);
			$message = curl_errno($curl);
		} else {
			$rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		}

		curl_close($curl);

		if (false === $response) {
			self::handleCurlError($errno, $message, $url);
		}

		return [$response, $rcode, $rheaders];

		// TODO: Implement request() method.
	}

	public function handleCurlError($errno, $message, $url)
	{
		switch ($errno) {
			case CURLE_COULDNT_CONNECT:
			case CURLE_COULDNT_RESOLVE_HOST:
			case CURLE_OPERATION_TIMEOUTED:
				$msg = "No puedo conectarme a Zimple ({$url}). Verifique si tiene conexion e intente de nuevo.";
				break;

			case CURLE_SSL_CACERT:
			case CURLE_SSL_PEER_CERTIFICATE:
				$msg = "Error de certificado. Verifique si su red no esta interceptando certificados. (Prueba esta url {$url} en tu navegador.)";
				break;

			default:
				$msg = "Error desconocido, al tratar de comunicarse con Zimple.";
		}

		$msg .= "\n\n(Error de red [errno {$errno}]: {$message}";;
		throw new ApiConnectionException($msg);
	}

	private function hasHeader($headers, $name)
	{
		foreach ($headers as $header) {
			if (0 === \strncasecmp($header, "{$name}: ", \strlen($name) + 2)) {
				return true;
			}
		}

		return false;
	}
}
