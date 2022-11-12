<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class ShowEntity {

	/** @const form�t data */
	const MASKA_DATA = 'Y-m-d';

	/** @var  int */
	private $ID;

	/** @var  int */
	private $Typ;

	/** @var  DateTime */
	private $Datum;

	/** @var string */
	private $Nazev;

	/** @var  string */
	private $Misto;

	/** @var  bool */
	private $Hotovo;

	/** @var  int */
	private $Rozhodci;

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
	public function getTyp() {
		return $this->Typ;
	}

	/**
	 * @param int $Typ
	 */
	public function setTyp($Typ) {
		$this->Typ = $Typ;
	}

	/**
	 * @return DateTime
	 */
	public function getDatum() {
		return $this->Datum;
	}

	/**
	 * @param $Datum
	 */
	public function setDatum($Datum) {
		$this->Datum = $Datum;
	}

	/**
	 * @return string
	 */
	public function getNazev() {
		return $this->Nazev;
	}

	/**
	 * @param string $Nazev
	 */
	public function setNazev($Nazev) {
		$this->Nazev = $Nazev;
	}

	/**
	 * @return string
	 */
	public function getMisto() {
		return $this->Misto;
	}

	/**
	 * @param string $Misto
	 */
	public function setMisto($Misto) {
		$this->Misto = $Misto;
	}

	/**
	 * @return boolean
	 */
	public function isHotovo() {
		return $this->Hotovo;
	}

	/**
	 * @param boolean $Hotovo
	 */
	public function setHotovo($Hotovo) {
		$this->Hotovo = ($Hotovo != "" ? 1 : 0);
	}

	/**
	 * @return int
	 */
	public function getRozhodci() {
		return $this->Rozhodci;
	}

	/**
	 * @param int $Rozhodci
	 */
	public function setRozhodci($Rozhodci) {
		$this->Rozhodci = $Rozhodci;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'Typ' => $this->getTyp(),
			'Datum' => $this->getDatum(),
			'Nazev' => $this->getNazev(),
			'Misto' => $this->getMisto(),
			'Hotovo' => $this->isHotovo(),
			'Rozhodci' => $this->getRozhodci()
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setTyp(isset($data['Typ']) ? $data['Typ'] : null);
		$this->setNazev(isset($data['Nazev']) ? $data['Nazev'] : null);
		$this->setMisto(isset($data['Misto']) ? $data['Misto'] : null);
		$this->setHotovo(isset($data['Hotovo']) ? $data['Hotovo'] : false);
		$this->setRozhodci(isset($data['Rozhodci']) ? $data['Rozhodci'] : null);

		if (isset($data['Datum']) && ($data['Datum'] != NULL)) {
			if (($data['Datum'] instanceof DateTime) == false) {
				$data['Datum'] = DateTime::createFromFormat(self::MASKA_DATA, $data['Datum']);
			}
			$this->setDatum($data['Datum']);
		}
	}
}