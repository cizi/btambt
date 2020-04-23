<?php

namespace App\Model\Entity;


class EnumerationItemEntity {

	/** @var  int */
	private $id;

	/** @var  string */
	private $enumHeaderId;

	/** @var  string */
	private $lang;

	/** @var string */
	private $item;

	/** @var int */
	private $order;

	/**
	 * @return int
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * @param int $order
	 */
	public function setOrder($order) {
		$this->order = $order;
	}

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
	public function getEnumHeaderId() {
		return $this->enumHeaderId;
	}

	/**
	 * @param string $enumHeaderId
	 */
	public function setEnumHeaderId($enumHeaderId) {
		$this->enumHeaderId = $enumHeaderId;
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
	public function getItem() {
		return $this->item;
	}

	/**
	 * @param string $item
	 */
	public function setItem($item) {
		$this->item = $item;
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->id,
			'enum_header_id' => $this->enumHeaderId,
			'lang' => $this->lang,
			'item' => $this->item,
			'order' => $this->order
		];
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->id = (isset($data['id']) ? $data['id'] : null);
		$this->enumHeaderId = $data['enum_header_id'];
		$this->lang = $data['lang'];
		$this->item = $data['item'];
		$this->order = $data['order'];
	}
}