<?php


namespace Bancard\Common;


class ApiResponse {
	/**
	 * @var null|array|CaseInsensitiveArray
	 */
	public $headers;

	/**
	 * @var string
	 */
	public $body;

	/**
	 * @var null|array
	 */
	public $json;

	/**
	 * @var int
	 */
	public $code;

	/**
	 * ApiResponse constructor.
	 * @param array|CaseInsensitiveArray|null $headers
	 * @param string $body
	 * @param array|null $json
	 * @param int $code
	 */
	public function __construct($body, $code, $headers, $json)
	{
		$this->headers = $headers;
		$this->body = $body;
		$this->json = $json;
		$this->code = $code;
	}

	public function getResponse(){

		$entry =  [
			'code' => $this->code
		];

		foreach ($this->json as $k => $v){
			$entry[$k] = $v;
		}

		return $entry;
	}
}