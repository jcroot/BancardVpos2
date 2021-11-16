<?php


namespace Bancard\Operations\Buy;


use Bancard\Core\Config;
use Bancard\Core\Environments;
use Bancard\Core\Request;
use Bancard\Operations\Operations;

class MultiCharge extends Request{
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

		if ( ! array_key_exists( 'number_of_payments', $data ) ) {
			throw new \InvalidArgumentException( "Number of Payments argument was not found [number_of_payments]." );
		}

		if ( ! array_key_exists( 'alias_token', $data ) ) {
			throw new \InvalidArgumentException( "Alias Token argument was not found [alias_token]." );
		}

		if (!array_key_exists('items', $data)) {
			throw new \InvalidArgumentException("Items argument was not found [items].");
		}

		if ( ! array_key_exists( 'additional_data', $data ) ) {
			throw new \InvalidArgumentException( "Additional data argument was not found [additional_data]." );
		}
	}

	public static function init( array $data ) {
		$self = new self();

        $testmode = (Config::get('APPLICATION_ENV') === 'staging');

        $public_key = ( $testmode ) ? Config::get('staging_public_key') : Config::get('production_public_key');
        $secret_key = ( $testmode ) ? Config::get('staging_private_key') : Config::get('production_private_key');

		$self->validateData( $data );
		# Set Enviroment.
		$environment = ( $testmode ) ? Environments::STAGING_URL : Environments::PRODUCTION_URL;
		$self->factory( $environment, Operations::MULTI_BUY_CHARGE );

		# Attach data.
		foreach ( $data as $key => $value ) {
			$self->addData( $key, $value );
		}

		if ( ! empty( $public_key ) && ! empty( $secret_key ) ) {
			$self->addData( 'public_key', $public_key );
			$self->addData( 'secret_key', $secret_key );
		}

		# Generate token.
		$self->getToken( 'multi_buy_charge' );
		# Create operation array.
		$self->makeOperationObject();

		return $self;
	}
}
