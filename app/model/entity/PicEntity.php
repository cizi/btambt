<?php

namespace App\Model\Entity;

class PicEntity {

	/** @var int  */
	private $id;

	/** @var string  */
	private $path;

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
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * @param array $data
	 */
	public function hydrate($data) {
		$this->id = (isset($data['id']) ? $data['id'] : null);
		$this->path = $data['path'];
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->id,
			'path' => $this->path
		];
	}

}