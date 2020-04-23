<?php

namespace App\Model\Entity;

class PageContentEntity {

	/** @var int */
	private $id;

	/** @var int */
	private $menuItem_id;

	/** @var int */
	private $block_id;

	/** @var int */
	private $order;

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
	public function getMenuItemId() {
		return $this->menuItem_id;
	}

	/**
	 * @param int $menuItem_id
	 */
	public function setMenuItemId($menuItem_id) {
		$this->menuItem_id = $menuItem_id;
	}

	/**
	 * @return int
	 */
	public function getBlockId() {
		return $this->block_id;
	}

	/**
	 * @param int $block_id
	 */
	public function setBlockId($block_id) {
		$this->block_id = $block_id;
	}

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
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setId(isset($data['id']) ? $data['id'] : null);
		$this->setMenuItemId(isset($data['menu_item_id']) ? $data['menu_item_id'] : null);
		$this->setBlockId(isset($data['block_id']) ? $data['block_id'] : null);
		$this->setOrder(isset($data['order']) ? $data['order'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->getId(),
			'menu_item_id' => $this->getMenuItemId(),
			'block_id' => $this->getBlockId(),
			'order' => $this->getOrder()
		];
	}
}