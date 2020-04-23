<?php

namespace App\Model\Entity;

class DogFileEntity {

	/** @var int */
	private $id;

	/** @var int */
	private $pID;

	/** @var string */
	private $cesta;

	/** @var int */
	private $typ;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
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
	 * @return string
	 */
	public function getCesta() {
		return $this->cesta;
	}

	/**
	 * @param string $cesta
	 */
	public function setCesta($cesta) {
		$this->cesta = $cesta;
	}

	/**
	 * @return boolean
	 */
	public function getTyp() {
		return $this->typ;
	}

	/**
	 * @param boolean $vychozi
	 */
	public function setTyp($typ) {
		$this->typ = $typ;
	}

	/**
	 * @return string original filename
	 */
	public function getNazevSouboru() {
		$ret = "";
		if (($this->getCesta() != null) && ($this->getCesta() != "")) {
			$ret = substr($this->getCesta(), strrpos($this->getCesta(), "/") + 17, strlen($this->getCesta()));
		}

		return $ret;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->getId(),
			'pID' => $this->getPID(),
			'cesta' => $this->getCesta(),
			'typ' => $this->getTyp()
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setId(isset($data['id'])? $data['id'] : null);
		$this->setPID(isset($data['pID'])? $data['pID'] : null);
		$this->setCesta(isset($data['cesta'])? $data['cesta'] : null);
		$this->setTyp(isset($data['typ'])? $data['typ'] : null);
	}
}