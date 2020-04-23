<?php

namespace App\Model;

use App\Model\Entity\SliderPicEntity;

class SliderSettingRepository extends BaseRepository {

	/** @const for slider enabled/disabled */
	const KEY_SLIDER_ON = "SLIDER_ON";

	/** @const for slider width */
	const KEY_SLIDER_WIDTH = "SLIDER_WIDTH";

	/** @const for slider timing */
	const KEY_SLIDER_TIMING = "SLIDER_TIMING";

	/** @const for slider slideshow */
	const KEY_SLIDER_SLIDE_SHOW = "SLIDER_SLIDE_SHOW";

	/** @const for slider controls */
	const KEY_SLIDER_CONTROLS = "SLIDER_CONTROLS";

	/**
	 * @return array
	 */
	public function load() {
		$query = ["select * from slider_setting"];
		$result = $this->connection->query($query)->fetchAll();

		$ret = [];
		foreach($result as $line) {
			$ret[$line->id] = $line->value;
		}

		return $ret;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param string $lang
	 * @return \Dibi\Result|int
	 */
	public function save($key, $value) {
		$query = ["select * from slider_setting where id = %s", $key];
		if ($this->connection->query($query)->fetch()) { // update
			$query = ["update slider_setting set value = %s where id = %s", $value, $key];
		} else {	// insert
			$query = ["insert into slider_setting values (%s, %s)", $key, $value];
		}

		return $this->connection->query($query);
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getByKey($key) {
		$ret = "";
		$query = ["select * from slider_setting where id = %s", $key];
		$result = $this->connection->query($query)->fetch();
		if ($result) {
			$ret = $result->value;
		}

		return $ret;
	}
}