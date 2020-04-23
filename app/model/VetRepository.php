<?php

namespace App\Model;

use App\Model\Entity\VetEntity;

class VetRepository extends BaseRepository {

	/** string znak pro nevybraného veterináø v selectu  */
	const NOT_SELECTED = "-";

	/**
	 * @return array
	 */
	public function findVets() {
		$query = "select * from appdata_veterinar";
		$result = $this->connection->query($query);

		$vets = [];
		foreach ($result->fetchAll() as $row) {
			$vet = new VetEntity();
			$vet->hydrate($row->toArray());
			$vets[] = $vet;
		}

		return $vets;
	}

	/**
	 * @return array
	 */
	public function findVetsForSelect() {
		$query = "select * from appdata_veterinar";
		$result = $this->connection->query($query);

		$vets[0] = self::NOT_SELECTED;
		foreach ($result->fetchAll() as $row) {
			$vet = new VetEntity();
			$vet->hydrate($row->toArray());
			$vets[$vet->getID()] = $vet->getTitulyPrefix() . " " . $vet->getJmeno() . " " . $vet->getPrijmeni() . " " . $vet->getTitulySuffix();
		}

		return $vets;
	}

	/**
	 * @param VetEntity $vetEntity
	 */
	public function saveVet(VetEntity $vetEntity) {
		if ($vetEntity->getID() == null) {
			$query = ["insert into appdata_veterinar ", $vetEntity->extract()];
		} else {
			$query = ["update appdata_veterinar set ", $vetEntity->extract(), "where ID=%i", $vetEntity->getID()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return VetEntity
	 */
	public function getVet($id) {
		$query = ["select * from appdata_veterinar where ID = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$vetEntity = new VetEntity();
			$vetEntity->hydrate($row->toArray());
			return $vetEntity;
		}
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function delete($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_veterinar where ID = %i", $id ];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}
}