<?php

namespace App\Enum;

class OrderingEnum extends Enum {

	public function __construct() {
		parent::__construct(__CLASS__);
	}

	/** @const owner */
	//const USER_OWNER = 22;

	/** @const registered */
	const ASCENDING = 1;

	/** @const breeder */
	const DESCENDING = 2;
}