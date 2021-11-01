<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class CoverageApplicationEntity {

	/** @var int */
	private $ID;

	/** @var string */
    private $Datum;
    
    /** @var DateTime */
    private $DatumVytvoreni;

	/** @var int */
    private $oID1;
    
    /** @var int */
    private $oID2;
    
    /** @var int */
	private $oID3;

    /** @var string */
    private $Poznamka;

	/** @var int */
	private $mID;

    /** @var int */
	private $uID;

    /** @var  int */
	private $Plemeno;

	/** @var  string */
    private $CisloKL;
    
    /** @var int */
    private $Expresni;

    /** @var DateTime */
    private $Odeslano;

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
        $this->setDatum(isset($data['Datum']) ? $data['Datum'] : null);
        $this->setDatumVytvoreni(isset($data['DatumVytvoreni']) ? $data['DatumVytvoreni'] : null);
        $this->setOID1(isset($data['oID1']) ? $data['oID1'] : null);
        $this->setOID2(isset($data['oID2']) ? $data['oID2'] : null);
        $this->setOID3(isset($data['oID3']) ? $data['oID3'] : null);
        $this->setPoznamka(isset($data['Poznamka']) ? $data['Poznamka'] : null);
        $this->setMID(isset($data['mID']) ? $data['mID'] : null);
        $this->setUID(isset($data['uID']) ? $data['uID'] : null);
        $this->setCisloKL(isset($data['CisloKL']) ? $data['CisloKL'] : null);
        $this->setExpresni(isset($data['Expresni']) ? $data['Expresni'] : null);
        $this->setPlemeno(isset($data['Plemeno']) ? $data['Plemeno'] : null);
        $this->setOdeslano(isset($data['Odeslano']) ? $data['Odeslano'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID'	=> $this->getID(),
			'Datum'	=> $this->getDatum(),
            'oID1'	=> $this->getOID1(),
            'oID2'	=> $this->getOID2(),
            'oID3'	=> $this->getOID3(),
            'Poznamka'  => $this->getPoznamka(),
			'mID'	=> $this->getMID(),
            'Plemeno'	=> $this->getPlemeno(),
            'uID'	=> $this->getUID(),
            'CisloKL'	=> $this->getCisloKL(),
            'DatumVytvoreni'	=> $this->getDatumVytvoreni(),
            'Expresni'	=> ($this->isExpresni() ? 1 : 0),
            'Odeslano' => $this->getOdeslano(),
		];
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

	/**
	 * Get the value of Datum
	 */ 
	public function getDatum()
	{
		return $this->Datum;
	}

	/**
	 * Set the value of Datum
	 *
	 * @return  self
	 */ 
	public function setDatum($Datum)
	{
		$this->Datum = $Datum;

		return $this;
	}

    /**
     * Get the value of oID1
     */ 
    public function getOID1()
    {
        return $this->oID1;
    }

    /**
     * Set the value of oID1
     *
     * @return  self
     */ 
    public function setOID1($oID1)
    {
        $this->oID1 = $oID1;

        return $this;
    }

    /**
     * Get the value of oID2
     */ 
    public function getOID2()
    {
        return $this->oID2;
    }

    /**
     * Set the value of oID2
     *
     * @return  self
     */ 
    public function setOID2($oID2)
    {
        $this->oID2 = $oID2;

        return $this;
    }

	/**
	 * Get the value of oID3
	 */ 
	public function getOID3()
	{
		return $this->oID3;
	}

	/**
	 * Set the value of oID3
	 *
	 * @return  self
	 */ 
	public function setOID3($oID3)
	{
		$this->oID3 = $oID3;

		return $this;
	}

	/**
	 * Get the value of mID
	 */ 
	public function getMID()
	{
		return $this->mID;
	}

	/**
	 * Set the value of mID
	 *
	 * @return  self
	 */ 
	public function setMID($mID)
	{
		$this->mID = $mID;

		return $this;
	}

	/**
	 * Get the value of uID
	 */ 
	public function getUID()
	{
		return $this->uID;
	}

	/**
	 * Set the value of uID
	 *
	 * @return  self
	 */ 
	public function setUID($uID)
	{
		$this->uID = $uID;

		return $this;
	}

	/**
	 * Get the value of Plemeno
	 */ 
	public function getPlemeno()
	{
		return $this->Plemeno;
	}

	/**
	 * Set the value of Plemeno
	 *
	 * @return  self
	 */ 
	public function setPlemeno($Plemeno)
	{
		$this->Plemeno = $Plemeno;

		return $this;
	}

	/**
	 * Get the value of CisloKL
	 */ 
	public function getCisloKL()
	{
		return $this->CisloKL;
	}

	/**
	 * Set the value of CisloKL
	 *
	 * @return  self
	 */ 
	public function setCisloKL($CisloKL)
	{
		$this->CisloKL = $CisloKL;

		return $this;
	}

    /**
     * Get the value of DatumVytvoreni
     */ 
    public function getDatumVytvoreni()
    {
        return $this->DatumVytvoreni;
    }

    /**
     * Set the value of DatumVytvoreni
     *
     * @return  self
     */ 
    public function setDatumVytvoreni($DatumVytvoreni)
    {
        $this->DatumVytvoreni = $DatumVytvoreni;

        return $this;
    }

    /**
     * Get the value of Expresni
     */ 
    public function isExpresni()
    {
        return ($this->Expresni == 1);
    }

    /**
     * Set the value of Expresni
     *
     * @return  self
     */ 
    public function setExpresni($Expresni)
    {
        $this->Expresni = $Expresni;

        return $this;
    }

    public function getOdeslano(): ?DateTime
    {
        return $this->Odeslano;
    }

    public function setOdeslano(?DateTime $Odeslano)
    {
        $this->Odeslano = $Odeslano;
    }

    public function getPoznamka(): ?string
    {
        return $this->Poznamka;
    }

    public function setPoznamka(?string $Poznamka): void
    {
        $this->Poznamka = $Poznamka;
    }
}
