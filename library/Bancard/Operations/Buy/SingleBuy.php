<?php


namespace Bancard\Operations\Buy;


use Bancard\Core\Config;
use Bancard\Core\Environments;
use Bancard\Core\Request;
use Bancard\Operations\Operations;

class SingleBuy extends Request {

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

		if ( ! array_key_exists( 'shop_process_id', $data ) ) {
			throw new \InvalidArgumentException( "Shop process id not found [shop_process_id]." );
		}

		if ( ! array_key_exists( 'amount', $data ) ) {
			throw new \InvalidArgumentException( "Amount argument was not found [amount]." );
		}

		if ( ! array_key_exists( 'currency', $data ) ) {
			throw new \InvalidArgumentException( "Currency argument was not found [currency]." );
		}

		if ( ! array_key_exists( 'description', $data ) ) {
			throw new \InvalidArgumentException( "Description argment was not found [description]." );
		}

		if ( ! array_key_exists( 'additional_data', $data ) ) {
			throw new \InvalidArgumentException( "Additional data argument was not found [additional_data]." );
		}
	}

	public static function init( array $data, $testmode = false ) {
		$self = new self();

		$self->validateData( $data );

        $public_key = ( $testmode ) ? Config::get('staging_public_key') : Config::get('production_public_key');
        $secret_key = ( $testmode ) ? Config::get('staging_private_key') : Config::get('production_private_key');

		$self->factory( ( $testmode ) ? Environments::STAGING_URL : Environments::PRODUCTION_URL, Operations::SINGLE_BUY );

		foreach ( $data as $key => $value ) {
			$self->addData( $key, $value );
		}

		if ( ! empty( $public_key ) && ! empty( $secret_key ) ) {
			$self->addData( 'public_key', $public_key );
			$self->addData( 'secret_key', $secret_key );
		}

		$self->getToken( 'single_buy' );

		$self->makeOperationObject();

		return $self;
	}
}