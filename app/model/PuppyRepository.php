<?php

namespace App\Model;

use App\Model\Entity\PuppyEntity;

class PuppyRepository extends BaseRepository {

	/**
	 * @param null $uID
	 * @return PuppyEntity[]
	 */
	public function findPuppies() {
		$query = "select * from appdata_stenata order by `ID` desc";
		$result = $this->connection->query($query);

		$puppies = [];
		foreach ($result->fetchAll() as $row) {
			$puppy = new PuppyEntity();
			$puppy->hydrate($row->toArray());
			$puppies[] = $puppy;
		}

		return $puppies;
	}

	/**
	 * @param int $id
	 * @return PuppyEntity
	 */
	public function getPuppy($id) {
		$query = ["select * from appdata_stenata where ID = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$puppyEntity = new PuppyEntity();
			$puppyEntity->hydrate($row->toArray());
			return $puppyEntity;
		}
	}

	/**
	 * @param PuppyEntity $puppyEntity
	 */
	public function savePuppy(PuppyEntity $puppyEntity) {
		if ($puppyEntity->getID() == null) {
		    $puppyEntity->setID(null);
			$query = ["insert into appdata_stenata ", $puppyEntity->extract()];
		} else {
			$query = ["update appdata_stenata set ", $puppyEntity->extract(), "where ID=%i", $puppyEntity->getID()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function deletePuppy($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_stenata where ID = %i", $id];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}
	
}