<?php

namespace App\Enum;

class Enum {

	/** @var string  */
	private $class;

	/**
	 * @param string $class
	 */
	public function __construct($class) {
		$this->class = $class;
	}

	/**
	 * @return array
	 */
	public function arrayKeyValue() {
		$ref = new \ReflectionClass($this->class);
		return $ref->getConstants();
	}

	/**
	 * @return array
	 */
	public function arrayValueKey() {
		return array_flip($this->arrayKeyValue());
	}

	/**
	 * @return array
	 */
	public function translatedForSelect($withEmptyOption = false) {
		$data = $this->arrayValueKey();
		$result = [];

		if ($withEmptyOption) {
			$result[0] = "-";
		}
		foreach ($data as $value => $key) {
			$result[$value] = constant($key);
		}

		return $result;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getValueByKey($key) {
		$arr = $this->arrayKeyValue();
		return (isset($arr[$key]) ? $arr[$key] : "");
	}
}