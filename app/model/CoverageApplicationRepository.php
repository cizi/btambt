<?php

namespace App\Model;

use App\Model\Entity\CoverageApplicationEntity;
use App\Model\Entity\CoverageApplicationAttachementEntity;
use Dibi\Connection;

class CoverageApplicationRepository extends BaseRepository {

    /** DogRepository $dogRepository */
    private $dogRepository;

    /** Connection $connection, */
    protected $connection;

    /**
     * @param DogRepository $dogRepository
     * @param Connection $connection
     */
    public function __construct(DogRepository $dogRepository, Connection $connection) {
        $this->dogRepository = $dogRepository;
        $this->connection = $connection;
    }

	/**
	 * @return CoverageApplicationEntity[]
	 */
	public function findCoverageApplications(array $filter = null) {
		if ($filter == null && empty($filter)) {
			$query = "select * from appdata_krycilist order by ID desc";
		} else {
			if (isset($filter["Datum"])) {
                $year = $filter["Datum"];
                unset($filter["Datum"]);
				$query = ["select * from appdata_krycilist where %and and YEAR(Datum) = %s order by ID desc", $filter, $year];
			} else {
                $query = ["select * from appdata_krycilist where %and order by ID desc", $filter];
            }
        }
		$result = $this->connection->query($query);

		$applications = [];
		foreach ($result->fetchAll() as $row) {
			$application = new CoverageApplicationEntity();
			$application->hydrate($row->toArray());
            $applications[] = $application;
		}

		return $applications;
    }

    /**
	 * @return string[]
	 */
	public function findCoverageYearsForSelect() {
        $query = "SELECT distinct YEAR(Datum) as Datum FROM `appdata_krycilist` WHERE Datum IS NOT NULL order by Datum asc";
        $result = $this->connection->query($query);
        $years = ["0" => EnumerationRepository::NOT_SELECTED];

        return $years + $result->fetchPairs("Datum", "Datum");
    }

    /**
	 * @return string[]
	 */
	public function findCoverageFemalesForSelect() {
        $query = "SELECT distinct mID FROM `appdata_krycilist`";
        $result = $this->connection->query($query);

        $females = ["0" => EnumerationRepository::NOT_SELECTED];
        foreach ($result->fetchAll() as $row) {
			$female = $this->dogRepository->getDog($row['mID']);
            $females[$row['mID']] = $female->getCeleJmeno();
        }

        return $females;
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
        try {
            $this->connection->begin();

            if ($CoverageApplicationEntity->getID() == null) {  // nový záznam
                $query = ["insert into appdata_krycilist ", $CoverageApplicationEntity->extract()];
                $this->connection->query($query);
                $CoverageApplicationEntity->setID($this->connection->getInsertId());
            } else {    // aktualizace
                $query = ["update appdata_krycilist set ", $CoverageApplicationEntity->extract(), "where ID=%i", $CoverageApplicationEntity->getID()];
                $this->connection->query($query);
            }
            if (!empty($attachements)) {    // soubory
                foreach($attachements as $attachement) {
                    $attachement->setKID($CoverageApplicationEntity->getID());
                    $query = ["insert into appdata_krycilist_prilohy ", $attachement->extract()];
                    $this->connection->query($query);
                }
            }
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
            $return = false;
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

                $query = ["delete from appdata_krycilist_prilohy where kID = %i", $id];
                $this->connection->query($query);

                $query = ["delete from appdata_krycilist where ID = %i", $id];
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
	 * @param int $id
	 * @return bool
	 */
	public function deleteAttachment($id) {
		$return = false;
		if (!empty($id)) {
            try {
                $query = ["delete from appdata_krycilist_prilohy where ID = %i", $id];
                $this->connection->query($query);
                $return = true;
            } catch (Exception $e) {
                $return = false;
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