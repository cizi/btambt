<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class AwaitingChangesEntity {

	/** @var  int */
	private $ID;

	/** @var  int */
	private $pID;

	/** @var int */
	private $uID;

	/** @var DateTime */
	private $datimVlozeno;

	/** @var  string */
	private $aktualniHodnota;

	/** @var string */
	private $pozadovanaHodnota;

	/** @var int */
	private $uIDKdoSchvalil;

	/** @var DateTime */
	private $datimZpracovani;

	/** @var int */
	private $stav;

	/** @var string */
	private $tabulka;

	/** @var string */
	private $sloupec;

	/** @var int */
	private $cID;

	/**
	 * @return int
	 */
	public function getZID() {
		return $this->zID;
	}

	/**
	 * @param int $zID
	 */
	public function setZID($zID) {
		$this->zID = $zID;
	}

	/** @var  int */
	private $zID;

	/**
	 * @return int
	 */
	public function getCID() {
		return $this->cID;
	}

	/**
	 * @param int $cID
	 */
	public function setCID($cID) {
		$this->cID = $cID;
	}

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
	public function getDatimVlozeno() {
		return $this->datimVlozeno;
	}

	/**
	 * @param DateTime $datimVlozeno
	 */
	public function setDatimVlozeno($datimVlozeno) {
		$this->datimVlozeno = $datimVlozeno;
	}

	/**
	 * @return string
	 */
	public function getAktualniHodnota() {
		return $this->aktualniHodnota;
	}

	/**
	 * @param string $aktualniHodnota
	 */
	public function setAktualniHodnota($aktualniHodnota) {
		$this->aktualniHodnota = $aktualniHodnota;
	}

	/**
	 * @return string
	 */
	public function getPozadovanaHodnota() {
		return $this->pozadovanaHodnota;
	}

	/**
	 * @param string $pozadovanaHodnota
	 */
	public function setPozadovanaHodnota($pozadovanaHodnota) {
		$this->pozadovanaHodnota = $pozadovanaHodnota;
	}

	/**
	 * @return int
	 */
	public function getUIDKdoSchvalil() {
		return $this->uIDKdoSchvalil;
	}

	/**
	 * @param int $uIDKdoSchvalil
	 */
	public function setUIDKdoSchvalil($uIDKdoSchvalil) {
		$this->uIDKdoSchvalil = $uIDKdoSchvalil;
	}

	/**
	 * @return DateTime
	 */
	public function getDatimZpracovani() {
		return $this->datimZpracovani;
	}

	/**
	 * @param DateTime $datimZpracovani
	 */
	public function setDatimZpracovani($datimZpracovani) {
		$this->datimZpracovani = $datimZpracovani;
	}

	/**
	 * @return int
	 */
	public function getStav() {
		return $this->stav;
	}

	/**
	 * @param int $stav
	 */
	public function setStav($stav) {
		$this->stav = $stav;
	}

	/**
	 * @return string
	 */
	public function getTabulka() {
		return $this->tabulka;
	}

	/**
	 * @param string $tabulka
	 */
	public function setTabulka($tabulka) {
		$this->tabulka = $tabulka;
	}

	/**
	 * @return string
	 */
	public function getSloupec() {
		return $this->sloupec;
	}

	/**
	 * @param string $sloupec
	 */
	public function setSloupec($sloupec) {
		$this->sloupec = $sloupec;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'pID' => $this->getpID(),
			'uID' => $this->getuID(),
			'datimVlozeno' => $this->getdatimVlozeno(),
			'aktualniHodnota' => $this->getaktualniHodnota(),
			'pozadovanaHodnota' => $this->getpozadovanaHodnota(),
			'uIDKdoSchvalil' => $this->getuIDKdoSchvalil(),
			'datimZpracovani' => $this->getdatimZpracovani(),
			'stav' => $this->getstav(),
			'tabulka' => $this->gettabulka(),
			'sloupec' => $this->getsloupec(),
			'cID' => $this->getCID(),
			'zID' => $this->getZID()
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setPID(isset($data['pID']) ? $data['pID'] : null);
		$this->setUID(isset($data['uID']) ? $data['uID'] : null);
		$this->setDatimVlozeno(isset($data['datimVlozeno']) ? $data['datimVlozeno'] : null);
		$this->setAktualniHodnota(isset($data['aktualniHodnota']) ? $data['aktualniHodnota'] : null);
		$this->setPozadovanaHodnota(isset($data['pozadovanaHodnota']) ? $data['pozadovanaHodnota'] : null);
		$this->setUIDKdoSchvalil(isset($data['uIDKdoSchvalil']) ? $data['uIDKdoSchvalil'] : null);
		$this->setDatimZpracovani(isset($data['datimZpracovani']) ? $data['datimZpracovani'] : null);
		$this->setStav(isset($data['stav']) ? $data['stav'] : null);
		$this->setTabulka(isset($data['tabulka']) ? $data['tabulka'] : null);
		$this->setSloupec(isset($data['sloupec']) ? $data['sloupec'] : null);
		$this->setCID(isset($data['cID']) ? $data['cID'] : null);
		$this->setZID(isset($data['zID']) ? $data['zID'] : null);
	}
}