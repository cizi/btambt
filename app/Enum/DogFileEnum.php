<?php

namespace App\Enum;

class DogFileEnum extends Enum {

	public function __construct() {
		parent::__construct(__CLASS__);
	}

	/** @const registered */
	const BONITACNI_POSUDEK = 1;
}