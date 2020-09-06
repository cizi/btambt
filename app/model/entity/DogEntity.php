<?php

namespace App\Model\Entity;

use App\Enum\DogStateEnum;
use Dibi\DateTime;

class DogEntity {

	/** @const format data */
    const MASKA_DATA = 'Y-m-d';
    
    /** @const format data pro zobrazeni */
    const MASKA_DATA_ZOBRAZENI = "j.n.Y";

	/** @var int */
	public $ID;

	/** @var string */
	public $TitulyPredJmenem;

	/** @var string */
	public $TitulyZaJmenem;

	/** @var string */
	public $Jmeno;

	/** @var DateTime */
	public $DatNarozeni;

	/** @var DateTime */
	public $DatUmrti;

	/** @var string */
	public $UmrtiKomentar;

	/** @var int */
	public $Pohlavi;

	/** @var int */
	public $Plemeno;

	/** @var int */
	public $Barva;

	/** @var int */
	public $Srst;

	/** @var string */
	public $BarvaKomentar;

	/** @var string */
	public $CisloZapisu;

	/** @var string */
	public $PCisloZapisu;

	/** @var string */
	public $Cip;

	/** @var string */
	public $Tetovani;

	/** @var string */
	public $ZdravotniKomentar;

	/** @var int */
	public $Varlata;

	/** @var int */
	public $Skus;

	/** @var int */
	public $Zuby;

	/** @var string */
	public $ZubyKomentar;

	/** @var int */
	public $Chovnost;

	/** @var string */
	public $ChovnyKomentar;

	/** @var string */
	public $Posudek;

	/** @var int[] */
    public $Zkousky;
    
    /** @var string */
    public $ZkouskySlozene;

	/** @var string */
	public $TitulyKomentar;

	/** @var string */
	public $Oceneni;

	/** @var  string */
	public $Zavody;

	/** @var int */
	public $oID;

	/** @var int */
	public $mID;

	/** @var  string */
	public $Komentar;

	/** @var  DateTime */
	public $PosledniZmena;

	/** @var  float */
	public $Vyska;

	/** @var  float */
	public $Vaha;

	/** @var  string */
	public $Bonitace;

	/** @var  string */
	public $ImpFrom;

	/** @var int */
	public $ImpID;

	/** @var int */
	public $oIDupdate;

	/** @var int */
	public $mIDupdate;

	/** @var string */
    public $KontrolaVrhu;
    
    /** @var int */
    public $Zeme;

    /** @var int */
    public $SkrytPotomky;

    /** @var int */
    public $SkrytSourozence;

    /** @var int */
    public $SkrytCelouKartu;

    /** @var int */
    public $SkrytSvod;

	/** @var  int */
	private $stav = DogStateEnum::ACTIVE;

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
	public function getTitulyPredJmenem() {
		return $this->TitulyPredJmenem;
	}

	/**
	 * @param string $TitulyPredJmenem
	 */
	public function setTitulyPredJmenem($TitulyPredJmenem) {
		$this->TitulyPredJmenem = $TitulyPredJmenem;
	}

	/**
	 * @return string
	 */
	public function getTitulyZaJmenem() {
		return $this->TitulyZaJmenem;
	}

	/**
	 * @param string $TitulyZaJmenem
	 */
	public function setTitulyZaJmenem($TitulyZaJmenem) {
		$this->TitulyZaJmenem = $TitulyZaJmenem;
    }
    
    /**
     * @return string
     */
    public function getCeleJmeno() {
        return trim($this->getTitulyPredJmenem() . " " . $this->getJmeno() . " " . $this->getTitulyZaJmenem());
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
	 * @return DateTime
	 */
	public function getDatNarozeni() {
		return $this->DatNarozeni;
    }
    
    /**
	 * @return string
	 */
	public function getZeme() {
		return $this->Zeme;
	}

	/**
	 * @param string $Zeme
	 */
	public function setZeme($Zeme) {
		$this->Zeme = $Zeme;
	}

	/**
	 * @param $DatNarozeni
	 */
	public function setDatNarozeni($DatNarozeni) {
		$this->DatNarozeni = $DatNarozeni;
	}

	/**
	 * @return DateTime
	 */
	public function getDatUmrti() {
		return $this->DatUmrti;
	}

	/**
	 * @param $DatUmrti
	 */
	public function setDatUmrti($DatUmrti) {
		$this->DatUmrti = $DatUmrti;
	}

	/**
	 * @return bool
	 */
	public function isPesMrtvy() {
		return ($this->getDatUmrti() != null);
	}

	/**
	 * @return string
	 */
	public function getUmrtiKomentar() {
		return $this->UmrtiKomentar;
	}

	/**
	 * @param string $UmrtiKomentar
	 */
	public function setUmrtiKomentar($UmrtiKomentar) {
		$this->UmrtiKomentar = $UmrtiKomentar;
	}

	/**
	 * @return int
	 */
	public function getPohlavi() {
		return $this->Pohlavi;
	}

	/**
	 * @param int $Pohlavi
	 */
	public function setPohlavi($Pohlavi) {
		$this->Pohlavi = $Pohlavi;
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
	public function getBarva() {
		return $this->Barva;
	}

	/**
	 * @param int $Barva
	 */
	public function setBarva($Barva) {
		$this->Barva = $Barva;
	}

	/**
	 * @return int
	 */
	public function getSrst() {
		return $this->Srst;
	}

	/**
	 * @param int $Srst
	 */
	public function setSrst($Srst) {
		$this->Srst = $Srst;
	}

	/**
	 * @return string
	 */
	public function getBarvaKomentar() {
		return $this->BarvaKomentar;
	}

	/**
	 * @param string $BarvaKomentar
	 */
	public function setBarvaKomentar($BarvaKomentar) {
		$this->BarvaKomentar = $BarvaKomentar;
	}

	/**
	 * @return string
	 */
	public function getCisloZapisu() {
		return $this->CisloZapisu;
	}

	/**
	 * @param string $CisloZapisu
	 */
	public function setCisloZapisu($CisloZapisu) {
		$this->CisloZapisu = $CisloZapisu;
	}

	/**
	 * @return string
	 */
	public function getPCisloZapisu() {
		return $this->PCisloZapisu;
	}

	/**
	 * @param string $PCisloZapisu
	 */
	public function setPCisloZapisu($PCisloZapisu) {
		$this->PCisloZapisu = $PCisloZapisu;
	}

	/**
	 * @return string
	 */
	public function getCip() {
		return $this->Cip;
	}

	/**
	 * @param string $Cip
	 */
	public function setCip($Cip) {
		$this->Cip = $Cip;
	}

	/**
	 * @return string
	 */
	public function getTetovani() {
		return $this->Tetovani;
	}

	/**
	 * @param string $Tetovani
	 */
	public function setTetovani($Tetovani) {
		$this->Tetovani = $Tetovani;
	}

	/**
	 * @return string
	 */
	public function getZdravotniKomentar() {
		return $this->ZdravotniKomentar;
	}

	/**
	 * @param string $ZdravotniKomentar
	 */
	public function setZdravotniKomentar($ZdravotniKomentar) {
		$this->ZdravotniKomentar = $ZdravotniKomentar;
	}

	/**
	 * @return int
	 */
	public function getVarlata() {
		return $this->Varlata;
	}

	/**
	 * @param int $Varlata
	 */
	public function setVarlata($Varlata) {
		$this->Varlata = $Varlata;
	}

	/**
	 * @return int
	 */
	public function getSkus() {
		return $this->Skus;
	}

	/**
	 * @param int $Skus
	 */
	public function setSkus($Skus) {
		$this->Skus = $Skus;
	}

	/**
	 * @return string
	 */
	public function getZuby() {
		return $this->Zuby;
	}

	/**
	 * @param string $Zuby
	 */
	public function setZuby($Zuby) {
		$this->Zuby = $Zuby;
	}

	/**
	 * @return string
	 */
	public function getZubyKomentar() {
		return $this->ZubyKomentar;
	}

	/**
	 * @param string $ZubyKomentar
	 */
	public function setZubyKomentar($ZubyKomentar) {
		$this->ZubyKomentar = $ZubyKomentar;
	}

	/**
	 * @return int
	 */
	public function getChovnost() {
		return $this->Chovnost;
	}

	/**
	 * @param int $Chovnost
	 */
	public function setChovnost($Chovnost) {
		$this->Chovnost = $Chovnost;
	}

	/**
	 * @return string
	 */
	public function getChovnyKomentar() {
		return $this->ChovnyKomentar;
	}

	/**
	 * @param string $ChovnyKomentar
	 */
	public function setChovnyKomentar($ChovnyKomentar) {
		$this->ChovnyKomentar = $ChovnyKomentar;
	}

	/**
	 * @return string
	 */
	public function getPosudek() {
		return $this->Posudek;
	}

	/**
	 * @param string $Posudek
	 */
	public function setPosudek($Posudek) {
		$this->Posudek = $Posudek;
	}

	/**
	 * @return int[]
	 */
	public function getZkousky() {
		return $this->Zkousky;
	}

	/**
	 * @param int[] $Zkousky
	 */
	public function setZkousky($Zkousky) {
		$this->Zkousky = $Zkousky;
    }
    
    /**
	 * @return string
	 */
	public function getZkouskySlozene() {
		return $this->ZkouskySlozene;
	}

	/**
	 * @param string $ZkouskySlozene
	 */
	public function setZkouskySlozene($ZkouskySlozene) {
		$this->ZkouskySlozene = $ZkouskySlozene;
	}

	/**
	 * @return string
	 */
	public function getTitulyKomentar() {
		return $this->TitulyKomentar;
	}

	/**
	 * @param string $TitulyKomentar
	 */
	public function setTitulyKomentar($TitulyKomentar) {
		$this->TitulyKomentar = $TitulyKomentar;
	}

	/**
	 * @return string
	 */
	public function getOceneni() {
		return $this->Oceneni;
	}

	/**
	 * @param string $Oceneni
	 */
	public function setOceneni($Oceneni) {
		$this->Oceneni = $Oceneni;
	}

	/**
	 * @return string
	 */
	public function getZavody() {
		return $this->Zavody;
	}

	/**
	 * @param string $Zavody
	 */
	public function setZavody($Zavody) {
		$this->Zavody = $Zavody;
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
	public function getPosledniZmena() {
		return $this->PosledniZmena;
	}

	/**
	 * @param \Nette\Utils\DateTime $PosledniZmena
	 */
	public function setPosledniZmena($PosledniZmena) {
		$this->PosledniZmena = $PosledniZmena;
	}

	/**
	 * @return float
	 */
	public function getVyska() {
		return $this->Vyska;
	}

	/**
	 * @param float $Vyska
	 */
	public function setVyska($Vyska) {
		$this->Vyska = $Vyska;
	}

	/**
	 * @return float
	 */
	public function getVaha() {
		return $this->Vaha;
	}

	/**
	 * @param float $Vaha
	 */
	public function setVaha($Vaha) {
		$this->Vaha = $Vaha;
	}

	/**
	 * @return string
	 */
	public function getBonitace() {
		return $this->Bonitace;
	}

	/**
	 * @param string $Bonitace
	 */
	public function setBonitace($Bonitace) {
		$this->Bonitace = $Bonitace;
	}

	/**
	 * @return string
	 */
	public function getImpFrom() {
		return $this->ImpFrom;
	}

	/**
	 * @param string $ImpFrom
	 */
	public function setImpFrom($ImpFrom) {
		$this->ImpFrom = $ImpFrom;
	}

	/**
	 * @return int
	 */
	public function getImpID() {
		return $this->ImpID;
	}

	/**
	 * @param int $ImpID
	 */
	public function setImpID($ImpID) {
		$this->ImpID = $ImpID;
	}

	/**
	 * @return int
	 */
	public function getOIDupdate() {
		return $this->oIDupdate;
	}

	/**
	 * @param int $oIDupdate
	 */
	public function setOIDupdate($oIDupdate) {
		$this->oIDupdate = $oIDupdate;
	}

	/**
	 * @return int
	 */
	public function getMIDupdate() {
		return $this->mIDupdate;
	}

	/**
	 * @return string
	 */
	public function getKontrolaVrhu() {
		return $this->KontrolaVrhu;
	}

	/**
	 * @param string $KontrolaVrhu
	 */
	public function setKontrolaVrhu($KontrolaVrhu) {
		$this->KontrolaVrhu = $KontrolaVrhu;
	}

	/**
	 * @param int $mIDupdate
	 */
	public function setMIDupdate($mIDupdate) {
		$this->mIDupdate = $mIDupdate;
    }
    
    /**
     * @var int $skrytPotomky
     */
    public function setSkrytPotomky($skrytPotomky) {
        $this->SkrytPotomky = $skrytPotomky;
    }

    /**
     * @return bool
     */
    public function isSkrytPotomky() {
        return ($this->SkrytPotomky == 1);
    }

    /**
     * @var int $skrytSourozence
     */
    public function setSkrytSourozence($skrytSourozence) {
        $this->SkrytSourozence = $skrytSourozence;
    }

    /**
     * @return bool
     */
    public function isSkrytSourozence() {
        return ($this->SkrytSourozence == 1);
    }

    /**
     * @var int $skrytCelouKartu
     */
    public function setSkrytCelouKartu($skrytCelouKartu) {
        $this->SkrytCelouKartu = $skrytCelouKartu;
    }

    /**
     * @return bool
     */
    public function isSkrytCelouKartu() {
        return ($this->SkrytCelouKartu == 1);
    }

	public function hydrate(array $data) {
		$this->setID(isset($data['ID']) ? $data['ID'] : null);
		$this->setTitulyPredJmenem(isset($data['TitulyPredJmenem']) ? $data['TitulyPredJmenem'] : null);
		$this->setTitulyZaJmenem(isset($data['TitulyZaJmenem']) ? $data['TitulyZaJmenem'] : null);
        $this->setJmeno(isset($data['Jmeno']) ? $data['Jmeno'] : null);
        $this->setZeme(isset($data['Zeme']) && !empty($data['Zeme']) ? $data['Zeme'] : null);
		$this->setUmrtiKomentar(isset($data['UmrtiKomentar']) ? $data['UmrtiKomentar'] : null);
		$this->setPohlavi((isset($data['Pohlavi']) && ($data['Pohlavi'] != 0)) ? $data['Pohlavi'] : null);
		$this->setPlemeno((isset($data['Plemeno']) && ($data['Plemeno'] != 0)) ? $data['Plemeno'] : null);
		$this->setBarva((isset($data['Barva']) && ($data['Barva'] != 0))? $data['Barva'] : null);
		$this->setSrst((isset($data['Srst']) && ($data['Srst'] != 0))? $data['Srst'] : null);
		$this->setBarvaKomentar(isset($data['BarvaKomentar']) ? $data['BarvaKomentar'] : null);
		$this->setCisloZapisu(isset($data['CisloZapisu']) ? $data['CisloZapisu'] : null);
		$this->setPCisloZapisu(isset($data['PCisloZapisu']) ? $data['PCisloZapisu'] : null);
		$this->setCip(isset($data['Cip']) ? $data['Cip'] : null);
		$this->setTetovani(isset($data['Tetovani']) ? $data['Tetovani'] : null);
		$this->setZdravotniKomentar(isset($data['ZdravotniKomentar']) ? $data['ZdravotniKomentar'] : null);
		$this->setVarlata((isset($data['Varlata']) && ($data['Varlata'] != 0)) ? $data['Varlata'] : null);
		$this->setSkus((isset($data['Skus']) && ($data['Skus'] != 0)) ? $data['Skus'] : null);
		$this->setZuby(isset($data['Zuby']) ? $data['Zuby'] : null);
		$this->setZubyKomentar(isset($data['ZubyKomentar']) ? $data['ZubyKomentar'] : null);
		$this->setChovnost((isset($data['Chovnost']) && ($data['Chovnost'] != 0)) ? $data['Chovnost'] : null);
		$this->setChovnyKomentar(isset($data['ChovnyKomentar']) ? $data['ChovnyKomentar'] : null);
		$this->setPosudek(isset($data['Posudek']) ? $data['Posudek'] : null);
        $this->setZkousky(isset($data['Zkousky']) ? $data['Zkousky'] : null);
        $this->setZkouskySlozene(isset($data['ZkouskySlozene']) ? $data['ZkouskySlozene'] : null);
		$this->setTitulyKomentar(isset($data['TitulyKomentar']) ? $data['TitulyKomentar'] : null);
		$this->setOceneni(isset($data['Oceneni']) ? $data['Oceneni'] : null);
		$this->setZavody(isset($data['Zavody']) ? $data['Zavody'] : null);
		$this->setoID((isset($data['oID']) && ($data['oID'] != 0)) ? $data['oID'] : null);
		$this->setmID((isset($data['mID']) && ($data['mID'] != 0)) ? $data['mID'] : null);
		$this->setKomentar(isset($data['Komentar']) ? $data['Komentar'] : null);
		$this->setPosledniZmena(isset($data['PosledniZmena']) ? $data['PosledniZmena'] : null);
		$this->setVyska(!empty($data['Vyska']) ? $data['Vyska'] : null);
		$this->setVaha(isset($data['Vaha']) ? $data['Vaha'] : null);
		$this->setBonitace(isset($data['Bonitace']) ? $data['Bonitace'] : null);
		$this->setImpFrom(isset($data['ImpFrom']) ? $data['ImpFrom'] : null);
		$this->setImpID(isset($data['ImpID']) ? $data['ImpID'] : null);
		$this->setoIDupdate(isset($data['oIDupdate']) ? $data['oIDupdate'] : null);
		$this->setmIDupdate(isset($data['mIDupdate']) ? $data['mIDupdate'] : null);
		$this->setKontrolaVrhu(isset($data['KontrolaVrhu']) ? $data['KontrolaVrhu'] : null);
        $this->setStav(isset($data['Stav']) ? $data['Stav'] : DogStateEnum::ACTIVE);
        $this->setSkrytPotomky(!empty($data['SkrytPotomky']) ? 1 : 0);
        $this->setSkrytSourozence(!empty($data['SkrytSourozence']) ? 1 : 0);
        $this->setSkrytCelouKartu(!empty($data['SkrytCelouKartu']) ? 1 : 0);
        $this->setSkrytSvod(!empty($data['SkrytSvod']) ? 1 : 0);

		if (isset($data['DatNarozeni']) && ($data['DatNarozeni'] != NULL)) {
			if (($data['DatNarozeni'] instanceof DateTime) == false) {
				$data['DatNarozeni'] = DateTime::createFromFormat(self::MASKA_DATA, $data['DatNarozeni']);
			}
			$this->setDatNarozeni($data['DatNarozeni']);
		}
		if (isset($data['DatUmrti']) && ($data['DatUmrti'] != NULL)) {
			if (($data['DatUmrti'] instanceof DateTime) == false) {
				$data['DatUmrti'] = DateTime::createFromFormat(self::MASKA_DATA, $data['DatUmrti']);
			}
			$this->setDatUmrti($data['DatUmrti']);
		}
	}

	public function extract() {
		return [
			'ID' => $this->getID(),
			'TitulyPredJmenem' => $this->getTitulyPredJmenem(),
			'TitulyZaJmenem' => $this->getTitulyZaJmenem(),
            'Jmeno' => $this->getJmeno(),
            'Zeme' => $this->getZeme(),
			'DatNarozeni' => $this->getDatNarozeni(),
			'DatUmrti' => $this->getDatUmrti(),
			'UmrtiKomentar' => $this->getUmrtiKomentar(),
			'Pohlavi' => $this->getPohlavi(),
			'Plemeno' => $this->getPlemeno(),
			'Barva' => $this->getBarva(),
			'Srst' => $this->getSrst(),
			'BarvaKomentar' => $this->getBarvaKomentar(),
			'CisloZapisu' => $this->getCisloZapisu(),
			'PCisloZapisu' => $this->getPCisloZapisu(),
			'Cip' => $this->getCip(),
			'Tetovani' => $this->getTetovani(),
			'ZdravotniKomentar' => $this->getZdravotniKomentar(),
			'Varlata' => $this->getVarlata(),
			'Skus' => $this->getSkus(),
			'Zuby' => $this->getZuby(),
			'ZubyKomentar' => $this->getZubyKomentar(),
			'Chovnost' => $this->getChovnost(),
			'ChovnyKomentar' => $this->getChovnyKomentar(),
			'Posudek' => $this->getPosudek(),
            'Zkousky' => $this->getZkousky(),
            'ZkouskySlozene' => $this->getZkouskySlozene(),
			'TitulyKomentar' => $this->getTitulyKomentar(),
			'Oceneni' => $this->getOceneni(),
			'Zavody' => $this->getZavody(),
			'oID' => $this->getoID(),
			'mID' => $this->getmID(),
			'Komentar' => $this->getKomentar(),
			'PosledniZmena' => $this->getPosledniZmena(),
			'Vyska' => $this->getVyska(),
			'Vaha' => $this->getVaha(),
			'Bonitace' => $this->getBonitace(),
			'ImpFrom' => $this->getImpFrom(),
			'ImpID' => $this->getImpID(),
			'oIDupdate' => $this->getoIDupdate(),
			'mIDupdate' => $this->getmIDupdate(),
			'KontrolaVrhu' => $this->getKontrolaVrhu(),
            'Stav' => $this->getStav(),
            'SkrytPotomky' => $this->SkrytPotomky,
            'SkrytSourozence' => $this->SkrytSourozence,
            'SkrytCelouKartu' => $this->SkrytCelouKartu,
            'SkrytSvod' => $this->SkrytSvod,
		];
    }
    
    /**
     * Erase object properties for sibling creation
     */
    public function eraseForSibling() {
        $this->setID(null);
        $this->setTitulyPredJmenem("");
        $this->setTitulyZaJmenem("");
        $this->setJmeno("");
        $this->setOceneni("");
        $this->setPCisloZapisu("");
        $this->setSkus("");
        $this->setUmrtiKomentar("");
        $this->setVaha(0);
        $this->setVyska(0);
        $this->setZdravotniKomentar("");
        $this->setZkousky("");
        $this->setZkouskySlozene("");
        $this->setZuby("");
        $this->setZubyKomentar("");
        $this->setBarva(0);
        $this->setBarvaKomentar("");
        $this->setBonitace("");
        $this->setChovnost(0);
        $this->setChovnyKomentar("");
        $this->setCip("");
        $this->setCisloZapisu("");
        $this->setDatUmrti(null);
        $this->setImpFrom("");
        $this->setImpID(0);
        $this->setPosudek("");
        $this->setTitulyKomentar("");
        $this->setTetovani("");
        $this->SkrytCelouKartu(0);
        $this->SkrytSourozence(0);
        $this->SkrytSvod(0);
        $this->SkrytPotomky(0);
    }

    /**
     * Get the value of SkrytSvod
     */ 
    public function isSkrytSvod()
    {
        return ($this->SkrytSvod == 1);
    }

    /**
     * Set the value of SkrytSvod
     *
     * @return  self
     */ 
    public function setSkrytSvod($SkrytSvod)
    {
        $this->SkrytSvod = $SkrytSvod;

        return $this;
    }
}