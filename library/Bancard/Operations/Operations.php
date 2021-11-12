<?php


namespace Bancard\Operations;


class Operations {
	const SINGLE_BUY = "/vpos/api/0.3/single_buy";
	const SINGLE_BUY_GET_CONFIRMATION = "/vpos/api/0.3/single_buy/confirmations";
	const SINGLE_BUY_ROLLBACK = "/vpos/api/0.3/single_buy/rollback";
	const CARDS_NEW = "/vpos/api/0.3/cards/new";
	const USERS_CARDS = "/vpos/api/0.3/users/%s/cards";
	const CHARGE = "/vpos/api/0.3/charge";
	// Multi buy operations url paths.
	const MULTI_BUY_URL = "/vpos/api/0.3/multi_buy";
	const MULTI_BUY_PAYMENTS_URL = "/payment/multi_buy";
	const MULTI_BUY_ROLLBACK_URL = "/vpos/api/0.3/multi_buy/rollback";
	const MULTI_BUY_CONFIRM_URL = "/vpos/api/0.3/multi_buy/confirmations";
	const MULTI_BUY_USER_VERIFICATION = "/vpos/api/0.3/verification/credit_card";
	const MULTI_BUY_CHARGE = "/vpos/api/0.3/multi/charge";

}
