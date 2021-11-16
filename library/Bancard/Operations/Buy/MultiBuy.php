<?php


namespace Bancard\Operations\Buy;


use Bancard\Core\Config;
use Bancard\Core\Environments;
use Bancard\Core\Request;
use Bancard\Operations\Operations;

class MultiBuy extends Request
{
    /**
     *
     * Validates data
     *
     * @return void
     *
     **/
    private function validateData(array $data)
    {
        if (count($data) < 6) {
            throw new \InvalidArgumentException("Invalid argument count (at least 5 values are expected).");
        }
        if (!array_key_exists('shop_process_id', $data)) {
            throw new \InvalidArgumentException("Shop process id not found [shop_process_id].");
        }
        if (!array_key_exists('number_of_items', $data)) {
            throw new \InvalidArgumentException("NumberOfItems argument was not found [number_of_items].");
        }
        if (!array_key_exists('amount_in_us', $data)) {
            throw new \InvalidArgumentException("AmountInUS argument was not found [amount_in_us].");
        }
        if (!array_key_exists('amount_in_gs', $data)) {
            throw new \InvalidArgumentException("AmountInGS argument was not found [amount_in_gs].");
        }
        if (!array_key_exists('items', $data)) {
            throw new \InvalidArgumentException("Items argument was not found [items].");
        }
        if (!array_key_exists('additional_data', $data)) {
            throw new \InvalidArgumentException("Additional data argument was not found [additional_data].");
        }
    }

    /**
     *
     * Initialize object
     *
     * @param array $data
     * @return MultiBuy
     *
     */
    public static function init(array $data )
    {
        # Instance.
        $self = new self;
        # Validate data.
        $self->validateData($data);

        $testmode = (Config::get('APPLICATION_ENV') === 'staging');

        $public_key = ( $testmode ) ? Config::get('staging_public_key') : Config::get('production_public_key');
        $secret_key = ( $testmode ) ? Config::get('staging_private_key') : Config::get('production_private_key');

        # Set Enviroment.
	    $environment = ( $testmode ) ? Environments::STAGING_URL : Environments::PRODUCTION_URL;
	    $self->factory( $environment, Operations::MULTI_BUY_URL );

        # Attach data.
        foreach ($data as $key => $value) {
            $self->addData($key, $value);
        }

	    if ( ! empty( $public_key ) && ! empty( $secret_key ) ) {
		    $self->addData( 'public_key', $public_key );
		    $self->addData( 'secret_key', $secret_key );
	    }

        # Generate token.
        $self->getToken('multi_buy');
        # Create operation array.
        $self->makeOperationObject();
        return $self;
    }
}
