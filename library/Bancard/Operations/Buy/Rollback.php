<?php


namespace Bancard\Operations\Buy;


use Bancard\Core\Environments;
use Bancard\Core\Request;
use Bancard\Operations\Operations;

class Rollback extends Request {

	/**
	 *
	 * Validates data
	 *
	 * @return void
	 *
	 **/
	private function validateData( array $data ) {
		if ( count( $data ) < 1 ) {
			throw new \InvalidArgumentException( "Invalid argument count (at least 5 values are expected)." );
		}

		if ( ! array_key_exists( 'shop_process_id', $data ) ) {
			throw new \InvalidArgumentException( "Shop process id not found [shop_process_id]." );
		}
	}

	public static function init( array $data, $public_key, $secret_key, $testmode = false ) {
		$self = new self();

		$self->validateData( $data );
		# Set Enviroment.
		$environment = ( $testmode ) ? Environments::STAGING_URL : Environments::PRODUCTION_URL;
		$self->factory( $environment, Operations::SINGLE_BUY_ROLLBACK );

		# Attach data.
		foreach ( $data as $key => $value ) {
			$self->addData( $key, $value );
		}

		if ( ! empty( $public_key ) && ! empty( $secret_key ) ) {
			$self->addData( 'public_key', $public_key );
			$self->addData( 'secret_key', $secret_key );
		}

		# Generate token.
		$self->getToken( 'rollback' );
		# Create operation array.
		$self->makeOperationObject('amount');

		return $self;
	}
}