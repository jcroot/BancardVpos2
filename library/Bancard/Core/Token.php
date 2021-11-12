<?php


namespace Bancard\Core;


class Token {
	private $type;

	private $private_key = "";

	private $shop_process_id;

	private $data;

	private $unhashed_string = "";

	private $hash;

	private $user_id;

	private $card_id;

	/**
	 *
	 * Set data and hash.
	 *
	 **/

	private function __construct($type, $data)
	{
		$this->type = $type;
		$this->data = $data;
		foreach ($data as $key => $value){
			if ($key == 'card_id' || $key == 'user_id' || $key == 'shop_process_id'){
				$this->$key = $value;
			}
		}
		$this->getPrivateKey();
		$this->make();
		$this->hash();
	}

	/**
	 *
	 * Get configured private key.
	 *
	 **/

	private function getPrivateKey()
	{
		if (!empty($this->data['secret_key'])) {
			$this->private_key = $this->data['secret_key'];
		}

		return $this->private_key;
	}

	/**
	 *
	 * Construct token string for given operation type.
	 *
	 **/

	private function make()
	{
		if ($this->type == "cards_new"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->card_id;
			$this->unhashed_string .= $this->user_id;
			$this->unhashed_string .= "request_new_card";
		}

		if ($this->type == "cards_list"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->user_id;
			$this->unhashed_string .= "request_user_cards";
		}

		if ($this->type == "charge"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= $this->type;
			$this->unhashed_string .= $this->data['amount'];
			$this->unhashed_string .= $this->data['currency'];
			$this->unhashed_string .= $this->data['alias_token'];
		}

		if ($this->type == "remove_card"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= "delete_card";
			$this->unhashed_string .= $this->user_id;
			$this->unhashed_string .= $this->data['alias_token'];
		}

		if ($this->type == "rollback"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= "rollback";
			$this->unhashed_string .= "0.00";
		}

		if ($this->type == "single_buy"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= $this->data['amount'];
			$this->unhashed_string .= $this->data['currency'];
		}

		if ($this->type == "single_buy_confirmation"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= "get_confirmation";
		}

		if ($this->type == "multi_buy_charge"){
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= "charge";
			$this->unhashed_string .= $this->data['amount'];
			$this->unhashed_string .= $this->data['number_items'];
		}

		if ($this->type == "multi_buy") {
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= $this->data['number_of_items'];
			$this->unhashed_string .= $this->data['amount_in_us'];
			$this->unhashed_string .= $this->data['amount_in_gs'];
		}

		if ($this->type == "multi_buy_confirm") {
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= "confirm";
			$this->unhashed_string .= $this->data['number_of_items'];
			$this->unhashed_string .= $this->data['amount_in_us'];
			$this->unhashed_string .= $this->data['amount_in_gs'];
		}

		if ($this->type == "multi_buy_rollback") {
			$this->unhashed_string .= $this->private_key;
			$this->unhashed_string .= $this->shop_process_id;
			$this->unhashed_string .= "rollback";
			$this->unhashed_string .= $this->data['number_of_items'];
			$this->unhashed_string .= $this->data['amount_in_us'];
			$this->unhashed_string .= $this->data['amount_in_gs'];
		}
	}

	/**
	 *
	 * MD5 hash of constructed hash.
	 *
	 **/

	private function hash()
	{
		$this->hash = md5($this->unhashed_string);
	}

	/**
	 *
	 * Create and return hash object.
	 *
	 * @return Token object
	 *
	 **/

	public static function create($type, $data = [])
	{
		$self = new self($type, $data);

		return $self;
	}

	/**
	 *
	 * Return hash string.
	 *
	 * @return string
	 *
	 **/

	public function get()
	{
		return $this->hash;
	}
}
