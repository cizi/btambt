<?php

namespace App\Model;

use App\Model\Entity\SliderPicEntity;

class SliderPicRepository extends BaseRepository {

	const KEY_SLIDER_FILES_UPLOAD = "SLIDER_FILES_UPLOAD";

	/**
	 * @return SliderPicEntity
	 */
	public function findPics() {
		$query = "select * from slider_pic";
		$result = $this->connection->query($query);

		$return = [];
		foreach($result->fetchAll() as $pic) {
			$picEntity = new SliderPicEntity();
			$picEntity->hydrate($pic->toArray());
			$return[] = $picEntity;
		}

		return $return;
	}

	/**
	 * @param SliderPicEntity $sliderPicEntity
	 * @return \Dibi\Result|int
	 */
	public function save(SliderPicEntity $sliderPicEntity) {
		$query = ["insert into slider_pic", $sliderPicEntity->extract()];
		return $this->connection->query($query);
	}

	/**
	 * @param int $idPic
	 * @return \Dibi\Result|int
	 */
	public function delete($idPic) {
		$query = ["delete from slider_pic where id = %i", $idPic];
		return $this->connection->query($query);
	}
}