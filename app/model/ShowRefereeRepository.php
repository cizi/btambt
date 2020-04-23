<?php

namespace App\Model;

use App\Model\Entity\ShowEntity;
use App\Model\Entity\ShowRefereeEntity;

class ShowRefereeRepository extends BaseRepository {

	/**
	 * @param int $vID
	 * @return ShowRefereeEntity[]
	 */
	public function findRefereeByShow($vID) {
		$query = ["select * from appdata_vystava_rozhodci where vID = %i", $vID];
		$result = $this->connection->query($query);

		$referees = [];
		foreach ($result->fetchAll() as $row) {
			$referee = new ShowRefereeEntity();
			$referee->hydrate($row->toArray());
			$referees[] = $referee;
		}

		return $referees;
	}

	/**
	 * @param int $vID
	 * @return ShowRefereeEntity[]
	 */
	public function findRefereeByShowFrontEnd($vID) {
		$query = ["select ID, vID, rID, GROUP_CONCAT(Trida SEPARATOR ',') as Trida, Plemeno from appdata_vystava_rozhodci where vID = %i group by Plemeno", $vID];
		$result = $this->connection->query($query);

		$referees = [];
		foreach ($result->fetchAll() as $row) {
			$referee = new ShowRefereeEntity();
			$referee->hydrate($row->toArray());
			$referees[] = $referee;
		}

		return $referees;
	}

	/**
	 * @param ShowRefereeEntity $showRefereeEntity
	 */
	public function save(ShowRefereeEntity $showRefereeEntity) {
		if ($showRefereeEntity->getID() == null) {
			$query = ["insert into appdata_vystava_rozhodci ", $showRefereeEntity->extract()];
		} else {
			$query = ["update appdata_vystava_rozhodci set ", $showRefereeEntity->extract(), "where ID=%i", $showRefereeEntity->getID()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param ShowRefereeEntity $showRefereeEntity
	 * @return bool
	 */
	public function existsRefereeForShowClassBreed(ShowRefereeEntity $showRefereeEntity) {
		$query = ["select * from appdata_vystava_rozhodci where vID = %i and rID = %i and Trida = %i and Plemeno = %i",
			$showRefereeEntity->getVID(),
			$showRefereeEntity->getRID(),
			$showRefereeEntity->getTrida(),
			$showRefereeEntity->getPlemeno()
		];

		return (count($this->connection->query($query)->fetchAll()) ? true : false);
	}

	/**
	 * @param array $refereees
	 */
	public function saveReferees(array $refereees) {
		try {
			$this->connection->begin();
			/** @var ShowRefereeEntity $referee */
			foreach ($refereees as $referee) {
				if ($this->existsRefereeForShowClassBreed($referee) == false) {
					$this->save($referee);
				}
			}
			$this->connection->commit();
		} catch (\Exception $e) {
			$this->connection->rollback();
			throw $e;
		}
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function delete($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_vystava_rozhodci where ID = %i", $id ];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}

	/**
	 * @param int $vID
	 * @param int $rID
	 * @return bool
	 */
	public function deleteByVIDAndRID($vID, $rID) {
		$return = false;
		if (!empty($vID) && !empty($rID)) {
			$query = ["delete from appdata_vystava_rozhodci where vID = %i and rID = %i", $vID, $rID];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}

	public function migrateRefereeFromOldStructure() {
		$result = $this->connection->query("select * from v2j");
		$showRepository = new ShowRepository($this->connection);
		$refereeRepositoty = new RefereeRepository($this->connection);
		try {
			$this->connection->begin();
			foreach ($result->fetchAll() as $row) {
				$showRefereeEntity = new ShowRefereeEntity();

				if ($showRepository->getShow($row['vID']) == null) {    // v DB chyb� odkaz na v�stavu = nem��u zmigrovat
					continue;
				}
				$showRefereeEntity->setVID($row['vID']);

				if ($refereeRepositoty->getReferee($row['jID']) == null) { // pokud v DB chyb� odkaz na rozhodciho nemuzu to migrovat
					continue;
				}
				$showRefereeEntity->setRID($row['jID']);

				if (($row['p1'] == 0) && ($row['p2'] == 0) && ($row['p3'] == 0)) {
					/** @var ShowEntity $showEntity */
					$showEntity = $showRepository->getShow($showRefereeEntity->getVID());
					$showEntity->setRozhodci($showRefereeEntity->getRID());
					$showRepository->save($showEntity);
				} else {
					$plemenaStara = ["p1" => 18, "p2" => 17, "p3" => 19];
					foreach ($plemenaStara as $stary => $novy) {
						if ($row[$stary] == 1) {
							$showRefereeEntity->setPlemeno($novy);
							if ($row['t10'] == 1) {
								$showRefereeEntity->setTrida(102);
								$this->save($showRefereeEntity);
							}
							if ($row['t20'] == 1) {
								$showRefereeEntity->setTrida(103);
								$this->save($showRefereeEntity);
							}
							if ($row['t30'] == 1) {
								$showRefereeEntity->setTrida(104);
								$this->save($showRefereeEntity);
							}
							if ($row['t40'] == 1) {
								$showRefereeEntity->setTrida(105);
								$this->save($showRefereeEntity);
							}
							if ($row['t50'] == 1) {
								$showRefereeEntity->setTrida(106);
								$this->save($showRefereeEntity);
							}
							if ($row['t60'] == 1) {
								$showRefereeEntity->setTrida(107);
								$this->save($showRefereeEntity);
							}
							if ($row['t70'] == 1) {
								$showRefereeEntity->setTrida(108);
								$this->save($showRefereeEntity);
							}
							if ($row['t80'] == 1) {
								$showRefereeEntity->setTrida(109);
								$this->save($showRefereeEntity);
							}
							if ($row['t90'] == 1) {
								$showRefereeEntity->setTrida(110);
								$this->save($showRefereeEntity);
							}
							if ($row['t100'] == 1) {
								$showRefereeEntity->setTrida(111);
								$this->save($showRefereeEntity);
							}
							if ($row['t110'] == 1) {
								$showRefereeEntity->setTrida(112);
								$this->save($showRefereeEntity);
							}
							if ($row['t120'] == 1) {
								$showRefereeEntity->setTrida(113);
								$this->save($showRefereeEntity);
							}
							// pokud není vybraná žádný typ co rozhodčí posuzuje tak posuzje vše
							if (($row['t10'] == 0) &&
								($row['t20'] == 0) &&
								($row['t30'] == 0) &&
								($row['t40'] == 0) &&
								($row['t50'] == 0) &&
								($row['t60'] == 0) &&
								($row['t70'] == 0) &&
								($row['t80'] == 0) &&
								($row['t90'] == 0) &&
								($row['t100'] == 0) &&
								($row['t110'] == 0) &&
								($row['t120'] == 0)
							) {
								$types = [102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113];
								foreach ($types as $type) {
									$showRefereeEntity->setTrida($type);
									$this->save($showRefereeEntity);
								}
							}
						}
					}
				}
			}
			$this->connection->query("RENAME TABLE v2j to migrated_v2j");
			$this->connection->commit();
		} catch (\Exception $e) {
				$this->connection->rollback();
				throw $e;
		}
	}
}