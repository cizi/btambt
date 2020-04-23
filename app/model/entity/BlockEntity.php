<?php

namespace App\Model\Entity;

class BlockEntity {

	/** @var string */
	private $id;

	/** @var string */
	private $backgroundColor;

	/** @var string */
	private $color;

	/** @var string */
	private $width;

	/** @var BlockContentEntity */
	private $blockContent;

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getBackgroundColor() {
		return $this->backgroundColor;
	}

	/**
	 * @param string $backgroundColor
	 */
	public function setBackgroundColor($backgroundColor) {
		$this->backgroundColor = $backgroundColor;
	}

	/**
	 * @return string
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param string $color
	 */
	public function setColor($color) {
		$this->color = $color;
	}

	/**
	 * @return string
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param string $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * @return BlockContentEntity
	 */
	public function getBlockContent() {
		return $this->blockContent;
	}

	/**
	 * @param BlockContentEntity $blockContent
	 */
	public function setBlockContent($blockContent) {
		$this->blockContent = $blockContent;
	}

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->setId(!empty($data['id']) ? $data['id'] : null);
		$this->setBackgroundColor(!empty($data['background_color']) ? $data['background_color'] : null);
		$this->setColor(!empty($data['color']) ? $data['color'] : null);
		$this->setWidth(!empty($data['width']) ? $data['width'] : null);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->getId(),
			'background_color' => $this->getBackgroundColor(),
			'color' => $this->getColor(),
			'width' => $this->getWidth()
		];
	}


}