<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class PuppyEntity {

	/** @var int */
	private $ID = null;

	/** @var int */
	private $Plemeno;

	/** @var int */
	private $mID;

	/** @var int */
	private $oID;

	/** @var int  */
	private $uID;

	/** @var DateTime */
	private $Termin;

	/** @var string */
	private $Podrobnosti;

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
	 * @return int
	 */
	public function getMID() {
		return $this->mID;
	}

	/**
	 * @param int $mID
	 */
	public function setMID($mID) {
		$this->mID = $mID;
	}

	/**
	 * @return int
	 */
	public function getOID() {
		return $this->oID;
	}

	/**
	 * @param int $oID
	 */
	public function setOID($oID) {
		$this->oID = $oID;
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
	 * @return DateTime
	 */
	public function getTermin() {
		return $this->Termin;
	}

	/**
	 * @param DateTime $Termin
	 */
	public function setTermin($Termin) {
		$this->Termin = $Termin;
	}

	/**
	 * @return string
	 */
	public function getPodrobnosti() {
		return $this->Podrobnosti;
	}

	/**
	 * @param string $Podrobnosti
	 */
	public function setPodrobnosti($Podrobnosti) {
		$this->Podrobnosti = $Podrobnosti;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'Plemeno' => $this->getPlemeno(),
			'mID' => $this->getmID(),
			'oID' => $this->getoID(),
			'uID' => $this->getUID(),
			'Termin' => $this->getTermin(),
			'Podrobnosti' => $this->getPodrobnosti()
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID((!empty($data['ID']) ? $data['ID'] : null));
		$this->setPlemeno((!empty($data['Plemeno']) ? $data['Plemeno'] : null));
		$this->setmID((!empty($data['mID']) ? $data['mID'] : null));
		$this->setoID((!empty($data['oID']) ? $data['oID'] : null));
		$this->setUID((!empty($data['uID']) ? $data['uID'] : null));
		$this->setTermin((!empty($data['Termin']) ? $data['Termin'] : null));
		$this->setPodrobnosti((!empty($data['Podrobnosti']) ? $data['Podrobnosti'] : null));
	}

}