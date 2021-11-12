<?php


namespace Bancard\Operations\Cards;


use Bancard\Core\Environments;
use Bancard\Core\Request;
use Bancard\Operations\Operations;

class NewToken extends Request {
	/**
	 *
	 * Validates data
	 *
	 * @return void
	 *
	 **/
	private function validateData( array $data ) {

		if ( count( $data ) < 5 ) {
			throw new \InvalidArgumentException( "Invalid argument count (at least 5 values are expected)." );
		}

		if ( ! array_key_exists( 'user_id', $data ) ) {
			throw new \InvalidArgumentException( "user_id argument was not found [user_id]." );
		}

		if ( ! array_key_exists( 'card_id', $data ) ) {
			throw new \InvalidArgumentException( "card_id argument was not found [card_id]." );
		}

		if ( ! array_key_exists( 'user_cell_phone', $data ) ) {
			throw new \InvalidArgumentException( "user_cell_phone argument was not found [user_cell_phone]." );
		}

		if ( ! array_key_exists( 'user_mail', $data ) ) {
			throw new \InvalidArgumentException( "user_mail argument was not found [user_mail]." );
		}

		if ( ! array_key_exists( 'return_url', $data ) ) {
			throw new \InvalidArgumentException( "return_url argument was not found [return_url]." );
		}
	}

	public static function init( array $data, $public_key, $secret_key, $testmode = false ) {
		# Instance.
		$self = new self;
		# Validate data.
		$self->validateData( $data );
		# Set Enviroment.
		$environment = ( $testmode ) ? Environments::STAGING_URL : Environments::PRODUCTION_URL;
		$self->factory( $environment, Operations::CARDS_NEW );

		# Attach data.
		foreach ( $data as $key => $value ) {
			$self->addData( $key, $value );
		}

		if ( ! empty( $public_key ) && ! empty( $secret_key ) ) {
			$self->addData( 'public_key', $public_key );
			$self->addData( 'secret_key', $secret_key );
		}

		# Generate token.
		$self->getToken( 'cards_new' );
		# Create operation array.
		$self->makeOperationObject();

		return $self;
	}
}