<?php

namespace App\Model\Entity;

class EnumerationEntity {

	/** @var  int */
	private $id;

	/** @var  int */
	private $enumHeaderId;

	/** @var  string */
	private $lang;

	/** @var  string  */
	private $description;

	/** @var  EnumerationItemEntity[] */
	private $items;

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
	public function getEnumHeaderId() {
		return $this->enumHeaderId;
	}

	/**
	 * @param int $enum_header_id
	 */
	public function setEnumHeaderId($enum_header_id) {
		$this->enumHeaderId = $enum_header_id;
	}

	/**
	 * @return string
	 */
	public function getLang() {
		return $this->lang;
	}

	/**
	 * @param string $lang
	 */
	public function setLang($lang) {
		$this->lang = $lang;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return EnumerationItemEntity[]
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @param EnumerationItemEntity[] $items
	 */
	public function setItems($items) {
		$this->items = $items;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->id,
			'enum_header_id' => $this->enumHeaderId,
			'lang' => $this->lang,
			'description' => $this->description
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->id = (isset($data['id']) ? $data['id'] : null);
		$this->enumHeaderId = $data['enum_header_id'];
		$this->lang = $data['lang'];
		$this->description = $data['description'];
	}
}