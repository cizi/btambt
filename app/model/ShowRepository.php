<?php

namespace App\Model;

use App\Model\Entity\ShowEntity;

class ShowRepository extends BaseRepository {

	/**
	 * @return ShowEntity[]
	 */
	public function findShows() {
		$query = ["select * from appdata_vystava order by Datum desc"];
		$result = $this->connection->query($query);

		$shows = [];
		foreach ($result->fetchAll() as $row) {
			$show = new ShowEntity();
			$show->hydrate($row->toArray());
			$shows[] = $show;
		}

		return $shows;
	}

	/**
	 * @param int $id
	 * @return ShowEntity
	 */
	public function getShow($id) {
		$query = ["select * from appdata_vystava where ID = %i", $id];
		$result = $this->connection->query($query);

		$row = $result->fetch();
		if ($row) {
			$show = new ShowEntity();
			$show->hydrate($row->toArray());

			return $show;
		}
	}

	/**
	 * @param ShowEntity $showEntity
	 */
	public function save(ShowEntity $showEntity) {
		if ($showEntity->getRozhodci() == 0) {
			$showEntity->setRozhodci(null);
		}

		if ($showEntity->getID() == null) {
			$query = ["insert into appdata_vystava ", $showEntity->extract()];
		} else {
			$query = ["update appdata_vystava set ", $showEntity->extract(), "where ID=%i", $showEntity->getID()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function setShowDone($id) {
		$query = ["update appdata_vystava set Hotovo = 1 where ID = %i", $id];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function setShowUndone($id) {
		$query = ["update appdata_vystava set Hotovo = 0 where ID = %i", $id];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function delete($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_vystava where ID=%i", $id];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}

}