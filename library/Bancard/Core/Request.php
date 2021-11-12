<?php


namespace Bancard\Core;


use Bancard\Common\ApiResponse;
use Bancard\Exception\AuthenticationException;
use Bancard\Exception\InvalidTokenException;
use Bancard\Exception\UnknownApiErrorException;

class Request {

	private $token;

	public $path;

	private $url;

	private $environment;

	protected $data = [];

	public $operation = [];

	protected $response_data;

	protected $code;

	private $public_key = "";

	public function factory( $env, $path ) {
		$this->environment = $env;
		$this->path        = $path;
	}

	public function addData( $k, $v ) {
		$this->data[ $k ] = $v;
	}

	public function request( $method, $params ) {
		$this->url = $this->environment . $this->path;

		list( $rbody, $rcode, $rheaders ) = HTTP::request( $method, $this->url, "application/json", $params );
		$json = $this->response( $rbody, $rcode, $rheaders );

		return new ApiResponse( $rbody, $rcode, $rheaders, $json );
	}

	public function launch( $method ) {
		return $this->request( $method, $this->operation );
	}

	public function handleErrorResponse( $rbody, $rcode, $rheaders, $resp ) {
		$msg = isset( $resp['message'] ) ? $resp['message'] : null;

		switch ( $rcode ) {
			case 401:
				return AuthenticationException::factory( $msg, $rcode, $rbody, $resp, $rheaders );

			case 403:
				return InvalidTokenException::factory( $msg, $rcode, $rbody, $resp, $rheaders );

			default:
				return UnknownApiErrorException::factory( $msg, $rcode, $rbody, $resp, $rheaders );
		}
	}

	private function response( $rbody, $rcode, $rheaders ) {
		$resp      = json_decode( $rbody, true );
		$jsonError = json_last_error();

		if ( $rcode < 200 || $rcode >= 300 ) {
			$this->handleErrorResponse( $rbody, $rcode, $rheaders, $resp );
		}

		return $resp;
	}

	protected function getToken( $type ) {
		$this->token = Token::create( $type, $this->data );
	}

	/**
	 *
	 * Get configured public key.
	 *
	 * @return string | null
	 *
	 **/

	private function getPublicKey() {
		if ( ! empty( $this->data['public_key'] ) ) {
			$this->public_key = $this->data['public_key'];
		}

		return $this->public_key;
	}

	/**
	 * Prepare operation object with expected structure.
	 *
	 * @param string $removeField
	 */
	protected function makeOperationObject( $removeField = "" ) {
		$this->operation['public_key']         = $this->getPublicKey();
		$this->operation['operation']          = [];
		$this->operation['operation']['token'] = $this->token->get();
		if ( isset( $this->shop_process_id ) && ! is_null( $this->shop_process_id ) ) {
			if ( $this->shop_process_id > 0 ) {
				$this->operation['operation']['shop_process_id'] = $this->shop_process_id;
			}
		}
		if ( count( $this->data ) > 0 ) {
			foreach ( $this->data as $key => $value ) {
				if (is_array($removeField)){
					if ( $key == "public_key" or $key == "secret_key" or in_array($key, $removeField) ) {
						continue;
					}
				}else{
					if ( $key == "public_key" or $key == "secret_key" or $key == $removeField ) {
						continue;
					}
				}
				$this->operation['operation'][ $key ] = $value;
			}
		}
	}
}
