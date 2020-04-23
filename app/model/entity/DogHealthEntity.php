<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class DogHealthEntity {

	/** @const formát data */
	const MASKA_DATA = 'Y-m-d';

	/** @var int */
	private $ID;

	/** @var int */
	private $pID;

	/** @var int */
	private $Typ;

	/** @var string */
	private $Vysledek;

	/** @var string */
	private $Komentar;

	/** @var DateTime */
	private $Datum;

	/** @var int */
	private $Veterinar;

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
	 * @return string
	 */
	public function getVysledek() {
		return $this->Vysledek;
	}

	/**
	 * @param string $Vysledek
	 */
	public function setVysledek($Vysledek) {
		$this->Vysledek = $Vysledek;
	}

	/**
	 * @return string
	 */
	public function getKomentar() {
		return $this->Komentar;
	}

	/**
	 * @param string $Komentar
	 */
	public function setKomentar($Komentar) {
		$this->Komentar = $Komentar;
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
	 * @return int
	 */
	public function getVeterinar() {
		return $this->Veterinar;
	}

	/**
	 * @param int $Veterinar
	 */
	public function setVeterinar($Veterinar) {
		$this->Veterinar = $Veterinar;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID' => $this->getID(),
			'pID' => $this->getPID(),
			'Typ' => $this->getTyp(),
			'Vysledek' => $this->getVysledek(),
			'Komentar' => $this->getKomentar(),
			'Datum' => $this->getDatum(),
			'Veterinar' => $this->getVeterinar(),
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setPID(isset($data['pID']) ? $data['pID'] : null);
		$this->setTyp(isset($data['Typ']) ? $data['Typ'] : null);
		$this->setVysledek(isset($data['Vysledek']) ? $data['Vysledek'] : null);
		$this->setKomentar(isset($data['Komentar']) ? $data['Komentar'] : null);
		$this->setVeterinar(isset($data['Veterinar']) ? $data['Veterinar'] : null);

		if (isset($data['Datum']) && ($data['Datum'] != NULL)) {
			if (($data['Datum'] instanceof DateTime) == false) {
				$data['Datum'] = DateTime::createFromFormat(self::MASKA_DATA, $data['Datum']);
			}
			$this->setDatum($data['Datum']);
		}
	}

}