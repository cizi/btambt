<?php

namespace App\Model;

use App\Enum\DogStateEnum;
use App\Enum\LitterApplicationStateEnum;
use App\Forms\DogFilterForm;
use App\Model\Entity\BreederEntity;
use App\Model\Entity\DogEntity;
use App\Model\Entity\DogFileEntity;
use App\Model\Entity\DogHealthEntity;
use App\Model\Entity\DogOwnerEntity;
use App\Model\Entity\DogPicEntity;
use App\Model\Entity\VetEntity;
use App\Model\Entity\LitterApplicationEntity;
use Dibi\Connection;
use Dibi\Row;
use Nette\Application\UI\Presenter;
use Nette\Http\Session;
use Nette\Utils\DateTime;
use Nette\Utils\Paginator;

class DogRepository extends BaseRepository {

	/** @const klíč pro poslední předcůdce psa */
	const SESSION_LAST_PREDECESSOR = 'lastPredecessor';

	/** @const znak pro nevybraného psa v selectu  */
	const NOT_SELECTED = "-";

	/** @const pořadí pro fenu */
	const FEMALE_ORDER = 30;

	/** @const pořadí pro psa */
	const MALE_ORDER = 29;

	/** @const pořadí pro jednotlivá zdraví  */
	const DOV_ORDER = 69;
	const MDR_ORDER = 62;

	/** @var EnumerationRepository  */
	private $enumRepository;

	/** @var \Dibi\Connection */
	protected $connection;

	/** @var Session */
	private $session;

	/** @var LangRepository */
	private $langRepository;

	/** @var LitterApplicationRepository */
    private $litterApplicationRepository;
    
    /** @var UserRepository */
    private $userRepository;    // needed juzst for migration

    /** @var VetRepository */
    private $vetRepository;

	/**
	 * @param EnumerationRepository $enumerationRepository
	 * @param Connection $connection
	 * @param Session $session
	 * @param LangRepository $langRepository
     * @param LitterApplicationRepository;
     * @param UserRepository $userRepository
     * @param VetRepository $vetRepository
	 */
	public function __construct(
		EnumerationRepository $enumerationRepository,
		Connection $connection,
		Session $session,
		LangRepository $langRepository,
        LitterApplicationRepository $litterApplicationRepository,
        UserRepository $userRepository,
        VetRepository $vetRepository
	) {
		$this->enumRepository = $enumerationRepository;
		$this->session = $session;
		$this->langRepository = $langRepository;
        $this->litterApplicationRepository = $litterApplicationRepository;
        $this->userRepository = $userRepository;
        $this->vetRepository = $vetRepository;

		parent::__construct($connection);
	}

	/**
	 * @param int $id
	 * @return DogEntity
	 */
	public function getDog($id) {
		$query = ["select * from appdata_pes where ID = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$dogEntity = new DogEntity();
			$dogEntity->hydrate($row->toArray());
			return $dogEntity;
		}
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function getName($id) {
		$name = "";
		$dog = $this->getDog($id);
		if ($dog != null) {
			$name = trim($dog->getTitulyPredJmenem() . " " . $dog->getJmeno() . " " . $dog->getTitulyZaJmenem());
		}

		return $name;
	}

	/**
	 * @return array
	 */
	public function findFemaleDogsForSelect($withNotSelectedOption = true, $justBreeding = false, $alive = false) {
        $query = [];
        $query[] = "select `ID`, `Jmeno` from appdata_pes where Stav = %i and Pohlavi = %i";
        $query[] = DogStateEnum::ACTIVE;
        $query[] = self::FEMALE_ORDER;

        if ($justBreeding) {
            $query[] = " and Chovnost = "  . EnumerationRepository::CHOVNOST_CHOVNY;
        }
        if ($alive) {
            $query[] = " and ((DatUmrti >= " . date(DogEntity::MASKA_DATA) . ") OR (DatUmrti is null))";
        }
        
		$result = $this->connection->query($query);
		$dogs = [];

		if ($withNotSelectedOption) {
			$dogs[0] = self::NOT_SELECTED;
		}
		foreach ($result->fetchAll() as $row) {
			$dog = $row->toArray();
			$dogs[$dog['ID']] = trim($dog['Jmeno']);
		}

		return $dogs;
	}

	/**
	 * @return array
	 */
	public function findDogsForSelect($withNotSelectedOption = true) {
		$query = ["select `ID`, `Jmeno` from appdata_pes where Stav = %i", DogStateEnum::ACTIVE];
		$result = $this->connection->query($query);
		$dogs = [];

		if ($withNotSelectedOption) {
			$dogs[0] = self::NOT_SELECTED;
		}
		foreach ($result->fetchAll() as $row) {
			$dog = $row->toArray();
			$dogs[$dog['ID']] = trim($dog['Jmeno']);
		}

		return $dogs;
	}

	/**
	 * @return DogEntity[]
	 */
	public function findMaleDogsForSelect($withNotSelectedOption = true) {
		$query = ["select `ID`, `Jmeno` from appdata_pes where Stav = %i and Pohlavi = %i", DogStateEnum::ACTIVE, self::MALE_ORDER];
		$result = $this->connection->query($query);
		$dogs = [];

		if ($withNotSelectedOption) {
			$dogs[0] = self::NOT_SELECTED;
		}
		foreach ($result->fetchAll() as $row) {
			$dog = $row->toArray();
			$dogs[$dog['ID']] = trim($dog['Jmeno']);
		}

		return $dogs;
	}

	/**
	 * @param Paginator $paginator
	 * @param array $filter
	 * @param null $owner
	 * @param null $breeder
     * @param bool $restrictVisibilityByUser
	 * @return DogEntity[]
	 * @throws \Dibi\Exception
	 */
	public function findDogs(Paginator $paginator, array $filter, $owner = null, $breeder = null, $restrictVisibilityByUser = false) {
        if (empty($filter) && ($owner == null) && ($breeder == null)) {
            if ($restrictVisibilityByUser) {
                $query = ["select * from appdata_pes where Stav = %i and SkrytCelouKartu = 0 order by `Jmeno` asc limit %i , %i", DogStateEnum::ACTIVE, $paginator->getOffset(), $paginator->getLength()];
            } else {
                $query = ["select * from appdata_pes where Stav = %i order by `Jmeno` asc limit %i , %i", DogStateEnum::ACTIVE, $paginator->getOffset(), $paginator->getLength()];
            }
		} else {
			$query[] = "select *, SPLIT_STR(CisloZapisu, '/', 3) as PlemenoCZ, ap.ID as ID from appdata_pes as ap ";
			foreach ($this->getJoinsToArray($filter, $owner, $breeder) as $join) {
				$query[] = $join;
			}
            $query[] = "where Stav = " . DogStateEnum::ACTIVE . " ";
            if ($restrictVisibilityByUser) {
                $query[] = " and SkrytCelouKartu = 0";
            }
            $query[] = $this->getWhereFromKeyValueArray($filter, $owner, $breeder);
            if (isset($filter[DogFilterForm::DOG_FILTER_LAST_14_DAYS])) {
                $query[] = " order by `PosledniZmena` desc limit %i , %i";
            } else if (isset($filter[DogFilterForm::DOG_FILTER_ORDER_NUMBER])) {
				$query[] = " order by PlemenoCZ " . (($filter[DogFilterForm::DOG_FILTER_ORDER_NUMBER]) == 2 ? "desc" : "asc") . " limit %i , %i";
			} else {
				$query[] = " order by `Jmeno` asc limit %i , %i";
			}
			$query[] = $paginator->getOffset();
			$query[] = $paginator->getLength();
		}
		$result = $this->connection->query($query);

		$dogs = [];
		foreach ($result->fetchAll() as $row) {
			$dog = new DogEntity();
			$dog->hydrate($row->toArray());
			$dogs[] = $dog;
		}

		return $dogs;
    }

	/**
	 * @param array $filter
	 * @param null $owner
	 * @param null $breeder
     * @param false $restrictVisibilityByUser
	 * @return int|mixed
	 * @throws \Dibi\Exception
	 */
	public function getDogsCount(array $filter, $owner = null, $breeder = null, $restrictVisibilityByUser = false) {
        $query = [];
        if (empty($filter) && ($owner == null) && ($breeder == null)) {
            $query[] = "select count(ID) as pocet from appdata_pes as ap ";
            $query[] = "where Stav = " . DogStateEnum::ACTIVE;
		} else {
			$query[] = "select count(distinct ap.ID) as pocet from appdata_pes as ap ";
			foreach ($this->getJoinsToArray($filter, $owner, $breeder) as $join) {
				$query[] = $join;
			}
			$query[] = "where Stav = " . DogStateEnum::ACTIVE . " ";
			$query[] = $this->getWhereFromKeyValueArray($filter, $owner, $breeder);
        }
        if ($restrictVisibilityByUser) {
            $query[] = " and ap.SkrytCelouKartu = 0";
        }
		$row = $this->connection->query($query);

		return ($row ? $row->fetch()['pocet'] : 0);
	}

	/**
	 * Připraví joiny tabulek
	 * @param $filter
	 * @param null $owner
	 * @param null $breeder
	 * @return array
	 */
	private function getJoinsToArray($filter, $owner = null, $breeder = null) {
		$joins = [];
		if ($owner != null) {
			$joins[] = "left join `appdata_majitel` as am on ap.ID = am.pID";
		}
		if ($breeder != null) {
			$joins[] = "left join `appdata_chovatel` as ach on ap.ID = ach.pID";
		}
		if (isset($filter[DogFilterForm::DOG_FILTER_BREEDER])) {
			$joins[] = "left join `appdata_chovatel` as ac on ap.ID = ac.pID
						left join `user` as u on ac.uID = u.ID ";
			unset($filter[DogFilterForm::DOG_FILTER_BREEDER]);
		}

		if (
			isset($filter[DogFilterForm::DOG_FILTER_HEALTH])
			|| isset($filter[DogFilterForm::DOG_FILTER_PROB_DKK])
            || isset($filter[DogFilterForm::DOG_FILTER_PROB_DLK])
            || isset($filter[DogFilterForm::DOG_FILTER_HEALTH_TEXT]
			)) {
			$joins[] = "left join `appdata_zdravi` as az on ap.ID = az.pID ";
			unset($filter[DogFilterForm::DOG_FILTER_HEALTH]);
			unset($filter[DogFilterForm::DOG_FILTER_PROB_DKK]);
            unset($filter[DogFilterForm::DOG_FILTER_PROB_DLK]);
            unset($filter[DogFilterForm::DOG_FILTER_HEALTH_TEXT]);
		}

		return $joins;
	}

	/**
	 * @param array $filter
	 * @param int $owner
	 * @param int $breeder
	 * @return string
	 */
	private function getWhereFromKeyValueArray(array $filter, $owner = null, $breeder = null) {
		// odstraním data, která jsou součástí filteru, ale nepatří do WHERE klauzule
		unset($filter[DogFilterForm::DOG_FILTER_ORDER_NUMBER]);	// tohle sem v podstatě nepatří, ale je to souččástí filtru

        $return = ((count($filter) > 0) || ($owner != null) || ($breeder != null) ? " and " : "");

		$dbDriver = $this->connection->getDriver();
		$currentLang = $this->langRepository->getCurrentLang($this->session);
		if ($owner != null) {
			$return .= sprintf("am.uID = %d and am.Soucasny = 1", $owner);	// je to soucasny spravne
			if (($breeder == null) && ((count($filter) > 0))) {
				$return .= " and ";
			} elseif (($breeder != null)) {
				$return .= " or ";
			}
		}
		if ($breeder != null) {
			$return .= sprintf("ach.uID = %d", $breeder);	// a majitele
			$return .= (count($filter) > 0 ? " and " : "");
		}
		if (isset($filter[DogFilterForm::DOG_FILTER_LAST_14_DAYS])) {
			$return .= " PosledniZmena >= (CURDATE() - INTERVAL 14 DAY)";
			$return .= (count($filter) > 1 ? " and " : "");
			unset($filter[DogFilterForm::DOG_FILTER_LAST_14_DAYS]);
        }
		if (isset($filter[DogFilterForm::DOG_FILTER_LAND])) {
            $return .= sprintf("ap.Zeme = %d", $filter[DogFilterForm::DOG_FILTER_LAND]);
			$return .= (count($filter) > 1 ? " and " : "");
			unset($filter[DogFilterForm::DOG_FILTER_LAND]);
		}
		if (isset($filter[DogFilterForm::DOG_FILTER_BREEDER])) {
			$return .= sprintf("ac.uID = %d", $filter[DogFilterForm::DOG_FILTER_BREEDER]);
			$return .= (count($filter) > 1 ? " and " : "");
			unset($filter[DogFilterForm::DOG_FILTER_BREEDER]);
		}
		if (isset($filter["Jmeno"])) {
			$return .= 	"(CONCAT_WS(' ', TitulyPredJmenem, Jmeno, TitulyZaJmenem) like \"%".$filter["Jmeno"]."%\")";
			$return .= (count($filter) > 1 ? " and " : "");
			unset($filter["Jmeno"]);
		}
		if (isset($filter[DogFilterForm::DOG_FILTER_HEALTH])) {	// pokud mám zdraví omezím výběr
			$return .= sprintf("az.Typ = %d", $filter[DogFilterForm::DOG_FILTER_HEALTH]);
			$return .= (count($filter) > 1 ? " and " : "");
			unset($filter[DogFilterForm::DOG_FILTER_HEALTH]);
        }
        if (isset($filter[DogFilterForm::DOG_FILTER_HEALTH_TEXT])) { // ale musím připojit vysledek
            $return .= sprintf("az.Vysledek = %s", $dbDriver->escapeText($filter[DogFilterForm::DOG_FILTER_HEALTH_TEXT]));
            $return .= (count($filter) > 1 ? " and " : "");
            unset($filter[DogFilterForm::DOG_FILTER_HEALTH_TEXT]);
        }		

		if (isset($filter[DogFilterForm::DOG_FILTER_PROB_DKK]) || isset($filter[DogFilterForm::DOG_FILTER_PROB_DLK])) {
			$dkk = (isset($filter[DogFilterForm::DOG_FILTER_PROB_DKK]) ? $this->enumRepository->findEnumItemByOrder($currentLang, $filter[DogFilterForm::DOG_FILTER_PROB_DKK]) : "");
			$dlk = (isset($filter[DogFilterForm::DOG_FILTER_PROB_DLK]) ? $this->enumRepository->findEnumItemByOrder($currentLang, $filter[DogFilterForm::DOG_FILTER_PROB_DLK]) : "");
			if ($dkk != "" && $dlk != "") {
				$return .= sprintf("(az.Typ in (65,66) and az.Vysledek in (%s, %s))", $dbDriver->escapeText($dkk), $dbDriver->escapeText($dlk));
				//$return .= "((az.Typ = 65 and az.Vysledek = '"  . $dkk . "') and (az.Typ = 66 and az.Vysledek = '"  . $dlk . "'))";
			} else if ($dkk != "") {
				$return .= sprintf("(az.Typ = 65 and az.Vysledek = %s)", $dbDriver->escapeText($dkk));
			} else if ($dlk != "") {
				$return .= sprintf("(az.Typ = 66 and az.Vysledek = %s)", $dbDriver->escapeText($dlk));
			}
			unset($filter[DogFilterForm::DOG_FILTER_PROB_DKK]);
			unset($filter[DogFilterForm::DOG_FILTER_PROB_DLK]);
			$return .= (count($filter) > 0 ? " and " : "");
		}
		if (isset($filter[DogFilterForm::DOG_FILTER_BIRTDATE])) {	// datum narození
			if (strpos($filter[DogFilterForm::DOG_FILTER_BIRTDATE], 'from') !== false) {
				$return .= sprintf(" YEAR(DatNarozeni) >= %s", $dbDriver->escapeText(str_replace("from", "", $filter[DogFilterForm::DOG_FILTER_BIRTDATE])));
			} else {
				$return .= sprintf(" YEAR(DatNarozeni) = %s", $dbDriver->escapeText($filter[DogFilterForm::DOG_FILTER_BIRTDATE]));
			}
			$return .= (count($filter) > 1 ? " and " : "");
			unset($filter[DogFilterForm::DOG_FILTER_BIRTDATE]);
		}

		$i = 0;
		foreach ($filter as $key => $value) {
			if ($key == DogFilterForm::DOG_FILTER_EXAM) {	// like
				$return .= 	sprintf("`Zkousky` like %s", $dbDriver->escapeLike($value, 0));
			} else {	// where
				$return .= sprintf("%s = %s", $key, $dbDriver->escapeText($value));
			}
			if (($i+1) != count($filter)) {
				$return .= " and ";
			}
			$i++;
		}

		return $return;
	}

	/**
	 * @param bool|true $withNotSelectedOption
	 * @return array
	 */
	public function findBirtYearsForSelect($withNotSelectedOption = true) {
		$years = [];
		if ($withNotSelectedOption) {
			 $years[0] = self::NOT_SELECTED;
		}
		$query = ["select DISTINCT YEAR(DatNarozeni) as DatNarozeni from appdata_pes where Stav = %i ORDER BY DatNarozeni DESC", DogStateEnum::ACTIVE];
		$result = $this->connection->query($query);
		foreach ($result->fetchAll() as $row) {
			if ($row['DatNarozeni'] != "") {
				$years[$row['DatNarozeni']] = $row['DatNarozeni'];
				$years['from' . $row['DatNarozeni']] = DOG_TABLE_DOG_YEAR_FROM . " " . $row['DatNarozeni'];
			}
		}

		return $years;
	}

	/**
	 * @param int $pID
	 */
	public function findSiblings($pID) {
		$siblings = [];
		$dog= $this->getDog($pID);
		if (($dog != null) && ($dog->getMID() != null) && ($dog->getOID() != null)) {
			$query = ["select * from appdata_pes where Stav = %i and mID = %i and oID = %i and ID <> %i", DogStateEnum::ACTIVE, $dog->getMID(), $dog->getOID(), $dog->getID()];
			$result = $this->connection->query($query);

			foreach ($result->fetchAll() as $row) {
				$sibling = new DogEntity();
				$sibling->hydrate($row->toArray());
				$siblings[] = $sibling;
			}
		}

		return $siblings;
	}

	/**
	 * @param int $pID
	 * @return DogEntity[]
	 */
	public function findDescendants($pID) {
		$descendants = [];
		$dog = $this->getDog($pID);
		if ($dog != null) {
			if ($dog->getPohlavi() == self::MALE_ORDER) {
				$query = ["select * from appdata_pes where Stav = %i and oID = %i order by mID", DogStateEnum::ACTIVE, $dog->getID()];
			} else {
				$query = ["select * from appdata_pes where Stav = %i and mID = %i order by oID", DogStateEnum::ACTIVE, $dog->getID()];
			}
			$result = $this->connection->query($query);
			foreach ($result->fetchAll() as $row) {
				$descendant = new DogEntity();
				$descendant->hydrate($row->toArray());
				$descendants[] = $descendant;
			}
		}

		return $descendants;
	}

	/**
	 * Uloží/aktualizuje zázanm do tabulky zdraví psa
	 * @param DogHealthEntity $dogHealthEntity
	 */
	public function saveDogHealth(DogHealthEntity $dogHealthEntity) {
		if ($dogHealthEntity->getID() == null) {
			$query = ["insert into appdata_zdravi ", $dogHealthEntity->extract()];
		} else {
			$query = ["update appdata_zdravi set ", $dogHealthEntity->extract(), "where ID=%i", $dogHealthEntity->getID()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param BreederEntity $breederEntity
	 */
	public function saveBreeder(BreederEntity $breederEntity) {
		if ($breederEntity->getUID() == 0) {		// pokud je v selectu vybrána nula tak mažu
			$this->deleteBreederByDogId($breederEntity->getPID());
		} else {
			if ($breederEntity->getID() == null ) {
				$query = ["insert into appdata_chovatel ", $breederEntity->extract()];
			} else {
				$query = ["update appdata_chovatel set ", $breederEntity->extract(), "where ID=%i", $breederEntity->getID()];
			}
			$this->connection->query($query);
		}
	}

	/**
	 * @param DogOwnerEntity $dogOwnerEntity
	 */
	public function saveOwner(DogOwnerEntity $dogOwnerEntity) {
		$alreadyIn = ["select * from appdata_majitel where uID = %i and pID = %i", $dogOwnerEntity->getUID(), $dogOwnerEntity->getPID()];
		$row = $this->connection->query($alreadyIn)->fetch();
		if ($row) {	// pokud existuje akorat přepnu na současného
			$dogOwn = new DogOwnerEntity();
			$dogOwn->hydrate($row->toArray());
			$query = ["update appdata_majitel set Soucasny = %i where ID = %i", $dogOwnerEntity->isSoucasny(), $dogOwn->getID()];
		} else {	// pokud záznam neexistuje vložím jako nový současný majitel
			$query = ["insert into appdata_majitel ", $dogOwnerEntity->extract()];
		}
		$this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return DogHealthEntity[]
	 */
	public function findAllHealthsByDogId($id) {
		$query = ["select * from appdata_zdravi where pID = %i", $id];
		$result = $this->connection->query($query);

		$dogHealths = [];
		foreach ($result->fetchAll() as $row) {
			$dogHealth = new DogHealthEntity();
			$dogHealth->hydrate($row->toArray());
			$dogHealths[] = $dogHealth;
		}

		return $dogHealths;
	}

	/**
	 * @param int $id
	 * @return DogHealthEntity[]
	 */
	public function findHealthsByDogId($id) {
		$query = ["select * from appdata_zdravi where pID = %i and Vysledek <> ''", $id];
		$result = $this->connection->query($query);

		$dogHealths = [];
		foreach ($result->fetchAll() as $row) {
			$dogHealth = new DogHealthEntity();
			$dogHealth->hydrate($row->toArray());
			$dogHealths[] = $dogHealth;
		}

		return $dogHealths;
	}

	/**
	 * Přepnutí psa do režimu smazání
	 * @param int $id
	 * @return bool
	 */
	public function delete($id) {
		$return = true;
		if (!empty($id)) {
			try {
				$query = ["update appdata_pes set `Stav` = %i where ID = %i", DogStateEnum::DELETED, $id];    // pak nastavím psa jako smazaného
				$this->connection->query($query);
			} catch (\Exception $e) {
				$return = false;
			}
		}

		return $return;
	}
	/**
	 * Opravdové smazaní psa z DB pokud to cizí klíče dovolí (jakože spíš ne)
	 * @param int $id
	 * @return bool
	 */
	public function realDelete($id) {
		$return = true;
		if (!empty($id)) {
			try {
				$this->connection->begin();

				$query = ["delete from appdata_pes_obrazky where pID = %i", $id];    // nejdříve smažu obrázky
				$this->connection->query($query);

				$query = ["delete from appdata_pes_soubory where pID = %i", $id];    // pak smažu ostaní soubory
				$this->connection->query($query);

				$this->deleteHealthByDogId($id);
				$this->deleteBreederByDogId($id);
				$this->deleteOwnerByDogId($id);

				$query = ["delete from appdata_pes where ID = %i", $id];    // pak smažu psa
				$this->connection->query($query);

				$this->connection->commit();
			} catch (\Exception $e) {
				$this->connection->rollback();
				$return = false;
			}
		}

		return $return;
	}

	/**
	 * @param int $pID
	 */
	private function deleteHealthByDogId($pID) {
		$query = ["delete from appdata_zdravi where pID = %i", $pID];
		$this->connection->query($query);
	}

	/**
	 * @param int $pID
	 */
	private function deleteBreederByDogId($pID) {
		$query = ["delete from appdata_chovatel where pID = %i", $pID];
		$this->connection->query($query);
	}

	/**
	 * @param int $pID
	 */
	private function deleteOwnerByDogId($pID) {
		$query = ["delete from appdata_majitel where pID = %i", $pID];
		$this->connection->query($query);
	}

	/**
	 * @param int $typ
	 * @param int $pID
	 * @return DogHealthEntity
	 */
	public function getHealthEntityByDogAndType($typ, $pID) {
		$query = ["select * from appdata_zdravi where Typ = %i and pID = %i", $typ, $pID];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$healthEntity = new DogHealthEntity();
			$healthEntity->hydrate($row->toArray());
			return $healthEntity;
		}
	}

	/***
	 * @param int $typ
	 * @param int $pID
	 * @param string $delimiter
	 * @return string
	 */
	public function getHealthByTypeAsStringWithDesc($pID,$typ, $delimiter = ": ") {
		$result = "";
		$query = ["select az.Vysledek, ei.item as Popis from enum_item as ei left join appdata_zdravi as az on ei.order = az.Typ
				where az.Vysledek <> '' and az.pID = %i and az.Typ = %i", $pID, $typ];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$result = $row['Popis'] . $delimiter . $row['Vysledek'];
		}

		return $result;
	}

	/**
	 * @param DogEntity $dogEntity
	 * @param DogPicEntity[]
	 * @param DogHealthEntity[]
	 * @param BreederEntity[]
	 * @param DogOwnerEntity[]
	 * @param DogFileEntity[]
	 * @param int [$mIdOrOidForNewDog]
	 */
	public function save(DogEntity $dogEntity, array $dogPics, array $dogHealth, array $breeders, array $owners, array $dogFiles, $mIdOrOidForNewDog = null) {
		try {
			$this->connection->begin();
			$dogEntity->setPosledniZmena(new DateTime());
			if ($dogEntity->getMID() == 0) {
				$dogEntity->setMID(null);
			}
			if ($dogEntity->getOID() == 0) {
				$dogEntity->setOID(null);
            }
            if ($dogEntity->getZeme() == 0) {
				$dogEntity->setZeme(null);
			}
			if ($dogEntity->getID() == null) {	// nový pes
				$query = ["insert into appdata_pes ", $dogEntity->extract()];
				$this->connection->query($query);
				$dogEntity->setID($this->connection->getInsertId());
            } else {	// editovaný pes
                if ($this->getDog($dogEntity->getID()) == null) {   // ale pokud znám ID a není v DB, zkusím ho pod tímto ID založit
                    $query = ["insert into appdata_pes ", $dogEntity->extract()];
				    $this->connection->query($query);
                } else {
                    $query = ["update appdata_pes set ", $dogEntity->extract(), "where ID=%i", $dogEntity->getID()];
				    $this->connection->query($query);
                }
			}
			/** @var DogHealthEntity $dogHealthEntity */
			foreach($dogHealth as $dogHealthEntity) {
				$dogHealthEntity->setPID($dogEntity->getID());
				if ($dogHealthEntity->getVeterinar() == 0) {	// pokud nebyl veterinář vybrán vynuluji jeho záznam
					$dogHealthEntity->setVeterinar(null);
				}
				$this->saveDogHealth($dogHealthEntity);
			}
			/** @var BreederEntity $breeder */
			foreach($breeders as $breeder) {
				$breeder->setPID($dogEntity->getID());
				$this->saveBreeder($breeder);
			}

			$query = ["update appdata_majitel set Soucasny = %i where pID = %i", 0, $dogEntity->getID()];	// nevím co mi nyní přijde takže všechny rovnou udělám jako bývalé majitele
			$this->connection->query($query);
			/** @var DogOwnerEntity $owner */
			foreach($owners as $owner) {
				$owner->setPID($dogEntity->getID());
				$this->saveOwner($owner);
			}

			/** @var DogPicEntity $dogPic */
			foreach ($dogPics as $dogPic) {
				$dogPic->setPID($dogEntity->getID());
				$dogPic->setVychozi(0);
				$this->saveDogPic($dogPic);
			}

			/** @var DogFileEntity $dogFile */
			foreach ($dogFiles as $dogFile) {
				$dogFile->setPID($dogEntity->getID());
				$this->saveDogFile($dogFile);
			}

			if ($mIdOrOidForNewDog != null) {	// aktualizujeme potomka o rodiče - pokud je z čeho
				$descendantDog = $this->getDog($mIdOrOidForNewDog);
				if ($descendantDog != null) {
					if ($dogEntity->getPohlavi() == self::MALE_ORDER) {
						$descendantDog->setOID($dogEntity->getID());
					} else {
						$descendantDog->setMID($dogEntity->getID());
					}
					$query = ["update appdata_pes set ", $descendantDog->extract(), "where ID=%i", $descendantDog->getID()];
					$this->connection->query($query);
				}
			}

			$this->connection->commit();
		} catch (\Exception $e) {
            $this->connection->rollback();
			throw $e;
		}
	}

	/**
	 * @param array $dogs
	 * @param array $breeders
	 * @param array $owners
	 * @param LitterApplicationEntity $litterApplicationEntity
	 * @throws \Exception
	 */
	public function saveDescendants(array $dogs, array $breeders, array $owners, LitterApplicationEntity $litterApplicationEntity) {
		try {
			$this->connection->begin();
			foreach ($dogs as $dog) {
				$this->save($dog, [], [], $breeders, $owners, []);
			}

			$litterApplicationEntity->setZavedeno(LitterApplicationStateEnum::REWRITTEN);
			$this->litterApplicationRepository->save($litterApplicationEntity);

			$this->connection->commit();
		} catch (\Exception $e) {
			$this->connection->rollback();
			throw $e;
		}
	}

	/**
	 * @param DogPicEntity $dogPicEntity
	 */
	public function saveDogPic(DogPicEntity $dogPicEntity) {
		$picQuery = ["insert into appdata_pes_obrazky ", $dogPicEntity->extract()];
		$this->connection->query($picQuery);
	}

	/**
	 * @param DogFileEntity $dogFileEntity
	 */
	public function saveDogFile(DogFileEntity $dogFileEntity) {
		$picQuery = ["insert into appdata_pes_soubory ", $dogFileEntity->extract()];
		$this->connection->query($picQuery);
	}

	/**
	 * @param int $pID
	 * @return DogPicEntity[]
	 */
	public function findDogPics($pID) {
		$query = ["select * from appdata_pes_obrazky where pID = %i order by `vychozi` desc" , $pID];
		$result = $this->connection->query($query);

		$pics = [];
		foreach ($result->fetchAll() as $row) {
			$dogPic = new DogPicEntity();
			$dogPic->hydrate($row->toArray());
			$pics[] = $dogPic;
		}

		return $pics;
	}

	/**
	 * @param int $pID
	 * @return DogFileEntity[]
	 */
	public function findDogFiles($pID) {
		$query = ["select * from appdata_pes_soubory where pID = %i order by id, typ desc", $pID];
		$result = $this->connection->query($query);

		$files = [];
		foreach ($result->fetchAll() as $row) {
			$dogFile = new DogFileEntity();
			$dogFile->hydrate($row->toArray());
			$files[] = $dogFile;
		}

		return $files;
	}

	/**
	 * @param int $dogId
	 * @param int $picId
	 */
	public function setDefaultDogPic($dogId, $picId) {
		$query = ["update appdata_pes_obrazky set vychozi=0 where pID = %i", $dogId];
		$this->connection->query($query);
		$query = ["update appdata_pes_obrazky set vychozi=1 where pID = %i and id = %i", $dogId, $picId];
		$this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function deleteDogPic($id) {
		$return = true;
		if (!empty($id)) {
			$query = ["delete from appdata_pes_obrazky where id = %i", $id];
			$return = $this->connection->query($query) == 1 ? true : false;
		}

		return $return;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function deleteDogFile($id) {
		$return = true;
		if (!empty($id)) {
			$query = ["delete from appdata_pes_soubory where id = %i", $id];
			$return = $this->connection->query($query) == 1 ? true : false;
		}

		return $return;
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function getDkkByDogId($id) {
		$query = ["select * from appdata_zdravi where pID = %i and Typ = %i", $id, 65];
		$result = $this->connection->query($query);

		$row = $result->fetch();
		if ($row) {
			$dogHealth = new DogHealthEntity();
			$dogHealth->hydrate($row->toArray());
			return $dogHealth;
		}
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function getDlkByDogId($id) {
		$query = ["select * from appdata_zdravi where pID = %i and Typ = %i", $id, 66];
		$result = $this->connection->query($query);

		$row = $result->fetch();
		if ($row) {
			$dogHealth = new DogHealthEntity();
			$dogHealth->hydrate($row->toArray());
			return $dogHealth;
		}
	}

	/**
	 * Najde psy u kterých je uživatel veden jako chovatel
	 *
	 * @param int $userId
	 * @return DogEntity[]
	 */
	public function findDogsByBreeder($userId) {
		$query = ["select *, ac.ID as acID from appdata_chovatel as ac left join appdata_pes as ap on ac.pID = ap.ID where ac.uID = %i and ap.Stav = %i", $userId, DogStateEnum::ACTIVE];
		$result = $this->connection->query($query);

		$dogs = [];
		foreach ($result->fetchAll() as $row) {
			$dog = new DogEntity();
			$dog->hydrate($row->toArray());
			$dogs[$row['acID']] = $dog;
		}

		return $dogs;
	}

	/**
	 * Najde psy u kterých je užovatel veden jako stávající vlastník
	 *
	 * @param int $userId
	 * @return DogEntity[]
	 */
	public function findDogsByCurrentOwner($userId) {
		$query = ["select *, am.ID as amID from appdata_majitel as am left join appdata_pes as ap on am.pID = ap.ID where am.uID = %i and am.Soucasny = 1 and ap.Stav = %i", $userId, DogStateEnum::ACTIVE];
		$result = $this->connection->query($query);

		$dogs = [];
		foreach ($result->fetchAll() as $row) {
			$dog = new DogEntity();
			$data = $row->toArray();
			$dog->hydrate($data);
			$dogs[$data['amID']] = $dog;
		}

		return $dogs;
	}

	/**
	 * Najde psy které měl uživatel evidované jako vlastník
	 *
	 * @param int $userId
	 * @return DogEntity[]
	 */
	public function findDogsByPreviousOwner($userId) {
		$query = ["select *, am.ID as amID from appdata_majitel as am left join appdata_pes as ap on am.pID = ap.ID where am.uID = %i and am.Soucasny = 0 and ap.Stav = %i", $userId, DogStateEnum::ACTIVE];
		$result = $this->connection->query($query);

		$dogs = [];
		foreach ($result->fetchAll() as $row) {
			$dog = new DogEntity();
			$data = $row->toArray();
			$dog->hydrate($data);
			$dogs[$data['amID']] = $dog;
		}

		return $dogs;
	}

	// ------ příbuzbost ----
	/**
	 * @param int $pID
	 * @param int $fID
	 * @return float
	 */
	public function genealogRelationship($pID,$fID) {
		$coef = floor($this->genealogRshipGo($pID,$fID,1)*10000)/100;
		return $coef;
	}

	/**
	 * @param int $ID1
	 * @param int $ID2
	 * @param int $level
	 * @return number
	 */
	public function genealogRshipGo($ID1,$ID2,$level) {
		global $deepMarkArray;
		$deepMarkArray = [];
		$tree1 = array(array());
		$this->genealogGetRshipPedigree(NULL, $ID1, 0, 4, $tree1);
		$tree1Toc = array_shift($tree1);

		$tree2 = array(array());
		$this->genealogGetRshipPedigree(NULL, $ID2, 0, 4, $tree2);
		$tree2Toc = array_shift($tree2);

		$coef = 0;
		foreach ($tree1 as $index1 => $dog1) {
			if (in_array($dog1['ID'], $tree2Toc)) {

				//naslo se!!! Najdeme vyskyty v Tree2 a promazeme
				foreach ($tree2 as $index2 => $dog2) {
					if (($dog2['ID'] == $dog1['ID']) and ($dog1['dID'] != $dog2['dID'])) {
						if (!in_array($dog1['ID'], $deepMarkArray)) {
							$deepMarkArray[] = $dog1['ID'];
						}
						$subcoef = pow(0.5, $dog1['level'] + $dog2['level'] + 1);
						$coef += $subcoef;
					}
				}
			}
		}

		return $coef;
	}

	/**
	 * Funkce pro zjisteni pribuznosti
	 *
	 * @param $dID
	 * @param $ID
	 * @param $level
	 * @param $levels
	 * @param $output
	 * @param array $route
	 */
	private function genealogGetRshipPedigree($dID,$ID,$level,$levels,&$output,$route = array()) {
		if (($level > $levels)) {
			return;
		}
		if (($ID == NULL)) {
			$GLOBALS['lastRship'] = false;
			return;
		}
		$query = ["select pes.ID AS ID, pes.Jmeno AS Jmeno, pes.oID AS oID, pes.mID AS mID FROM appdata_pes as pes WHERE ID= %i LIMIT 1", $ID];
		$fetch = $this->connection->query($query)->fetch();
		if ($fetch == false) {
			$row['Jmeno'] = "";
			$row['oID']= "";
			$row['mID'] = "";
		} else {
			$row = $fetch->toArray();
		}
		$output[0][] = $ID;
		$output[] = array(
			'ID' => $ID,
			'Jmeno' => $row['Jmeno'],
			'dID' => $dID,
			'oID' => $row['oID'],
			'mID' => $row['mID'],
			'level' => $level,
			'route' => $route
		);

		$route[] = $ID;
		//if (isset($row['oID'])) {
			$this->genealogGetRshipPedigree($ID,$row['oID'],$level+1,$levels,$output,$route);
		//}
		//if (isset($row['mID'])) {
			$this->genealogGetRshipPedigree($ID,$row['mID'],$level+1,$levels,$output,$route);
		//}
	}

	/**
	 * @param int $ID
	 * @param int $max
	 * @param string $lang
	 * @param Presenter $presenter
     * @param bool $restrictVisibilityByUser
	 * @param bool $isUserAdmin
	 * @return string
	 */
	public function genealogDeepPedigree($ID, $max, $lang, Presenter $presenter, $isUserAdmin, $restrictVisibilityByUser, $deepMark = false) {
		$this->clearPedigreeSession();
		global $pedigree;
		$query = ["SELECT pes.ID AS ID, pes.Jmeno AS Jmeno, pes.oID AS oID, pes.mID AS mID FROM appdata_pes as pes
										WHERE pes.ID= %i LIMIT 1", $ID];
		$row = $this->connection->query($query)->fetch()->toArray();
		$pedigree = array();
		$this->genealogDPTrace($row['oID'],1,$max, $lang);
		$this->genealogDPTrace($row['mID'],1,$max, $lang);

		return $this->genealogShowDeepPTable($max, $presenter, $ID, $isUserAdmin, $restrictVisibilityByUser, $deepMark);
    }

    // MIGRACE START
    public function migrateOldStructure() {
        $migratedDogs = 0;
        $this->connection->query("SET sql_mode = ''");
        $this->connection->query("SET FOREIGN_KEY_CHECKS=0");

        $defaultPass = "geneAheslo";
        $fakeEmailCounter = 0;
        $tables = ["gene002", "gene003"];
        $usersCreated = 0;
        foreach ($tables as $table) {
            $query = "select * from {$table}";
            $dogs = $this->connection->query($query);
            foreach ($dogs->fetchAll() as $dog) {
                $newDogEntity = new DogEntity();
                $newDogEntity->setID($dog->ID);
                $newDogEntity->setOID($dog->oID);
                $newDogEntity->setMID($dog->mID);
                $newDogEntity->setJmeno($dog->Jmeno);
                $newDogEntity->setDatNarozeni($dog->DatNar);
                $newDogEntity->setDatUmrti($dog->DatUmr);
                $newDogEntity->setPohlavi($this->getSex($dog->Pohlavi));
                $newDogEntity->setBarva($this->getFurColor($dog->Barva));
                $newDogEntity->setSrst(45);
                $newDogEntity->setCisloZapisu($dog->Czap);
                $newDogEntity->setChovnost($this->getBreedingState($dog->Chovnost));
                $newDogEntity->setVyska((empty($dog->Vyska) ? NULL : floatval($dog->Vyska)));
                $newDogEntity->setPlemeno($this->getNewBreed($table));

                if (!empty($dog->SvodDate) && ($dog->SvodDate != "0000-00-00")) {
                    $svodDatum = new DateTime($dog->SvodDate);
                    $newDogEntity->setBonitace($svodDatum->format(DogEntity::MASKA_DATA_ZOBRAZENI));
                }

                $newDogEntity->setPosudek($dog->SvodVysl);
                $newDogEntity->setTitulyKomentar($dog->Vystavy);
                $newDogEntity->setSkrytPotomky($dog->LockPotomci);
                $newDogEntity->setSkrytCelouKartu($dog->LockKarta);
                $newDogEntity->setZkousky($dog->Zkousky);
                $newDogEntity->setZkouskySlozene($dog->Zkousky);
                $newDogEntity->setKomentar($dog->Poznamka);

                // tetování a čip
                $tetCip = trim($dog->TetCip);
                if (!empty($tetCip)) {
                    if (strpos($tetCip, ",") === true) {
                        $tc = explode(",", $tetCip);
                        $tetovani = trim($tc[0]);
                        $cip = trim($tc[1]);
                        if ((strlen($tetovani) == 3) || (strlen($tetovani) == 4) || (strlen($tetovani) == 5)) {
                            $newDogEntity->setTetovani($tetovani);
                            $newDogEntity->setCip($cip);
                        } else if (strlen($tetovani) == 15) {
                            $newDogEntity->setCip($tetovani);
                            $newDogEntity->setTetovani($cip);
                        }
                    } else {
                        if (strlen($tetCip) == 15) {
                            $newDogEntity->setCip($tetCip);
                        } else {
                            $newDogEntity->setTetovani($tetCip);
                        }
                    }
                }
            
                $dogHealth = [];
                $dbCols = [ 59 => "TestHeart", 64 => "testLS", 65 => "testLU", 67 => "TestPatella", 68 => "TestBAER", 69 => "TestLuxace", ];
                foreach ($dbCols as $typ => $hodnota) {
                    if (isset($dog[$hodnota])) {
                        $dh = $this->getMigrationDogHealth($typ, trim($dog[$hodnota]));
                        if (!empty($dh)) {
                            $dogHealth[] = $dh;
                        }
                    }
                }
                $breeders = $this->getMigrationBreederOwner(trim($dog->Chovatel), true, "", "", "", "", "nejakeSuoerHeslo");
                $owners = $this->getMigrationBreederOwner(trim($dog->Majitel), false, $dog->mAdresa, $dog->mTelefon, $dog->mMail, $dog->mWww, $dog->Heslo);
                try {
                    $this->save($newDogEntity, [], $dogHealth, $breeders, $owners, []);
                    $migratedDogs++;
                } catch (Exception $e) {
                    dump($e);
                    die;
                }
            }
        }
        $this->connection->query("SET FOREIGN_KEY_CHECKS=1");
        echo "Bylo vytvořeno {$migratedDogs} psů";
    }

    private function getMigrationDogHealth($typ, $vysledek) {
        $dogHealth = null;
        if (!empty($vysledek)) {
            $dhe = new DogHealthEntity();
            $dhe->setTyp($typ);
            $datum = explode("-", $vysledek);
            if (count($datum) > 1) {
                try {
                    $date = DateTime::createFromFormat(DogEntity::MASKA_DATA_ZOBRAZENI, trim($datum[0]));
                    if ($date instanceof DateTime) {
                        $dhe->setDatum($date->format(DogEntity::MASKA_DATA));
                        $vysledek = \str_replace(trim($datum[0]), "", $vysledek);
                    }
                } catch (Exception $e) {
                    $dhe->setDatum(null);
                }
            }
                    
            $vetFound = false;
            $vets = $this->vetRepository->findVets();
            foreach ($vets as $vet) {             
                $vetJmenoPrijmeni = $vet->getTitulyPrefix().$vet->getJmeno().$vet->getPrijmeni();
                if (strpos(trim(preg_replace('/\s+/', '', $vysledek)), $vetJmenoPrijmeni) !== false) {  
                    $dhe->setVeterinar($vet->getID());
                    $vysledek = trim(\str_replace($vetJmenoPrijmeni, "", $vysledek));
                    $vetFound = true;
                    break;
                }
            }

            if (($vetFound == false) && (strpos($vysledek, "MVDr.") !== false)) {
                $newVet = new VetEntity();
                $newVet->setTitulyPrefix("MVDr.");
                $vetDet = \explode("MVDr.", $vysledek);
                $vetJmen = \explode(" ", trim($vetDet[1]));
                if (count($vetJmen) > 1) {
                    $newVet->setJmeno($vetJmen[0]);
                    $newVet->setPrijmeni($vetJmen[1]);
                    $this->vetRepository->saveVet($newVet);

                    $vets = $this->vetRepository->findVets();
                    foreach ($vets as $vet) {             
                        $vetJmenoPrijmeni = $vet->getTitulyPrefix().$vet->getJmeno().$vet->getPrijmeni();
                        if (strpos(trim(preg_replace('/\s+/', '', $vysledek)), $vetJmenoPrijmeni) !== false) {  
                            $dhe->setVeterinar($vet->getID());
                            $vysledek = trim(\str_replace($vetJmenoPrijmeni, "", $vysledek));
                            break;
                        }
                    }
                }
            }
            $dhe->setVysledek("");
            $dhe->setKomentar($vysledek);
            $dogHealth = $dhe;
        }

        return $dogHealth;
    }

    private function getMigrationBreederOwner($userString, $isBreeder, $mAdresa, $mTelefon, $mMail, $mWww, $heslo) {
        $usersByType = [];
        if (!empty($userString)) {
            $celeJmenoArr = explode(" ", $userString);
            $jmeno = (!empty($celeJmenoArr[0]) ? trim($celeJmenoArr[0]) : "");
            $jmeno = \str_replace(",", "", $jmeno);
            $celeJmenoArr[0] = "";

            $prijmeni = (!empty($celeJmenoArr[1]) ? trim($celeJmenoArr[1]) : "");
            $prijmeni = implode(" ", $celeJmenoArr); //  \str_replace(",", "", $prijmeni);

            if (!empty($jmeno) && (!empty($prijmeni))) {
                $userInDb = $this->userRepository->getUserByNameSurname($jmeno, $prijmeni);
                if (empty($userInDb)) { // musím založit neexistuje
                    $fakeEmailCounter = 0;
                    if (empty($mMail)) {
                        $email = "unknow_email{$fakeEmailCounter}@email.cz";
                    } else {
                        $email = $mMail;
                    }
                    $userByEmail = $this->userRepository->getUserByEmail($email);
                    while ($userByEmail != null) {
                        $fakeEmailCounter++;
                        $email = "unknow_email{$fakeEmailCounter}@email.cz";
                        $userByEmail = $this->userRepository->getUserByEmail($email);
                    }
                    $newUserId = $this->userRepository->createUser($jmeno, $prijmeni, $mAdresa, $mTelefon, $email, $mWww, $heslo);
                    if ($isBreeder) {   // chovatel 
                        $breeder = new BreederEntity();
                        $breeder->setUID($newUserId);
                        $usersByType[] = $breeder;
                    } else {    // majitel
                        $owner = new DogOwnerEntity();
                        $owner->setSoucasny(true);
                        $owner->setUID($newUserId);
                        $usersByType[] = $owner;
                    }
                } else {
                    if ($isBreeder) {   // chovatel 
                        $breeder = new BreederEntity();
                        $breeder->setUID($userInDb->getId());
                        $usersByType[] = $breeder;
                    } else {    // majitel
                        $owner = new DogOwnerEntity();
                        $owner->setSoucasny(true);
                        $owner->setUID($userInDb->getId());
                        $usersByType[] = $owner;
                    }
                }
            }
        }

        return $usersByType;
    }

    private function getBreedingState($oldValue) {
        switch ($oldValue) {
            case 1:
                return 27;
            break;

            case 2:
                return 28;
            break;

            default:
            return 0;
        break;
        }

    }

    /**
	 * Vrátí novou hodnotu číselníku pro Plemeno dle tabulky
	 * @param $oldBreed
	 * @return int|null
	 */
	private function getNewBreed($tableName) {
        if ($tableName ==  "gene003") {
            return 18;
        } else {
            return 17;
        }
        
	}

	/**
	 * Vrátí novou hodnotu číselníku pro Barvu
	 * @param $oldColor
	 * @return int|null
	 */
	private function getFurColor($oldColor) {
		switch ($oldColor) {
			case "černá s žíháním, bílé zn.":
				return 199;
				break;

			case "žíhaná, bílé zn.":
				return 197;
				break;

			case "bílá":
				return 194;
				break;

			case "bílá se zn.":
				return 195;
				break;

			case "červená, bílé zn.":
				return 201;
				break;

            case "žíhaná":
                return 196;
            break;

            case "tricolor, bílé zn.":
                return 205;
            break;

            case "písková":
                return 200;
            break;

            case "plavá":
                return 202;
            break;

            case "černá s žíháním":
                return 198;
            break;

            case "písková, bílé zn.":
                return 201;
            break;

            case "červená":
                return 200;
            break;

            case "plavá, bílé zn.":
                return 203;
            break;

            case "tricolor":
                return 204;
            break;

            case "tricolor, bílé zn.":
                return 205;
            break;

            case "černá s pálením":
                return 206;
            break;

			default:
				return null;
				break;
		}
    }
    
	/**
	 * Vrátí novou hodnutu číselníku pro Pohlaví
	 * @param $oldSex
	 * @return int|null
	 */
	private function getSex($oldSex) {
		switch ($oldSex) {
			case 1:
				return 29;
				break;

			case 2:
				return 30;
				break;

			default:
				return null;
				break;
		}
    }
    // migrace END

	/**
	 * @param int $ID
	 * @param int $level
	 * @param int $max
	 * @param string $lang
	 */
	private function genealogDPTrace($ID,$level,$max, $lang) {
		global $pedigree;
		if ($level > $max) {
			return;
		};
		if ($ID != NULL) { // predek existuje
			$query = ["SELECT pes.ID AS ID, pes.Jmeno AS Jmeno, pes.oID AS oID, pes.mID AS mID, pes.Vyska AS Vyska, pes.Pohlavi As Pohlavi,
										pes.SkrytCelouKartu AS SkrytCelouKartu,
                                        pes.Plemeno As Plemeno,
										pes.Barva As BarvaOrder,
										plemeno.item as Varieta,
										pes.TitulyPredJmenem AS TitulyPredJmenem,
										pes.TitulyZaJmenem AS TitulyZaJmenem,
										barva.item AS Barva
										FROM appdata_pes as pes
										LEFT JOIN enum_item as plemeno
											ON (pes.Plemeno = plemeno.order && plemeno.enum_header_id = 7 && plemeno.lang = %s)
										LEFT JOIN enum_item as barva
											ON (pes.Barva = barva.order && barva.enum_header_id = 4 && barva.lang = %s)
										WHERE pes.ID = %i
										LIMIT 1", $lang, $lang, $ID];
			$row = $this->connection->query($query)->fetch()->toArray();
			if (EnumerationRepository::PLEMENO_BT == $row["Plemeno"]) {
                unset($row["Vyska"]);
                $query = ["SELECT item as Nazev, Vysledek FROM appdata_zdravi as zdravi LEFT JOIN enum_item as ciselnik
                    ON (ciselnik.enum_header_id = 14 AND ciselnik.order = zdravi.Typ) WHERE pID = %i and zdravi.Typ <> %i and ciselnik.lang = %s ORDER BY Datum DESC", $row['ID'], EnumerationRepository::ZDRAVI_PLL, $lang];
            } else {
                $query = ["SELECT item as Nazev, Vysledek FROM appdata_zdravi as zdravi LEFT JOIN enum_item as ciselnik
                    ON (ciselnik.enum_header_id = 14 AND ciselnik.order = zdravi.Typ) WHERE pID = %i and zdravi.Typ <> %i and ciselnik.lang = %s ORDER BY Datum DESC", $row['ID'], EnumerationRepository::ZDRAVI_DNA, $lang];
            }
            $zdravi = $this->connection->query($query)->fetchPairs("Nazev","Vysledek");
            $zdravi = $zdravi === null ? '' : $zdravi;

			/* $query = ["SELECT Vysledek AS DKK FROM appdata_zdravi WHERE pID = %i && Typ=65 ORDER BY Datum DESC LIMIT 1", $row['ID']];
			$DKK = $this->connection->query($query)->fetch();
			$DKK = $DKK === false ? '' : $DKK->toArray()['DKK'];

			$query = ["SELECT Vysledek AS DLK FROM appdata_zdravi WHERE pID = %i && Typ=66 ORDER BY Datum DESC LIMIT 1", $row['ID']];
			$DLK = $this->connection->query($query)->fetch();
			$DLK = $DLK === false ? '' : $DLK->toArray()['DLK']; */

			$pedigree[] = array(
				'Uroven' => $level,
				'ID' => $row['ID'],
				'Jmeno' => $this->arGet($row,'Jmeno'),
				'Barva' => $this->arGet($row,'Barva'),
				'Varieta' => $this->arGet($row,'Varieta'),
				'TitulyPredJmenem' => $this->arGet($row,'TitulyPredJmenem'),
                'TitulyZaJmenem' => $this->arGet($row,'TitulyZaJmenem'),
                'SkrytCelouKartu' => $row['SkrytCelouKartu'],
				// 'DKK' => $DKK,
				// 'DLK' => $DLK,
				'Vyska' => $this->arGet($row,'Vyska'),
				'zdravi' => $zdravi
			);

			$this->genealogDPTrace($row['oID'], $level + 1, $max, $lang);
			$this->genealogDPTrace($row['mID'], $level + 1, $max, $lang);
		} else { // predek neexistuje
			$pedigree[] = array(
				'Uroven' => $level,
				'ID' => NULL,
				'Jmeno' => '&nbsp;',
				'Barva' => '&nbsp;'
			);
			$this->genealogDPTrace(NULL, $level + 1, $max, $lang);
			$this->genealogDPTrace(NULL, $level + 1, $max, $lang);
		}
	}

	/**
	 * @param array $array
	 * @param string $name
	 * @return string
	 */
	private function arGet($array,$name) {
		if (isset($array[$name]) and trim($array[$name]) != '') {
			return ($array[$name]);
		} else {
			return('');
		}
	}

	/**
	 * @param int $max
	 * @param Presenter $presenter
	 * @param int $ID
	 * @param bool $isUserAdmin
     * @param bool restrictVisibilityByUser
	 * @param bool $deepMark
	 * @return string
	 */
	private function genealogShowDeepPTable($max, Presenter $presenter, $ID, $isUserAdmin, $restrictVisibilityByUser, $deepMark) {
		global $pedigree;
		global $deepMarkArray;
		$maxLevel = $max;
		$htmlOutput = "<table border='0' cellspacing='1' cellpadding='3' class='genTable'><tr>";
		$lastLevel = 0;
		for ($i = 0; $i < count($pedigree); $i++) {
			if ($pedigree[$i]['Uroven'] <= $lastLevel) {
				$htmlOutput .= '<tr>';
			}
			$lastLevel = $pedigree[$i]['Uroven'];
			$adds = array();
			if (isset($pedigree[$i]['Varieta']) && $pedigree[$i]['Varieta'] != '') {
				$adds[] = $pedigree[$i]['Varieta'];
			}
			if (isset($pedigree[$i]['Barva']) && $pedigree[$i]['Barva'] != '') {
				$adds[] = $pedigree[$i]['Barva'];
			}
			if (isset($pedigree[$i]['Vyska']) && !empty($pedigree[$i]['Vyska'])) {
				$adds[] = ($pedigree[$i]['Vyska']) . ' cm';
			}
			if (isset($pedigree[$i]['zdravi']) && $pedigree[$i]['zdravi'] != '') {
				foreach ($pedigree[$i]['zdravi'] as $zdraviTyp => $zdraviVysledek) {
					if (trim($zdraviVysledek) != "") {
						$adds[] = '' . $zdraviTyp . ': ' . $zdraviVysledek;
					}
				}
			}
			if (count($adds) > 0) {
				$adds = '<br />'.implode(', ',$adds);
			} else {
				$adds = '';
			}
			if (isset($pedigree[$i]['TitulyPredJmenem']) && trim($pedigree[$i]['TitulyPredJmenem']) != '') {
				$pedigree[$i]['Jmeno'] = $pedigree[$i]['TitulyPredJmenem'] . ' ' . $pedigree[$i]['Jmeno'];
			}
			if (isset($pedigree[$i]['TitulyZaJmenem']) && trim($pedigree[$i]['TitulyZaJmenem']) != '') {
				$pedigree[$i]['Jmeno'] = $pedigree[$i]['Jmeno'] . ', ' . $pedigree[$i]['TitulyZaJmenem'];
			}

			if ($pedigree[$i]['ID'] != NULL) {
				//$link = ($isUserAdmin ? $presenter->link('FeItem1velord2:edit', $pedigree[$i]['ID']) : $presenter->link('FeItem1velord2:view', $pedigree[$i]['ID']));
                $link = $presenter->link('FeItem1velord2:view', $pedigree[$i]['ID']);
                $restrictDogLink = ($restrictVisibilityByUser && ($pedigree[$i]['SkrytCelouKartu'] == 1));
				if ($deepMark && in_array($pedigree[$i]['ID'], $deepMarkArray)) {
					$htmlOutput .= '<td rowspan="'.pow(2,$maxLevel - $pedigree[$i]['Uroven'] ).'" style="background:#FFFFCC"><b>';
                    $htmlOutput .= ($restrictDogLink ? $pedigree[$i]['Jmeno'] : '<a href="' . $link . '">' . $pedigree[$i]['Jmeno'] . '</a>');
                    $htmlOutput .= '</b>'.$adds . '</td>';
				} else {
					$htmlOutput .= '<td rowspan="'.pow(2,$maxLevel - $pedigree[$i]['Uroven'] ).'"><b>';
                    $htmlOutput .= ($restrictDogLink ? $pedigree[$i]['Jmeno'] : '<a href="' . $link . '">' . $pedigree[$i]['Jmeno'] . '</a>');
                    $htmlOutput .= '</b>'.$adds.'</td>';
				}
				if (($pedigree[$i]['Uroven'] > 0) && ($pedigree[$i]['Uroven'] != $maxLevel)) {
					$this->setLastPredecessorSession($pedigree[$i]['Uroven'], $pedigree[$i]['ID']);
					$this->setLastPredecessorSession($pedigree[$i]['Uroven'] . 'Uroven', $pedigree[$i]['Uroven']);
				}
			} else {
				$htmlOutput .= '<td rowspan="' . pow(2, $maxLevel - $pedigree[$i]['Uroven']) . '">';
				if ($isUserAdmin) {
					if ($pedigree[$i]['Uroven'] == 1) {
						$htmlOutput .= '<a href="' . $presenter->link("FeItem1velord2:addMissingDog", $ID) . '">' . DOG_FORM_PEDIGREE_ADD_MISSING . '</a>';
					} else {
						if ($this->getLastPredecessorSession($pedigree[$i]['Uroven'] - 1) != null) {
							$htmlOutput .= '<a href="' . $presenter->link("FeItem1velord2:addMissingDog", $this->getLastPredecessorSession($pedigree[$i]['Uroven'] - 1)) . '">' . DOG_FORM_PEDIGREE_ADD_MISSING . '</a>';
						}
					}
					for ($y = ($pedigree[$i]['Uroven']); $y <= $maxLevel; $y++) {	// jakmile jsem tady už jednou vypsal zbylá data nepotřebuji
						$this->clearPedigreeKey($y);
						$this->clearPedigreeKey($y . 'Uroven');
					}
					$htmlOutput .= '</td>'; // <b>' . $pedigree[$i]['Jmeno'].'</b><br/> '.$adds.'</td>';
				}
				if ($pedigree[$i]['Uroven'] == $maxLevel) {
					$htmlOutput .= '</tr>';
				}
			}
		}
		$htmlOutput .= "</table>";

		return $htmlOutput;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	private function getLastPredecessorSession($key) {
		$return = "";
		if ($this->session->hasSection(self::SESSION_LAST_PREDECESSOR)) {
			$section = $this->session->getSection(self::SESSION_LAST_PREDECESSOR);
			$return = $section->{$key};
		}

		return $return;
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	private function setLastPredecessorSession($key, $value) {
		$section = $this->session->getSection(self::SESSION_LAST_PREDECESSOR);
		$section->{$key} = $value;
	}

	/**
	 * Vyčistím sešnu
	 */
	private function clearPedigreeKey($wantToDeleteKey) {
		// odstraním posledně použite mID a oID u rokomene
		$section = $this->session->getSection(self::SESSION_LAST_PREDECESSOR);
		foreach($section as $key => $value) {
			if ($wantToDeleteKey == $key) {
				unset($section->{$key});
			}
		}
	}

	/**
	 * Vyčistím sešnu
	 */
	private function clearPedigreeSession() {
		// odstraním posledně použite mID a oID u rokomene
		$section = $this->session->getSection(self::SESSION_LAST_PREDECESSOR);
		foreach($section as $key => $value) {
			unset($section->{$key});
		}
	}
}