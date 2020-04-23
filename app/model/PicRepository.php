<?php

namespace App\Model;

use App\Model\Entity\PicEntity;

class PicRepository extends BaseRepository {

	/**
	 * @return PicEntity[]
	 */
	public function load() {
		$return = [];
		$query = "select * from shared_pic";
		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$footerPic = new PicEntity();
			$footerPic->hydrate($item->toArray());
			$return[] = $footerPic;
		}

		return $return;
	}

	/**
	 * @param PicEntity $picEntity
	 * @return \Dibi\Result|int
	 */
	public function save(PicEntity $picEntity) {
		$query = ["insert into shared_pic", $picEntity->extract()];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function delete($id) {
		$query = ["delete from shared_pic where id = %i", $id];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return PicEntity
	 */
	public function getById($id) {
		$query = ["select * from shared_pic where id = %i", $id];
		$result = $this->connection->query($query)->fetch();
		$footerPic = new PicEntity();
		if ($result) {
			$footerPic->hydrate($result->toArray());
		}

		return $footerPic;
	}

}