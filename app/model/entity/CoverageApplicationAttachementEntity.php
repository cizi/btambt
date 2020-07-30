<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class CoverageApplicationAttachementEntity {

	/** @var int */
	private $ID;

	/** @var int */
    private $kID;
    
    /** @var String */
    private $Cesta;

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
        $this->setKID(isset($data['kID']) ? $data['kID'] : null);
        $this->setCesta(isset($data['Cesta']) ? $data['Cesta'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'ID'	=> $this->getID(),
			'kID'	=> $this->getKID(),
            'Cesta'	=> $this->getCesta()
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
     * Get the value of kID
     */ 
    public function getKID()
    {
        return $this->kID;
    }

    /**
     * Set the value of kID
     *
     * @return  self
     */ 
    public function setKID($kID)
    {
        $this->kID = $kID;

        return $this;
    }

    /**
     * Get the value of Cesta
     */ 
    public function getCesta()
    {
        return $this->Cesta;
    }

    /**
     * Set the value of Cesta
     *
     * @return  self
     */ 
    public function setCesta($Cesta)
    {
        $this->Cesta = $Cesta;

        return $this;
    }
}