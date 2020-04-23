<?php

namespace App\Model\Entity;

class DogPicEntity {

	/** @var int */
	private $id;

	/** @var int */
	private $pID;

	/** @var string */
	private $cesta;

	/** @var bool */
	private $vychozi;

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
	public function isVychozi() {
		return $this->vychozi;
	}

	/**
	 * @param boolean $vychozi
	 */
	public function setVychozi($vychozi) {
		$this->vychozi = $vychozi;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->getId(),
			'pID' => $this->getPID(),
			'cesta' => $this->getCesta(),
			'vychozi' => $this->isVychozi()
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setId(isset($data['id'])? $data['id'] : null);
		$this->setPID(isset($data['pID'])? $data['pID'] : null);
		$this->setCesta(isset($data['cesta'])? $data['cesta'] : null);
		$this->setVychozi(isset($data['vychozi'])? $data['vychozi'] : null);
	}
}