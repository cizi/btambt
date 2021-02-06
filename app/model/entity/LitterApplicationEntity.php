<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class LitterApplicationEntity {

	/** @var int */
	private $ID;

	/** @var DateTime */
	private $Datum;

	/** @var int */
	private $oID;

	/** @var int */
	private $mID;

	/** @var DateTime */
	private $DatumNarozeni;

	/** @var string */
	private $Data;

	/** @var string */
	private $Formular;

	/** @var int */
	private $Zavedeno;

	/** @var  int */
	private $Plemeno;

	/** @var  int */
	private $Klub;

	/** @var  int */
	private $MajitelFeny;

	/** @var bool */
	private $Skryto;

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
	 * @return DateTime
	 */
	public function getDatum() {
		return $this->Datum;
	}

	/**
	 * @param DateTime $Datum
	 */
	public function setDatum($Datum) {
		$this->Datum = $Datum;
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
	 * @return DateTime
	 */
	public function getDatumNarozeni() {
		return $this->DatumNarozeni;
	}

	/**
	 * @param DateTime $DatumNarozeni
	 */
	public function setDatumNarozeni($DatumNarozeni) {
		$this->DatumNarozeni = $DatumNarozeni;
	}

	/**
	 * @return string
	 */
	public function getData() {
		return $this->Data;
	}

	/**
	 * Vrátí dekódovaná POST data formuláře
	 * @return array
	 */
	public function getDataDecoded() {
		return unserialize(gzinflate(base64_decode($this->getData())));
	}

	/**
	 * @param string $Data
	 */
	public function setData($Data) {
		$this->Data = $Data;
	}

	/**
	 * @return string
	 */
	public function getFormular() {
		return $this->Formular;
	}

	/**
	 * Vrátí dekódovaný obsah formuláře
	 * @return array
	 */
	public function getFormularDecoded() {
		return gzinflate(base64_decode($this->getFormular()));
	}

	/**
	 * @param string $Formular
	 */
	public function setFormular($Formular) {
		$this->Formular = $Formular;
	}

	/**
	 * @return int
	 */
	public function getZavedeno() {
		return $this->Zavedeno;
	}

	/**
	 * @param int $Zavedeno
	 */
	public function setZavedeno($Zavedeno) {
		$this->Zavedeno = $Zavedeno;
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
	public function getKlub() {
		return $this->Klub;
	}

	/**
	 * @param int $Klub
	 */
	public function setKlub($Klub) {
		$this->Klub = $Klub;
	}

	/**
	 * @return int
	 */
	public function getMajitelFeny() {
		return $this->MajitelFeny;
	}

	/**
	 * @param int $MajitelFeny
	 */
	public function setMajitelFeny($MajitelFeny) {
		$this->MajitelFeny = $MajitelFeny;
	}

    /**
     * @return bool
     */
    public function isSkryto(): bool
    {
        return $this->Skryto;
    }

    /**
     * @param bool $Skryto
     */
    public function setSkryto(bool $Skryto): void
    {
        $this->Skryto = $Skryto;
    }

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setDatum(isset($data['Datum']) ? $data['Datum'] : null);
		$this->setoID(isset($data['oID']) ? $data['oID'] : null);
		$this->setmID(isset($data['mID']) ? $data['mID'] : null);
		$this->setDatumNarozeni(isset($data['DatumNarozeni']) ? $data['DatumNarozeni'] : null);
		$this->setData(isset($data['Data']) ? $data['Data'] : null);
		$this->setFormular(isset($data['Formular']) ? $data['Formular'] : null);
		$this->setZavedeno(isset($data['Zavedeno']) ? $data['Zavedeno'] : null);
		$this->setPlemeno(isset($data['Plemeno']) ? $data['Plemeno'] : null);
		$this->setKlub(isset($data['Klub']) ? $data['Klub'] : null);
		$this->setMajitelFeny(isset($data['MajitelFeny']) ? $data['MajitelFeny'] : null);
		$this->setSkryto(isset($data['Skryto']) ? $data['Skryto'] : 0);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID'	=> $this->getID(),
			'Datum'	=> $this->getDatum(),
			'oID'	=> $this->getoID(),
			'mID'	=> $this->getmID(),
			'DatumNarozeni'	=> $this->getDatumNarozeni(),
			'Data'	=> $this->getData(),
			'Formular'	=> $this->getFormular(),
			'Zavedeno'	=> $this->getZavedeno(),
			'Plemeno'	=> $this->getPlemeno(),
			'Klub'	=> $this->getKlub(),
			'MajitelFeny'	=> $this->getMajitelFeny(),
            'Skryto' => $this->isSkryto()
		];
	}
}
