<?php


namespace Bancard\Operations\Cards;


use Bancard\Core\Environments;
use Bancard\Core\Request;
use Bancard\Operations\Operations;

class Delete extends Request {

	/**
	 *
	 * Validates data
	 *
	 * @return void
	 *
	 **/
	private function validateData( array $data ) {
		if ( count( $data ) < 2 ) {
			throw new \InvalidArgumentException( "Invalid argument count (at least 5 values are expected)." );
		}
		if ( ! array_key_exists( 'user_id', $data ) ) {
			throw new \InvalidArgumentException( "user_id argument was not found [user_id]." );
		}

		if ( ! array_key_exists( 'alias_token', $data ) ) {
			throw new \InvalidArgumentException( "token argument was not found [token]." );
		}
	}

	public static function init( array $data, $public_key, $secret_key, $testmode = false ) {
		# Instance.
		$self = new self;
		# Validate data.
		$self->validateData( $data );
		# Set Enviroment.
		$environment = ( $testmode ) ? Environments::STAGING_URL : Environments::PRODUCTION_URL;
		$self->factory( $environment, sprintf( Operations::USERS_CARDS, $data['user_id'] ) );

		# Attach data.
		foreach ( $data as $key => $value ) {
			$self->addData( $key, $value );
		}

		if ( ! empty( $public_key ) && ! empty( $secret_key ) ) {
			$self->addData( 'public_key', $public_key );
			$self->addData( 'secret_key', $secret_key );
		}

		# Generate token.
		$self->getToken( 'remove_card' );
		# Create operation array.
		$self->makeOperationObject('user_id');

		return $self;
	}
}