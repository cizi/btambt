<?php

namespace App\Enum;

class UserRoleEnum extends Enum {

	public function __construct() {
		parent::__construct(__CLASS__);
	}

	/** @const owner */
	//const USER_OWNER = 22;

	/** @const registered */
	const USER_REGISTERED = 33;

	/** @const editor */
	const USER_EDITOR = 55;

	/** @const string */
	const USER_ROLE_ADMINISTRATOR = 99;
}