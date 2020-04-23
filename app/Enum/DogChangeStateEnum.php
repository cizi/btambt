<?php

namespace App\Enum;

class DogChangeStateEnum extends Enum {

	public function __construct() {
		parent::__construct(__CLASS__);
	}

	/** @const vloženo */
	const INSERTED = 0;

	/** @const schváleno */
	const PROCEEDED = 1;

	/** @const odmítnuto */
	const DECLINED = 2;
}