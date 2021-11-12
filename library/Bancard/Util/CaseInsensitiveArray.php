<?php


namespace Bancard\Util;

class CaseInsensitiveArray implements \ArrayAccess, \Countable, \IteratorAggregate {
	private $container = [];

	/**
	 * CaseInsensitiveArray constructor.
	 */
	public function __construct( $initial_array = [] ) {
		$this->container = array_change_key_case( $initial_array, CASE_LOWER );
	}

	public function getIterator() {
		// TODO: Implement getIterator() method.
		return new \ArrayIterator( $this->container );
	}

	public function offsetExists( $offset ) {
		// TODO: Implement offsetExists() method.

		return isset( $this->container[ $offset ] );
	}

	public function offsetGet( $offset ) {
		// TODO: Implement offsetGet() method.
		$offset = static::maybeLowercase( $offset );

		return isset( $this->container[ $offset ] ) ? $this->container[ $offset ] : null;
	}

	public function offsetSet( $offset, $value ) {
		// TODO: Implement offsetSet() method.
		$offset = static::maybeLowercase( $offset );
		if ( null === $offset ) {
			$this->container[] = $value;
		} else {
			$this->container[ $offset ] = $value;
		}
	}

	public function offsetUnset( $offset ) {
		// TODO: Implement offsetUnset() method.
		$offset = static::maybeLowercase( $offset );
		unset( $this->container[ $offset ] );
	}

	public function count() {
		// TODO: Implement count() method.
		return $this->count( $this->container );
	}

	private static function maybeLowerCase( $v ) {
		if ( is_string( $v ) ) {
			return strtolower( $v );
		}

		return $v;
	}
}