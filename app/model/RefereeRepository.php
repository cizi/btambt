<?php

namespace App\Model;

use App\Model\Entity\RefereeEntity;

class RefereeRepository extends BaseRepository {

	/** string znak pro nevybraného veterináø v selectu  */
	const NOT_SELECTED = "-";

	/**
	 * @return RefereeEntity
	 */
	public function findReferees() {
		$query = "select * from appdata_rozhodci";
		$result = $this->connection->query($query);

		$referees = [];
		foreach ($result->fetchAll() as $row) {
			$referee = new RefereeEntity();
			$referee->hydrate($row->toArray());
			$referees[] = $referee;
		}

		return $referees;
	}

	/**
	 * @return array
	 */
	public function findRefereesForSelect() {
		$query = "select * from appdata_rozhodci";
		$result = $this->connection->query($query);

		$referees[0] = self::NOT_SELECTED;
		foreach ($result->fetchAll() as $row) {
			$referee = new RefereeEntity();
			$referee->hydrate($row->toArray());
			$referees[$referee->getID()] = $referee->getTitulyPrefix() . " " . $referee->getJmeno() . " " . $referee->getPrijmeni() . " " . $referee->getTitulySuffix();
		}

		return $referees;
	}

	/**
	 * @param RefereeEntity $refereeEntity
	 */
	public function saveReferee(RefereeEntity $refereeEntity) {
		if ($refereeEntity->getID() == null) {
			$query = ["insert into appdata_rozhodci ", $refereeEntity->extract()];
		} else {
			$query = ["update appdata_rozhodci set ", $refereeEntity->extract(), "where ID=%i", $refereeEntity->getID()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return RefereeEntity
	 */
	public function getReferee($id) {
		$query = ["select * from appdata_rozhodci where ID = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$refereeEntity = new RefereeEntity();
			$refereeEntity->hydrate($row->toArray());
			return $refereeEntity;
		}
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function delete($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_rozhodci where ID = %i", $id ];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}
}