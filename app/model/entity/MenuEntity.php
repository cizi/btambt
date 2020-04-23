<?php

namespace App\Model\Entity;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Link;

class MenuEntity {

	/** @var int */
	private $id;

	/** @var string */
	private $lang;

	/** @var string */
	private $link;

	/** @var string */
	private $title;
	/** @var string */
	private $alt;

	/** @var int */
	private $level;

	/**@var int */
	private $order;

	/** @var int */
	private $submenu;

	/** @var bool */
	private $hasSubItems;

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
	public function getLink() {
		return $this->link;
	}

	/**
	 * @param string $link
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getAlt() {
		return $this->alt;
	}

	/**
	 * @param string $alt
	 */
	public function setAlt($alt) {
		$this->alt = $alt;
	}

	/**
	 * @return int
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @param int $level
	 */
	public function setLevel($level) {
		$this->level = $level;
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
	 * @return int
	 */
	public function getSubmenu() {
		return $this->submenu;
	}

	/**
	 * @param int $submenu
	 */
	public function setSubmenu($submenu) {
		$this->submenu = $submenu;
	}

	/**
	 * @return boolean
	 */
	public function hasSubItems() {
		return $this->hasSubItems;
	}

	/**
	 * @param boolean $hasSubItems
	 */
	public function setHasSubItems($hasSubItems) {
		$this->hasSubItems = $hasSubItems;
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->id = (isset($data['id']) ? $data['id'] : null);
		$this->lang = (isset($data['lang']) ? $data['lang'] : null);
		$this->link = (isset($data['link']) ? $data['link'] : null);
		$this->title = (isset($data['title']) ? $data['title'] : null);
		$this->alt = (isset($data['alt']) ? $data['alt'] : null);
		$this->level = (isset($data['level']) ? $data['level'] : null);
		$this->order = (isset($data['order']) ? $data['order'] : null);
		$this->submenu = (isset($data['submenu']) ? $data['submenu'] : null);
		$this->hasSubItems = (isset($data['hasSubItems']) ? $data['hasSubItems'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->id,
			'lang' => $this->lang,
			'link' => $this->link,
			'title' => $this->title,
			'alt' => $this->alt,
			'level' => $this->level,
			'order' => $this->order,
			'submenu' => $this->submenu,
			'hasSubItems' => $this->hasSubItems
		];
	}
}