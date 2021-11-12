<?php


namespace Bancard\Exception;


use Bancard\Util\CaseInsensitiveArray;

abstract class ApiErrorException extends \Exception implements ExceptionInterface {
	/**
	 * @var null | string
	 */
	protected $httpBody;
	/**
	 * @var null | array | CaseInsensitiveArray
	 */
	protected $httpHeaders;
	/**
	 * @var null | boolean
	 */
	protected $httpStatus;
	/**
	 * @var null | array<string, mixed>
	 */
	protected $jsonBody;

	public static function factory($message,
		$httpStatus = null,
		$httpBody = null,
		$jsonBody = null,
		$httpHeaders = null)
	{
		$instance = new static($message);
		$instance->setHttpStatus($httpStatus);
		$instance->setHttpBody($httpBody);
		$instance->setHttpHeaders($httpHeaders);
		$instance->setJsonBody($jsonBody);
		return $instance;
	}

	/**
	 * @return string|null
	 */
	public function getHttpBody()
	{
		return $this->httpBody;
	}

	/**
	 * @param string|null $httpBody
	 */
	public function setHttpBody($httpBody)
	{
		$this->httpBody = $httpBody;
	}

	/**
	 * @return array|CaseInsensitiveArray|null
	 */
	public function getHttpHeaders()
	{
		return $this->httpHeaders;
	}

	/**
	 * @param array|CaseInsensitiveArray|null $httpHeaders
	 */
	public function setHttpHeaders($httpHeaders)
	{
		$this->httpHeaders = $httpHeaders;
	}

	/**
	 * @return bool|null
	 */
	public function getHttpStatus()
	{
		return $this->httpStatus;
	}

	/**
	 * @param bool|null $httpStatus
	 */
	public function setHttpStatus($httpStatus)
	{
		$this->httpStatus = $httpStatus;
	}

	/**
	 * @return array|null
	 */
	public function getJsonBody()
	{
		return $this->jsonBody;
	}

	/**
	 * @param array|null $jsonBody
	 */
	public function setJsonBody($jsonBody)
	{
		$this->jsonBody = $jsonBody;
	}

	public function __toString()
	{
		// TODO: Implement __toString() method.
	}
}