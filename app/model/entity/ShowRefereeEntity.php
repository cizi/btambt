<?php

namespace App\Model\Entity;

class ShowRefereeEntity {

	const NOT_SELECTED = "-";

	/** @var  int */
	private $ID;

	/** @var  int */
	private $vID;

	/** @var  int */
	private $rID;

	/** @var  int */
	private $Trida;

	/** @var  int */
	private $Plemeno;

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
	public function getVID() {
		return $this->vID;
	}

	/**
	 * @param int $vID
	 */
	public function setVID($vID) {
		$this->vID = $vID;
	}

	/**
	 * @return int
	 */
	public function getRID() {
		return $this->rID;
	}

	/**
	 * @param int $rID
	 */
	public function setRID($rID) {
		$this->rID = $rID;
	}

	/**
	 * @return int
	 */
	public function getTrida() {
		return $this->Trida;
	}

	/**
	 * @param int $Trida
	 */
	public function setTrida($Trida) {
		$this->Trida = $Trida;
	}

	/**
	 * @return int
	 */
	public function getPlemeno() {
		return $this->Plemeno;
	}

	/**
	 * @param int $Plemeno
	 */
	public function setPlemeno($Plemeno) {
		$this->Plemeno = $Plemeno;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'vID' => $this->getvID(),
			'rID' => $this->getrID(),
			'Trida' => $this->getTrida(),
			'Plemeno' => $this->getPlemeno()
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setvID(isset($data['vID']) ? $data['vID'] : null);
		$this->setrID(isset($data['rID']) ? $data['rID'] : null);
		$this->setTrida(isset($data['Trida']) ? $data['Trida'] : null);
		$this->setPlemeno(isset($data['Plemeno']) ? $data['Plemeno'] : null);
	}
}