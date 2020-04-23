<?php

namespace App\Enum;

class DogStateEnum extends Enum {

	public function __construct() {
		parent::__construct(__CLASS__);
	}

	/** @const neaktivní pes - nutno schválit */
	const INACTIVE = 0;

	/** @const normální stav, pes je aktivní */
	const ACTIVE = 1;

	/** @const pes je smazaný */
	const DELETED = 2;
}