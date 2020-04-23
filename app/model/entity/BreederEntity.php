<?php

namespace App\Model\Entity;

class BreederEntity {

	/** @var int */
	private $ID;

	/** @var int */
	private $uID;

	/** @var int */
	private $pID;

	/**
	 * @return int
	 */
	public function getID() {
		return $this->ID;
	}

	/**
	 * @param int $ID
	 */
	public function setID($ID) {
		$this->ID = $ID;
	}

	/**
	 * @return int
	 */
	public function getUID() {
		return $this->uID;
	}

	/**
	 * @param int $uID
	 */
	public function setUID($uID) {
		$this->uID = $uID;
	}

	/**
	 * @return int
	 */
	public function getPID() {
		return $this->pID;
	}

	/**
	 * @param int $pID
	 */
	public function setPID($pID) {
		$this->pID = $pID;
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setUID(isset($data['uID']) ? $data['uID'] : null);
		$this->setPID(isset($data['pID']) ? $data['pID'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' =>  $this->getID(),
			'uID' => $this->getUID(),
			'pID' => $this->getPID(),
		];
	}

}