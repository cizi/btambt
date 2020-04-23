<?php

namespace App\Model\Entity;

class ShowDogEntity {

	/** @var  int */
	private $ID;

	/** @var  int */
	private $vID;

	/** @var  int */
	private $pID;

	/** @var  int */
	private $Trida;

	/** @var  int */
	private $Oceneni;

	/** @var  int */
	private $Poradi;

	/** @var  int */
	private $Titul;

	/** @var  string */
	private $TitulyDodatky;

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
	public function getOceneni() {
		return $this->Oceneni;
	}

	/**
	 * @param int $Oceneni
	 */
	public function setOceneni($Oceneni) {
		$this->Oceneni = $Oceneni;
	}

	/**
	 * @return int
	 */
	public function getPoradi() {
		return $this->Poradi;
	}

	/**
	 * @param int $Poradi
	 */
	public function setPoradi($Poradi) {
		$this->Poradi = $Poradi;
	}

	/**
	 * @return int
	 */
	public function getTitul() {
		return $this->Titul;
	}

	/**
	 * @param int $Titul
	 */
	public function setTitul($Titul) {
		$this->Titul = $Titul;
	}

	/**
	 * @return string
	 */
	public function getTitulyDodatky() {
		return $this->TitulyDodatky;
	}

	/**
	 * @param string $TitulyDodatky
	 */
	public function setTitulyDodatky($TitulyDodatky) {
		$this->TitulyDodatky = $TitulyDodatky;
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setvID(isset($data['vID']) ? $data['vID'] : null);
		$this->setpID(isset($data['pID']) ? $data['pID'] : null);
		$this->setTrida(isset($data['Trida']) ? $data['Trida'] : null);
		$this->setOceneni(isset($data['Oceneni']) ? $data['Oceneni'] : null);
		$this->setPoradi(isset($data['Poradi']) ? $data['Poradi'] : null);
		$this->setTitul(isset($data['Titul']) ? $data['Titul'] : null);
		$this->setTitulyDodatky(isset($data['TitulyDodatky']) ? $data['TitulyDodatky'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'vID' => $this->getvID(),
			'pID' => $this->getpID(),
			'Trida' => $this->getTrida(),
			'Oceneni' => $this->getOceneni(),
			'Poradi' => $this->getPoradi(),
			'Titul' => $this->getTitul(),
			'TitulyDodatky' => $this->getTitulyDodatky()
		];
	}

}