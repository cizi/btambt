<?php

namespace App\Model\Entity;

class SliderPicEntity {

	/** @var int */
	private $id;

	/** @var string */
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
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->id,
			'path' => $this->path
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->id = (isset($data['id']) ? $data['id'] : null);
		$this->path = (isset($data['path']) ? $data['path'] : null);
	}
}