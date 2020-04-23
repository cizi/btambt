<?php

namespace App\Model;

use Nette;

class BaseRepository {

    use Nette\SmartObject;

	/** @var \Dibi\Connection */
	protected $connection;

	public function __construct(\Dibi\Connection $connection) {
		$this->connection = $connection;
	}
}