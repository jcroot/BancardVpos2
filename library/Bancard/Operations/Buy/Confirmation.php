<?php


namespace Bancard\Operations\Buy;


use Bancard\Core\Config;
use Bancard\Core\Environments;
use Bancard\Core\Request;
use Bancard\Operations\Operations;

class Confirmation extends Request {

	/**
	 *
	 * Validates data
	 *
	 * @return void
	 *
	 **/

	private function validateData( array $data ) {

		if ( count( $data ) < 1 ) {
			throw new \InvalidArgumentException( "Invalid argument count (at least 1 value are expected)." );
		}

		if ( ! array_key_exists( 'shop_process_id', $data ) ) {
			throw new \InvalidArgumentException( "Shop process id not found [shop_process_id]." );
		}
	}


	public static function init( array $data ) {
		$self = new self();

		$self->validateData( $data );
		# Set Enviroment.

        $testmode = (Config::get('APPLICATION_ENV') === 'staging');

        $public_key = ( $testmode ) ? Config::get('staging_public_key') : Config::get('production_public_key');
        $secret_key = ( $testmode ) ? Config::get('staging_private_key') : Config::get('production_private_key');

		$environment = ( $testmode ) ? Environments::STAGING_URL : Environments::PRODUCTION_URL;
		$self->factory( $environment, Operations::SINGLE_BUY_GET_CONFIRMATION );

		# Attach data.
		foreach ( $data as $key => $value ) {
			$self->addData( $key, $value );
		}

		if ( ! empty( $public_key ) && ! empty( $secret_key ) ) {
			$self->addData( 'public_key', $public_key );
			$self->addData( 'secret_key', $secret_key );
		}

		# Generate token.
		$self->getToken( 'single_buy_confirmation' );
		# Create operation array.
		$self->makeOperationObject();

		return $self;
	}
}