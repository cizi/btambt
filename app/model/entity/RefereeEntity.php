<?php

namespace App\Model\Entity;

class RefereeEntity {

	/** @const pro oddělovač rozhodčích ve výstavě */
	const REFEREE_SHOW_DELIMITER = "~";

	/** @var  int */
	private $ID;

	/** @var string */
	private $Jmeno;

	/** @var string */
	private $Prijmeni;

	/** @var string */
	private $TitulyPrefix;

	/** @var string */
	private $TitulySuffix;

	/** @var string */
	private $Ulice;

	/** @var string */
	private $Mesto;

	/** @var string */
	private $PSC;

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
	 * @return string
	 */
	public function getJmeno() {
		return $this->Jmeno;
	}

	/**
	 * @param string $Jmeno
	 */
	public function setJmeno($Jmeno) {
		$this->Jmeno = $Jmeno;
	}

	/**
	 * @return string
	 */
	public function getPrijmeni() {
		return $this->Prijmeni;
	}

	/**
	 * @param string $Prijmeni
	 */
	public function setPrijmeni($Prijmeni) {
		$this->Prijmeni = $Prijmeni;
	}

	/**
	 * @return string
	 */
	public function getTitulyPrefix() {
		return $this->TitulyPrefix;
	}

	/**
	 * @param string $TitulyPrefix
	 */
	public function setTitulyPrefix($TitulyPrefix) {
		$this->TitulyPrefix = $TitulyPrefix;
	}

	/**
	 * @return string
	 */
	public function getTitulySuffix() {
		return $this->TitulySuffix;
	}

	/**
	 * @param string $TitulySuffix
	 */
	public function setTitulySuffix($TitulySuffix) {
		$this->TitulySuffix = $TitulySuffix;
	}

	/**
	 * @return string
	 */
	public function getUlice() {
		return $this->Ulice;
	}

	/**
	 * @param string $Ulice
	 */
	public function setUlice($Ulice) {
		$this->Ulice = $Ulice;
	}

	/**
	 * @return string
	 */
	public function getMesto() {
		return $this->Mesto;
	}

	/**
	 * @param string $Mesto
	 */
	public function setMesto($Mesto) {
		$this->Mesto = $Mesto;
	}

	/**
	 * @return string
	 */
	public function getPSC() {
		return $this->PSC;
	}

	/**
	 * @param string $PSC
	 */
	public function setPSC($PSC) {
		$this->PSC = $PSC;
	}

	public function getCeleJmeno() {
		return trim($this->getTitulyPrefix() . " " . $this->getJmeno() . " " . $this->getPrijmeni() . " " . $this->getTitulySuffix());
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setJmeno(isset($data['Jmeno']) ? $data['Jmeno'] : null);
		$this->setPrijmeni(isset($data['Prijmeni']) ? $data['Prijmeni'] : null);
		$this->setTitulyPrefix(isset($data['TitulyPrefix']) ? $data['TitulyPrefix'] : null);
		$this->setTitulySuffix(isset($data['TitulySuffix']) ? $data['TitulySuffix'] : null);
		$this->setUlice(isset($data['Ulice']) ? $data['Ulice'] : null);
		$this->setMesto(isset($data['Mesto']) ? $data['Mesto'] : null);
		$this->setPSC(isset($data['PSC']) ? $data['PSC'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'Jmeno' => $this->getJmeno(),
			'Prijmeni' => $this->getPrijmeni(),
			'TitulyPrefix' => $this->getTitulyPrefix(),
			'TitulySuffix' => $this->getTitulySuffix(),
			'Ulice' => $this->getUlice(),
			'Mesto' => $this->getMesto(),
			'PSC' => $this->getPSC(),
		];
	}
}