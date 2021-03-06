<?php

namespace App\Model;

use App\Controller\DogChangesComparatorController;
use App\Enum\DogChangeStateEnum;
use App\Enum\DogFileEnum;
use App\Model\Entity\AwaitingChangesEntity;
use App\Model\Entity\BreederEntity;
use App\Model\Entity\DogFileEntity;
use App\Model\Entity\DogHealthEntity;
use App\Model\Entity\DogOwnerEntity;
use App\Model\Entity\ExamEntity;
use App\Model\ExamRepository;
use Dibi\Connection;
use Dibi\DateTime;
use Nette\Security\User;

class AwaitingChangesRepository extends BaseRepository {

	/** @var DogRepository */
    private $dogRepository;
    
    /** @var ExamRepository */
    private $examRepository;

	/**
	 * AwaitingChangesRepository constructor.
	 * @param DogRepository $dogRepository
	 * @param Connection $connection
     * @param ExamRepository $examRepository
	 */
	public function __construct(DogRepository $dogRepository, Connection $connection, ExamRepository $examRepository) {
		parent::__construct($connection);
        $this->dogRepository = $dogRepository;
        $this->examRepository = $examRepository;
	}

	/**
	 * @return AwaitingChangesEntity[]
	 */
	public function findAwaitingChanges() {
			$query = ["select * from appdata_zmeny where stav= %i order by datimVlozeno asc", DogChangeStateEnum::INSERTED];
			$result = $this->connection->query($query);

			$awaitingChanges = [];
			foreach ($result->fetchAll() as $row) {
				$change = new AwaitingChangesEntity();
				$change->hydrate($row->toArray());
				$awaitingChanges[] = $change;
			}

			return $awaitingChanges;
	}

	/**
	 * @return AwaitingChangesEntity[]
	 */
	public function findProceededChanges() {
		$query = ["select * from appdata_zmeny where stav= %i order by datimVlozeno asc", DogChangeStateEnum::PROCEEDED];
		$result = $this->connection->query($query);

		$awaitingChanges = [];
		foreach ($result->fetchAll() as $row) {
			$change = new AwaitingChangesEntity();
			$change->hydrate($row->toArray());
			$awaitingChanges[] = $change;
		}

		return $awaitingChanges;
	}

	/**
	 * @return AwaitingChangesEntity[]
	 */
	public function findDeclinedChanges() {
		$query = ["select * from appdata_zmeny where stav= %i order by datimVlozeno asc", DogChangeStateEnum::DECLINED];
		$result = $this->connection->query($query);

		$awaitingChanges = [];
		foreach ($result->fetchAll() as $row) {
			$change = new AwaitingChangesEntity();
			$change->hydrate($row->toArray());
			$awaitingChanges[] = $change;
		}

		return $awaitingChanges;
	}

	/**
	 * @param int $id
	 * @return AwaitingChangesEntity
	 */
	public function getAwaitingChange($id) {
		$query = ["select * from appdata_zmeny where ID = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$awaitingEntity = new AwaitingChangesEntity();
			$awaitingEntity->hydrate($row->toArray());
			return $awaitingEntity;
		}
	}

	/**
	 * @param AwaitingChangesEntity $awaitChngEnt
	 * @param User $user
	 */
	public function proceedChange(AwaitingChangesEntity $awaitChngEnt, User $user) {
		$this->connection->begin();
		try {
            // zapíšu změny do tabulky
            $idColumn = ($awaitChngEnt->getTabulka() == "appdata_pes_soubory" ? "id" : "ID");    // srovnání názvu sloupců ID podle tabulky
            if ($awaitChngEnt->getTabulka() == DogChangesComparatorController::TBL_DOG_HEALTH_NAME) {    // u tabbulky zdravi jsou jiná pravidla
                if ($awaitChngEnt->getZID() == null) {    // tady by tedy mělo jít insert, ale raději se podívám zda pro psa s tímto ID a Typem už nějaký záznam neexistuje
                    $dogHealth = $this->dogRepository->getHealthEntityByDogAndType($awaitChngEnt->getCID(), $awaitChngEnt->getPID());
                    if ($dogHealth != null) {    // tedy záznam pro pro tohoto psa s typem zraví již máme
                        $awaitChngEnt->setZID($dogHealth->getID());    // a tedy záznamu pro změnu nastavím ID záznamu (z toho bude vyplývá budu dělat update, NE insert
                    }
                }
                if ($awaitChngEnt->getZID() != null) {    // jde tedy o update stávající záznamu
                    $query = ["update {$awaitChngEnt->getTabulka()} set `{$awaitChngEnt->getSloupec()}` = '{$awaitChngEnt->getPozadovanaHodnota()}' where `{$idColumn}` = {$awaitChngEnt->getZID()}"];
                    $this->connection->query($query);
                } else {
                    $dogHealthEntity = new DogHealthEntity();
                    $dogHealthEntity->setPID($awaitChngEnt->getPID());
                    $dogHealthEntity->setTyp($awaitChngEnt->getCID());
                    $dogHealthEntity->setVysledek("");
                    $dogHealthEntity->setKomentar("");
                    $dogHealthEntity->{"set" . $awaitChngEnt->getSloupec()}($awaitChngEnt->getPozadovanaHodnota());
                    $this->dogRepository->saveDogHealth($dogHealthEntity);
                }
            } else if ($awaitChngEnt->getTabulka() == DogChangesComparatorController::TBL_BREEDER_NAME) {    // chovatel
                $breeder = new BreederEntity();
                $breeder->setPID($awaitChngEnt->getPID());
                $breeder->setUID($awaitChngEnt->getPozadovanaHodnota());
                if ($awaitChngEnt->getZID() != null) {
                    $breeder->setID($awaitChngEnt->getZID());
                }
                $this->dogRepository->saveBreeder($breeder);
            } else if ($awaitChngEnt->getTabulka() == DogChangesComparatorController::TBL_OWNER_NAME) {    // majitel
                $owner = new DogOwnerEntity();
                $owner->setPID($awaitChngEnt->getPID());
                if ($awaitChngEnt->getZID() != null) {    // znaplatňuji již existující záznam
                    $owner->setID($awaitChngEnt->getZID());
                    $owner->setSoucasny(false);
                    $owner->setUID($awaitChngEnt->getAktualniHodnota());
                } else {    // zapisuji nový
                    $owner->setSoucasny(true);
                    $owner->setUID($awaitChngEnt->getPozadovanaHodnota());
                }
                $this->dogRepository->saveOwner($owner);
            } else if ($awaitChngEnt->getTabulka() == DogChangesComparatorController::TBL_EXAM_NAME) {    // zkoušky
                if ($awaitChngEnt->getPozadovanaHodnota() == "") {    // mazání položky
                    $this->examRepository->deleteByPidAndZid($awaitChngEnt->getPID(), $awaitChngEnt->getAktualniHodnota());
                } else {    // vkládání nové položky
                    $examEnt = new ExamEntity();
                    $examEnt->setZID($awaitChngEnt->getPozadovanaHodnota());
                    $examEnt->setPID($awaitChngEnt->getPID());
                    $this->examRepository->save($examEnt);
                }
            } else if ($awaitChngEnt->getTabulka() == DogChangesComparatorController::TBL_DOG_FILE) {
                $dogFile = new DogFileEntity();
                $dogFile->setCesta($awaitChngEnt->getPozadovanaHodnota());
                $dogFile->setTyp(DogFileEnum::BONITACNI_POSUDEK);
                $dogFile->setPID($awaitChngEnt->getPID());
                $this->dogRepository->saveDogFile($dogFile);
            } else {	// tohle pokryje jen psa
				$query = ["update {$awaitChngEnt->getTabulka()} set `{$awaitChngEnt->getSloupec()}` = '{$awaitChngEnt->getPozadovanaHodnota()}' where `{$idColumn}` = '{$awaitChngEnt->getPID()}'"];
				$this->connection->query($query);
			}

			// aktualizuji záznam změny
			$awaitChngEnt->setDatimZpracovani(new DateTime());
			$awaitChngEnt->setStav(DogChangeStateEnum::PROCEEDED);
			$awaitChngEnt->setUIDKdoSchvalil($user->getId());
			$query = ["update appdata_zmeny set ", $awaitChngEnt->extract(), "where ID=%i", $awaitChngEnt->getID()];
			$this->connection->query($query);
		} catch (\Exception $e) {
			$this->connection->rollback();
			throw $e;
		}
		$this->connection->commit();
	}

	/**
	 * @param AwaitingChangesEntity $awaitingChangesEntity
	 * @param User $user
	 */
	public function declineChange(AwaitingChangesEntity $awaitingChangesEntity, User $user) {
		$awaitingChangesEntity->setDatimZpracovani(new DateTime());
		$awaitingChangesEntity->setStav(DogChangeStateEnum::DECLINED);
		$awaitingChangesEntity->setUIDKdoSchvalil($user->getId());
		$query = ["update appdata_zmeny set ", $awaitingChangesEntity->extract(), "where ID=%i", $awaitingChangesEntity->getID()];
		$this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function deleteAwaitingChange($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_zmeny where ID = %i", $id];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}

	/**
	 * @param AwaitingChangesEntity[] $dogAwaitingChangesEntities
	 */
	public function writeChanges(array $dogAwaitingChangesEntities) {
		$this->connection->begin();
		try {
			foreach ($dogAwaitingChangesEntities as $dogChangeEnt) {
				$this->save($dogChangeEnt);
			}
		} catch (\Exception $e) {
			$this->connection->rollback();
		}
		$this->connection->commit();
    }
    
    /**
	 * @return AwaitingChangesEntity[]
	 */
	public function findChangesByUser($uID) {
		$query = ["select * from appdata_zmeny where uID= %i order by datimVlozeno asc", $uID];
		$result = $this->connection->query($query);

		$awaitingChanges = [];
		foreach ($result->fetchAll() as $row) {
			$change = new AwaitingChangesEntity();
			$change->hydrate($row->toArray());
			$awaitingChanges[] = $change;
		}

		return $awaitingChanges;
	}

	/**
	 * Zapíše poždavek do tabulky
	 * @param AwaitingChangesEntity $awaitingChangesEntity
	 */
	private function save(AwaitingChangesEntity $awaitingChangesEntity) {
		$query = ["insert into appdata_zmeny", $awaitingChangesEntity->extract()];
		$this->connection->query($query);
	}
}
