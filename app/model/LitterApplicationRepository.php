<?php

namespace App\Model;

use App\Model\Entity\LitterApplicationEntity;

class LitterApplicationRepository extends BaseRepository {

	/**
	 * @return LitterApplicationEntity[]
	 */
	public function findLitterApplications(array $filter = null) {
		$chs = null;
		if ($filter == null && empty($filter)) {
			$query = "select * from appdata_prihlaska order by DatumNarozeni desc";
		} else {
			if (isset($filter["Zavedeno"])) {
				$filter["Zavedeno"] = $filter["Zavedeno"] - 1;
				if ($filter["Zavedeno"] == 2) {
					unset($filter["Zavedeno"]);
				}
			}
			if (isset($filter['chs']) && $filter['chs'] != "") {
				$chs = $filter['chs'];
				unset($filter['chs']);
			}
			$query = ["select * from appdata_prihlaska where %and order by DatumNarozeni desc", $filter];
		}
		$result = $this->connection->query($query);

		$applications = [];
		foreach ($result->fetchAll() as $row) {
			$application = new LitterApplicationEntity();
			$application->hydrate($row->toArray());
			if (empty($chs) == false) {
				$formData = $application->getDataDecoded();
				if((isset($formData['chs'])) && (trim($formData['chs']) == $chs)) {
					$applications[] = $application;
				}
			} else {
				$applications[] = $application;
			}
		}

		return $applications;
	}

	/**
	 * Vrátí pole všch CHS, které jsou dostupné v přihláškách vrhu
	 * @return array
	 */
	public function findChsInApplications() {
		$chs[0] = EnumerationRepository::NOT_SELECTED;
		$litterApplicationEntities = $this->findLitterApplications();
		foreach ($litterApplicationEntities as $litterApplicationEntity) {
			$formData = $litterApplicationEntity->getDataDecoded();
			if (isset($formData['chs']) && (isset($chs[$formData['chs']]) == false) && ($formData['chs'] != "")) {
				$chs[trim($formData['chs'])] = trim($formData['chs']);
			}
		}

		return $chs;
	}

	/**
	 * @param LitterApplicationEntity $litterApplicationEntity
	 */
	public function save(LitterApplicationEntity $litterApplicationEntity) {
		if ($litterApplicationEntity->getID() == null) {
			$query = ["insert into appdata_prihlaska ", $litterApplicationEntity->extract()];
			$this->connection->query($query);
			$litterApplicationEntity->setID($this->connection->getInsertId());
		} else {
			$query = ["update appdata_prihlaska set ", $litterApplicationEntity->extract(), "where ID=%i", $litterApplicationEntity->getID()];
			$this->connection->query($query);
		}
	}

	/**
	 * @param int $id
	 * @return LitterApplicationEntity
	 */
	public function getLitterApplication($id) {
		$query = ["select * from appdata_prihlaska where ID = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$litterApplicationEntity = new LitterApplicationEntity();
			$litterApplicationEntity->hydrate($row->toArray());
			return $litterApplicationEntity;
		}
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function delete($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_prihlaska where ID = %i", $id];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
    }
    
    /**
	 * @return array - [uID]
	 */
	public function findUsersInApplications() {
		$users = [];
		$applications = $this->findLitterApplications();
		foreach ($applications as $application) {
			$data = $application->getDataDecoded();
			if (isset($data['MajitelFeny'])) {
				$users[$data['MajitelFeny']] = "";
			}
		}

		return $users;
    }
    
    /**
	 * @param int $id
	 */
	public function findUsedUserInApplication($id) {
		$records = [];
		$applications = $this->findLitterApplications();
		foreach ($applications as $application) {
			$data = $application->getDataDecoded();
			if (isset($data['MajitelFeny']) && ($data['MajitelFeny'] == $id)) {
				$records[$application->getID()] = $application;
			}
		}

		return $records;
	}
}