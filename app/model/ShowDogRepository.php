<?php

namespace App\Model;

use App\Model\Entity\DogEntity;
use App\Model\Entity\ShowDogEntity;
use App\Model\Entity\ShowEntity;
use Dibi\Connection;

class ShowDogRepository extends BaseRepository {

	/** @var DogRepository  */
	private $dogRepository;

	/**
	 * @param DogRepository $dogRepository
	 * @param Connection $connection
	 */
	public function __construct(DogRepository $dogRepository, Connection $connection) {
		$this->dogRepository = $dogRepository;
		parent::__construct($connection);
	}

	/**
	 * @param int $vID
	 * @param int $pID
	 * @return ShowDogEntity[]
	 */
	public function findTitlesByShowAndDog($vID, $pID) {
		$query = ["select * from appdata_vystava_pes where vID = %i and pID = %i", $vID, $pID];
		$result = $this->connection->query($query);

		$dogs = [];
		foreach ($result->fetchAll() as $row) {
			$dog = new ShowDogEntity();
			$dog->hydrate($row->toArray());
			$dogs[] = $dog;
		}

		return $dogs;
	}

	/**
	 * @param int $vID
	 * @param int $pID
	 * @return ShowDogEntity[]
	 */
	public function findTitlesByDog($pID) {
		$query = [
			"select *, avp.Oceneni as avpOceneni from appdata_vystava_pes as avp 
			left join appdata_vystava as av on avp.vID = av.ID
			where avp.pID = %i
			order by av.Datum asc"
			, $pID];
		$result = $this->connection->query($query);

		$showData = [];
		foreach ($result->fetchAll() as $row) {
			$data = [];
			$dogShowDog = new ShowDogEntity();
			$dogShowDog->hydrate($row->toArray());
			$dogShowDog->setOceneni($row['avpOceneni']);
			$data['showDog'] = $dogShowDog;

			$show = new ShowEntity();
			$show->hydrate($row->toArray());
			$data['show'] = $show;

			$showData[] = $data;
		}

		return $showData;
	}

	/**
	 * @param int $vID
	 * @return ShowDogEntity[]
	 */
	public function findDogsByShow($vID) {
		$query = ["select * from appdata_vystava_pes where vID = %i", $vID];
		$result = $this->connection->query($query);

		$dogs = [];
		foreach ($result->fetchAll() as $row) {
			$dog = new ShowDogEntity();
			$dog->hydrate($row->toArray());
			$dogs[] = $dog;
		}

		return $dogs;
	}

	/**
	 * @param int $vID
	 * @return ShowDogEntity[]
	 */
	public function findDogsByShowForDetail($vID) {
		$query = [
			"select av.* from appdata_vystava_pes as av
			left join appdata_pes as ap on av.pID = ap.ID
			where vID = %i group by av.pID order by ap.Plemeno, ap.Pohlavi, av.Trida",
			$vID];
		$result = $this->connection->query($query);

		$dogs = [];
		foreach ($result->fetchAll() as $row) {
			$dog = new ShowDogEntity();
			$dog->hydrate($row->toArray());
			$dogs[] = $dog;
		}

		return $dogs;
	}

 	/**
	 * @param ShowDogEntity $showDogEntity
	 */
	public function save(ShowDogEntity $showDogEntity) {
		if ($showDogEntity->getID() == null) {
			$query = ["insert into appdata_vystava_pes ", $showDogEntity->extract()];
		} else {
			$query = ["update appdata_vystava_pes set ", $showDogEntity->extract(), "where ID=%i", $showDogEntity->getID()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param ShowDogEntity $showDogEntity
	 * @return bool
	 */
	public function existsDogByShowClassValOrderTitle(ShowDogEntity $showDogEntity) {
		$query = ["select * from appdata_vystava_pes where vID = %i and pID = %i and Trida = %i and Oceneni = %i and Poradi = %i and Titul = %i",
			$showDogEntity->getVID(),
			$showDogEntity->getPID(),
			$showDogEntity->getTrida(),
			$showDogEntity->getOceneni(),
			$showDogEntity->getPoradi(),
			$showDogEntity->getTitul()
		];

		return (count($this->connection->query($query)->fetchAll()) ? true : false);
	}

	/**
	 * @param ShowDogEntity[] $dogs
	 */
	public function saveDogs(array $dogs) {
		try {
			$this->connection->begin();
			/** @var ShowDogEntity $dog */
			foreach ($dogs as $dog) {
				if ($this->existsDogByShowClassValOrderTitle($dog) == false) {
					$this->save($dog);
				}
			}
			$this->connection->commit();
		} catch (\Exception $e) {
			$this->connection->rollback();
			throw $e;
		}
	}

	/**
	 * @param int $vID
	 * @param int $rID
	 * @return bool
	 */
	public function deleteByVIDAndPID($vID, $pID) {
		$return = false;
		if (!empty($vID) && !empty($pID)) {
			$query = ["delete from appdata_vystava_pes where vID = %i and pID = %i", $vID, $pID];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function delete($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_vystava_pes where ID = %i", $id ];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}

	/**
	 * Migrace dat ze staré struktury
	 */
	public function migrateDogsFromOldStructure() {
		$result = $this->connection->query("select * from v2p");
		$showRepository = new ShowRepository($this->connection);
		try {
			$this->connection->begin();
			foreach ($result->fetchAll() as $row) {
				$showDogEntity = new ShowDogEntity();
				$showDogEntity->setTitulyDodatky($row['TitulyDodatky']);

				if ($showRepository->getShow($row['vID']) == null) {    // v DB chybí odkaz na výstavu = nemůžu zmigrovat
					continue;
				}
				$showDogEntity->setVID($row['vID']);

				if ($this->dogRepository->getDog($row['pID']) == null) { // pokud v DB chybí odkaz na psa, nemmůžu migrovat
					continue;
				}
				$showDogEntity->setPID($row['pID']);

				switch ($row['Trida']) {
					case "10":
						$showDogEntity->setTrida(102);
						break;
					case "20":
						$showDogEntity->setTrida(103);
						break;
					case "30":
						$showDogEntity->setTrida(104);
						break;
					case "40":
						$showDogEntity->setTrida(105);
						break;
					case "50":
						$showDogEntity->setTrida(106);
						break;
					case "60":
						$showDogEntity->setTrida(107);
						break;
					case "70":
						$showDogEntity->setTrida(108);
						break;
					case "80":
						$showDogEntity->setTrida(109);
						break;
					case "90":
						$showDogEntity->setTrida(110);
						break;
					case "100":
						$showDogEntity->setTrida(111);
						break;
					case "110":
						$showDogEntity->setTrida(112);
						break;
					case "120":
						$showDogEntity->setTrida(113);
						break;
				}

				switch ($row['Oceneni']) {
					case "10":
						$showDogEntity->setOceneni(114);
						break;
					case "20":
						$showDogEntity->setOceneni(115);
						break;
					case "30":
						$showDogEntity->setOceneni(116);
						break;
					case "40":
						$showDogEntity->setOceneni(117);
						break;
					case "50":
						$showDogEntity->setOceneni(118);
						break;
					case "60":
						$showDogEntity->setOceneni(119);
						break;
					case "70":
						$showDogEntity->setOceneni(120);
						break;
					case "80":
						$showDogEntity->setOceneni(121);
						break;
					case "90":
						$showDogEntity->setOceneni(122);
						break;
					case "100":
						$showDogEntity->setOceneni(123);
						break;
					case "110":
						$showDogEntity->setOceneni(124);
						break;
					case "120":
						$showDogEntity->setOceneni(125);
						break;
				}

				switch ($row['Poradi']) {
					case "-":
						$showDogEntity->setPoradi(126);
						break;
					case "1":
						$showDogEntity->setPoradi(127);
						break;
					case "2":
						$showDogEntity->setPoradi(128);
						break;
					case "3":
						$showDogEntity->setPoradi(129);
					break;
					case "4":
						$showDogEntity->setPoradi(130);
					break;
				}

				$tituly = [
					'OV' => 131,
					'KrV' => 132,
					'CAJC' => 133,
					'Zwml' => 134,
					'NejMP' => 135,
					'CAC' => 136,
					'resCAC' => 137,
					'CSK' => 138,
					'CACA' => 139,
					'resCACA' => 140,
					'CWC' => 141,
					'CACIB' => 142,
					'resCACIB' => 143,
					'NV' => 144,
					'KLV' => 145,
					'KLVM' => 146,
					'VSV' => 147,
					'VSVM' => 148,
					'NejP' => 149,
					'NejF' => 150,
					'NSwR' => 151,
					'NPwR' => 152,
					'NejV' => 153,
					'NejVP' => 154,
					'BOB' => 155,
					'BOS' => 156
				];
				$titulNevyplnen = true;
				foreach ($tituly as $stary => $novy) {
					if ($row[$stary] == 1) {
						$showDogEntity->setTitul($novy);
						$this->save($showDogEntity);
						$titulNevyplnen = false;
					}
				}
				if ($titulNevyplnen == true) {	// uložíme psa bez titulu
					$showDogEntity->setTitul(null);
					$this->save($showDogEntity);
				}
			}
			$this->connection->query("RENAME TABLE v2p to migrated_v2p");
			$this->connection->commit();
		} catch (\Exception $e) {
			$this->connection->rollback();
			throw $e;
		}
	}
}