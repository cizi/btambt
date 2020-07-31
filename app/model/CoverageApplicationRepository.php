<?php

namespace App\Model;

use App\Model\Entity\CoverageApplicationEntity;
use App\Model\Entity\CoverageApplicationAttachementEntity;

class CoverageApplicationRepository extends BaseRepository {

	/**
	 * @return CoverageApplicationEntity[]
	 */
	public function findCoverageApplications(array $filter = null) {
		$chs = null;
		if ($filter == null && empty($filter)) {
			$query = "select * from appdata_krycilist order by ID desc";
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
			$query = ["select * from appdata_krycilist where %and order by ID desc", $filter];
		}
		$result = $this->connection->query($query);

		$applications = [];
		foreach ($result->fetchAll() as $row) {
			$application = new CoverageApplicationEntity();
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
	 * @return CoverageApplicationEntity
	 */
	public function getCoverageApplication($id) {
        $query = ["select * from appdata_krycilist where ID = %i", $id];
        $row = $this->connection->query($query)->fetch();
		if ($row) {
			$cae = new CoverageApplicationEntity();
			$cae->hydrate($row->toArray());
			return $cae;
		}
    }

    /**
	 * @return CoverageApplicationAttachementEntity[]
	 */
	public function findCoverageApplicationAttachments($id) {
        $query = ["select * from appdata_krycilist_prilohy where kID = %i", $id];
        $result = $this->connection->query($query);

		$applications = [];
		foreach ($result->fetchAll() as $row) {
			$application = new CoverageApplicationAttachementEntity();
            $application->hydrate($row->toArray());
            $applications[] = $application;
        }
        
        return $applications;
    }

	/**
	 * @param CoverageApplicationEntity $CoverageApplicationEntity
     * @param CoverageApplicationAttachementEntity[] $attachements
	 */
	public function save(CoverageApplicationEntity $CoverageApplicationEntity, array $attachements) {
		if ($CoverageApplicationEntity->getID() == null) {
            try {
                $this->connection->begin();

                $query = ["insert into appdata_krycilist ", $CoverageApplicationEntity->extract()];
                $this->connection->query($query);
                $CoverageApplicationEntity->setID($this->connection->getInsertId());
                if (!empty($attachements)) {
                    foreach($attachements as $attachement) {
                        $attachement->setKID($CoverageApplicationEntity->getID());
                        $query = ["insert into appdata_krycilist_prilohy ", $attachement->extract()];
                        $this->connection->query($query);
                    }
                }
            } catch (\Exception $e) {
				$this->connection->rollback();
				$return = false;
			}
            $this->connection->commit();
		} else {
			$query = ["update appdata_krycilist set ", $CoverageApplicationEntity->extract(), "where ID=%i", $CoverageApplicationEntity->getID()];
			$this->connection->query($query);
		}
	}

	/**
	 * @param int $id
	 * @return CoverageApplicationEntity
	 */
	public function getLitterApplication($id) {
		$query = ["select * from appdata_krycilist where ID = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$CoverageApplicationEntity = new CoverageApplicationEntity();
			$CoverageApplicationEntity->hydrate($row->toArray());
			return $CoverageApplicationEntity;
		}
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function delete($id) {
		$return = false;
		if (!empty($id)) {
            try {
                $this->connection->begin();

                $query = ["delete from appdata_krycilist where ID = %i", $id];
                $this->connection->query($query);

                $query = ["delete from appdata_krycilist_prilohy where kID = %i", $id];
                $this->connection->query($query);

                $this->connection->commit();
                $return = true;
            } catch (Exception $e) {
                $this->connection->rollback();
            }
		}

		return $return;
    }
    
    /**
	 * @return array - [uID]
	 */
	public function findUsersInApplications() {
		$users = [];
		$applications = $this->findCoverageApplications();
		foreach ($applications as $application) {
			if (isset($data['uID'])) {
				$users[$data['uID']] = "";
			}
		}

		return $users;
    }
    
    /**
	 * @param int $id
	 */
	public function findUsedUserInApplication($id) {
		$records = [];
		$applications = $this->findCoverageApplications();
		foreach ($applications as $application) {
			if (isset($data['uID']) && ($data['uID'] == $id)) {
				$records[$application->getID()] = $application;
			}
		}

		return $records;
	}
}