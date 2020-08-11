<?php

namespace App\Model\Entity;

class ExamEntity {

	/** @var int */
	private $ID;

	/** @var int */
	private $zID;

	/** @var int */
	private $pID;

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setZID(isset($data['zID']) ? $data['zID'] : null);
		$this->setPID(isset($data['pID']) ? $data['pID'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'zID' => $this->getZID(),
			'pID' => $this->getPID()
		];
	}

	/**
	 * Get the value of pID
	 */ 
	public function getPID()
	{
		return $this->pID;
	}

	/**
	 * Set the value of pID
	 *
	 * @return  self
	 */ 
	public function setPID($pID)
	{
		$this->pID = $pID;

		return $this;
	}

	/**
	 * Get the value of zID
	 */ 
	public function getZID()
	{
		return $this->zID;
	}

	/**
	 * Set the value of zID
	 *
	 * @return  self
	 */ 
	public function setZID($zID)
	{
		$this->zID = $zID;

		return $this;
	}

	/**
	 * Get the value of ID
	 */ 
	public function getID()
	{
		return $this->ID;
	}

	/**
	 * Set the value of ID
	 *
	 * @return  self
	 */ 
	public function setID($ID)
	{
		$this->ID = $ID;

		return $this;
	}
}