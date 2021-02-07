<?php

namespace App\Controller;

use App\Enum\DogChangeStateEnum;
use App\Enum\DogStateEnum;
use App\Model\AwaitingChangesRepository;
use App\Model\DogRepository;
use App\Model\Entity\AwaitingChangesEntity;
use App\Model\Entity\BreederEntity;
use App\Model\Entity\DogEntity;
use App\Model\Entity\DogHealthEntity;
use App\Model\Entity\DogOwnerEntity;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;
use App\Model\VetRepository;
use App\Model\WebconfigRepository;
use Dibi\DateTime;
use Nette\Application\UI\Presenter;
use Nette\Security\User;


class DogChangesComparatorController {

	/**
	 * @const string tabulky
	 */
	const TBL_DOG_NAME = "appdata_pes";
	const TBL_DOG_HEALTH_NAME = "appdata_zdravi";
	const TBL_BREEDER_NAME = "appdata_chovatel";
	const TBL_OWNER_NAME = "appdata_majitel";
	const TBL_EXAM_NAME = "appdata_pes_zkousky";
	const TBL_DOG_FILE = "appdata_pes_soubory";

	/**
	 * @const string sloupce
	 */
	const TBL_CLM_DOG_BIRTH = "DatNarozeni";
	const TBL_CLM_DOG_DEATH = "DatUmrti";
	const TBL_CLM_HEALTH_VET = "Veterinar";
	const TBL_CLM_HEALTH_DATE = "Datum";

	/** @var AwaitingChangesRepository */
	private $awaitingChangeRepository;

	/** @var User */
	private $user;

	/** @var WebconfigRepository */
	private $webconfigRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var  DogRepository */
	private $dogRepository;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var VetRepository */
	private $vetRepository;

	public function __construct(
		AwaitingChangesRepository $awaitingChangesRepository,
		User $user,
		WebconfigRepository $webconfigRepository,
		UserRepository $userRepository,
		DogRepository $dogRepository,
		EnumerationRepository $enumerationRepository,
		VetRepository $vetRepository
	) {
		$this->awaitingChangeRepository = $awaitingChangesRepository;
		$this->user = $user;
		$this->webconfigRepository = $webconfigRepository;
		$this->userRepository = $userRepository;
		$this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->vetRepository = $vetRepository;
	}

	/**
	 * Vrátí pole aktuálního zdraví kde je v klíči pole jeho typ
	 * @param DogHealthEntity[] $currentDogHealths
	 */
	private function arrayWithTypeKey(array $currentDogHealths) {
		$array = [];
		foreach ($currentDogHealths as $health) {
			$array[$health->getTyp()] = $health;
		}

		return $array;
    }
  
    /**
	 * @param array $currentDogsExams
	 * @param array $newDogsExams
     * @param int $dogId
	 * @return bool
	 */
    public function compareDogsExams(array $currentDogsExams, array $newDogsExams, $dogId) {
        // dump($currentDogsExams, $newDogsExams); die;
        $changes = [];
        $new = [];
        foreach ($newDogsExams as $exam) {
            $new[] = $exam->getZID();           
        }

        $current = [];
        foreach ($currentDogsExams as $exam) {
            $current[] = $exam->getZID();
        }

        $changeMaster = new AwaitingChangesEntity();
        $changeMaster->setPID($dogId);
        $changeMaster->setUID($this->user->getId());
        $changeMaster->setStav(DogChangeStateEnum::INSERTED);
        $changeMaster->setDatimVlozeno(new DateTime());
        $changeMaster->setTabulka(self::TBL_EXAM_NAME);
        $changeMaster->setCID(EnumerationRepository::ZKOUSKY);
        $changeMaster->setSloupec("zID");

        foreach ($new as $newExam) {
            if (!\in_array($newExam, $current)) {    // nový záznam
                $change = clone($changeMaster);
                $change->setAktualniHodnota("");
                $change->setPozadovanaHodnota($newExam);
                $changes[] = $change;
            }
        }

        foreach ($current as $currentExam) {
            if (!\in_array($currentExam, $new)) {
                $change = clone($changeMaster);
                $change->setAktualniHodnota($currentExam);
                $change->setPozadovanaHodnota("");
                $changes[] = $change;
            }
        }
        $this->awaitingChangeRepository->writeChanges($changes);	// zapíšu změny

		return (count($changes) > 0);
    }

	/**
	 * @param array $currentDogHealth
	 * @param array $newDogHealth
	 * @return bool
	 */
	public function compareSaveDogHealth(array $currentDogHealth, array $newDogHealth) {
		$changes = [];
		$currentDogHealth = $this->arrayWithTypeKey($currentDogHealth);
		foreach ($newDogHealth as $requiredHealth) {    // projíždím co vyplnili uživatel
			$changeMaster = new AwaitingChangesEntity();
			$changeMaster->setPID($requiredHealth->getPID());
			$changeMaster->setUID($this->user->getId());
			$changeMaster->setStav(DogChangeStateEnum::INSERTED);
			$changeMaster->setDatimVlozeno(new DateTime());
			$changeMaster->setTabulka(self::TBL_DOG_HEALTH_NAME);
			$changeMaster->setCID($requiredHealth->getTyp());
			if ($this->isSomethingFilled($requiredHealth)) {    // a zkontroluji zda něco vyplnil
				if (isset($currentDogHealth[$requiredHealth->getTyp()])) {    // jde o editovaný záznam
					$curDogHealth = $currentDogHealth[$requiredHealth->getTyp()];
					$changeMaster->setZID($curDogHealth->getID());                    // takže známe jeho ID
					if ($requiredHealth->getVysledek() != $curDogHealth->getVysledek()) {
						$change = clone($changeMaster);
						$change->setSloupec("Vysledek");
						$change->setAktualniHodnota($curDogHealth->getVysledek());
						$change->setPozadovanaHodnota($requiredHealth->getVysledek());
						$changes[] = $change;
					}
					if ($requiredHealth->getKomentar() != $curDogHealth->getKomentar()) {
						$change = clone($changeMaster);
						$change->setSloupec("Komentar");
						$change->setAktualniHodnota($curDogHealth->getKomentar());
						$change->setPozadovanaHodnota($requiredHealth->getKomentar());
						$changes[] = $change;
					}
					if (
						($requiredHealth->getDatum() != null) && ($curDogHealth->getDatum() != null)
						&& ($requiredHealth->getDatum()->format(DogHealthEntity::MASKA_DATA) !== $curDogHealth->getDatum()->format(DogHealthEntity::MASKA_DATA))
					) {
						$change = clone($changeMaster);
						$change->setSloupec(self::TBL_CLM_HEALTH_DATE);
						$change->setAktualniHodnota($curDogHealth->getDatum());
						$change->setPozadovanaHodnota($requiredHealth->getDatum());
						$changes[] = $change;
					}
					if (($requiredHealth->getVeterinar() != 0) && ($requiredHealth->getVeterinar() != $curDogHealth->getVeterinar())) {
						$change = clone($changeMaster);
						$change->setSloupec(self::TBL_CLM_HEALTH_VET);
						$change->setAktualniHodnota($curDogHealth->getVeterinar());
						$change->setPozadovanaHodnota($requiredHealth->getVeterinar());
						$changes[] = $change;
					}
				} else {    // jde o nový záznam (tohle je zavádějicí,protože vždy když dělám insert nového psa tak vložím všechny typy zdraví akorát prázdný)
					if ($requiredHealth->getVysledek() != "") {
						$change = clone($changeMaster);
						$change->setSloupec("Vysledek");
						$change->setPozadovanaHodnota($requiredHealth->getVysledek());
						$changes[] = $change;
					}
					if ($requiredHealth->getKomentar() != "") {
						$change = clone($changeMaster);
						$change->setSloupec("Komentar");
						$change->setPozadovanaHodnota($requiredHealth->getKomentar());
						$changes[] = $change;
					}
					if ($requiredHealth->getDatum() !== null) {
						$change = clone($changeMaster);
						$change->setSloupec(self::TBL_CLM_HEALTH_DATE);
						$change->setPozadovanaHodnota($requiredHealth->getDatum());
						$changes[] = $change;
					}
					if ($requiredHealth->getVeterinar() != 0) {
						$change = clone($changeMaster);
						$change->setSloupec(self::TBL_CLM_HEALTH_VET);
						$change->setPozadovanaHodnota($requiredHealth->getVeterinar());
						$changes[] = $change;
					}
				}
			}
		}
		$this->awaitingChangeRepository->writeChanges($changes);	// zapíšu změny

		return (count($changes) > 0);
	}

	/**
	 * @param DogHealthEntity $dogHealthEntity
	 * @return bool
	 */
	private function isSomethingFilled(DogHealthEntity $dogHealthEntity) {
		if (
			(trim($dogHealthEntity->getVeterinar()) != "")
			|| (trim($dogHealthEntity->getKomentar()) != "")
			|| ($dogHealthEntity->getDatum() != null)
			|| (trim($dogHealthEntity->getVysledek()) != "")
		) {
			return true;
		}

		return false;
	}

	/**
	 * @param DogEntity $currentDog
	 * @param DogEntity $newDog
	 * @return bool
	 */
	public function compareSaveDog(DogEntity $currentDog, DogEntity $newDog) {
		$enumValues = [
			// název sloupce v DB psa => číslo číselníků
			'Pohlavi' => EnumerationRepository::POHLAVI,
			'Plemeno' => EnumerationRepository::PLEMENO,
			'Barva' => EnumerationRepository::BARVA,
			'Srst' => EnumerationRepository::SRST,
			'Varlata' => EnumerationRepository::VARLATA,
			'Skus' => EnumerationRepository::SKUS,
			'Chovnost' => EnumerationRepository::CHOVNOST
		];
		$changes = [];
		foreach ($currentDog as $property => $currentValue) {
			$newValue = $newDog->{$property};
			if ($currentValue != $newValue) {
				if (
					(($property == self::TBL_CLM_DOG_DEATH) || ($property == self::TBL_CLM_DOG_BIRTH))	// pokud jde o Datumy
					&& ($currentValue != null) && ($newValue != null) && ($newValue->format(DogEntity::MASKA_DATA) == $currentValue->format(DogEntity::MASKA_DATA))
				) {
					continue;
				}
				$awaitingEntity = new AwaitingChangesEntity();
				$awaitingEntity->setAktualniHodnota($currentValue);
				$awaitingEntity->setPozadovanaHodnota($newValue);
				$awaitingEntity->setPID($currentDog->getID());
				$awaitingEntity->setDatimVlozeno(new DateTime());
				$awaitingEntity->setTabulka(self::TBL_DOG_NAME);
				$awaitingEntity->setSloupec($property);
				$awaitingEntity->setUID($this->user->getId());
				$awaitingEntity->setStav(DogChangeStateEnum::INSERTED);

				if (array_key_exists($property, $enumValues)) {
					$awaitingEntity->setCID($enumValues[$property]);
				}

				$changes[] = $awaitingEntity;
			}
		}
		$this->awaitingChangeRepository->writeChanges($changes);        // zapíšu změny

		return (count($changes) > 0);
	}

	/**
	 * @param DogOwnerEntity[] $currentOwners
	 * @param DogOwnerEntity[] $newOwners
	 * @return bool
	 */
	public function compareSaveOwners(array $currentOwners, array $newOwners) {
		$changes = [];
		foreach ($newOwners as $newOwner) {	// projedu ty co tam chci přidat
			$changeMaster = new AwaitingChangesEntity();
			$changeMaster->setUID($this->user->getId());
			$changeMaster->setStav(DogChangeStateEnum::INSERTED);
			$changeMaster->setDatimVlozeno(new DateTime());
			$changeMaster->setTabulka(self::TBL_OWNER_NAME);

			$currentOwner = $this->findOwnerInArray($currentOwners, $newOwner->getUID(), $newOwner->getPID());
			if ($currentOwner == null) {	// požadovaný majitel, kterého chci přidat není v současnýchy (=1) majitelých
				$change = clone($changeMaster);
				$change->setPID($newOwner->getPID());
				$change->setSloupec("uID");
				$change->setPozadovanaHodnota($newOwner->getUID());
				$changes[] = $change;
			}
		}

		foreach ($currentOwners as $currentOwner) {	// projedu ty co chci zneplatnit
			$changeMaster = new AwaitingChangesEntity();
			$changeMaster->setPID($currentOwner->getPID());
			$changeMaster->setUID($this->user->getId());
			$changeMaster->setStav(DogChangeStateEnum::INSERTED);
			$changeMaster->setDatimVlozeno(new DateTime());
			$changeMaster->setTabulka(self::TBL_OWNER_NAME);

			$newOwner = $this->findOwnerInArray($newOwners, $currentOwner->getUID(), $currentOwner->getPID());
			if ($newOwner == null) {	// požadovaný majitel, kterého chci přidat není v současnýchy (=1) majitelých
				$change = clone($changeMaster);
				$change->setSloupec("uID");
				$change->setZID($currentOwner->getID());
				$change->setAktualniHodnota($currentOwner->getUID());
				$changes[] = $change;
			}
		}
		$this->awaitingChangeRepository->writeChanges($changes);        // zapíšu změny

		return (count($changes) > 0);
	}

	/**
	 * Pokud běžný uživatel založí psa je třeba ho schválit, tedy poslat emaily apod.
	 * @param DogEntity $dogEntity
	 * @param string $dogUrl
	 */
	public function newDogCreated(DogEntity $dogEntity, $dogUrl) {
		$changes = [];
		$change = new AwaitingChangesEntity();
		$change->setPID($dogEntity->getID());
		$change->setUID($this->user->getId());
		$change->setStav(DogChangeStateEnum::INSERTED);
		$change->setDatimVlozeno(new DateTime());
		$change->setTabulka(self::TBL_DOG_NAME);
		$change->setSloupec("Stav");
		$change->setAktualniHodnota($dogEntity->getStav());
		$change->setPozadovanaHodnota(DogStateEnum::ACTIVE);
		$changes[] = $change;

		$this->awaitingChangeRepository->writeChanges($changes);        // zapíšu změny

		if (count($changes) > 0) {
			$userEntity = $this->userRepository->getUser($this->user->getId());
			$emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);

			// email pro uživatele
			//EmailController::SendPlainEmail($emailFrom, $userEntity->getEmail(), AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_SUBJECT_USER, AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_BODY_USER);		// TODO
			// email pro admina/y
			$body = sprintf(AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_BODY_ADMIN, $dogUrl, $dogUrl);
			EmailController::SendPlainEmail($userEntity->getEmail(), $emailFrom, AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_SUBJECT_ADMIN, $body);
		}
	}

	/**
	 * @param DogOwnerEntity[] $owners
	 * @param int $uID
	 * @param int $pID
	 * @return DogOwnerEntity
	 */
	private function findOwnerInArray(array $owners, $uID, $pID) {
		foreach ($owners as $owner) {
			if (($owner->getUID() == $uID) && ($owner->getPID() == $pID)) {
				return $owner;
			}
		}
	}

	/**
	 * @param $currentBreeder
	 * @param BreederEntity[] $newBreeder - tady by měl být jen jeden záznam
	 * @return bool
	 */
	public function compareSaveBreeder($currentBreeder, array $newBreeders) {
		if ($currentBreeder == null) {	// což se může stát
			$currentBreeder = new BreederEntity();
		}
		$changes = [];
		if (count($newBreeders) > 0) {
			/** @var BreederEntity $newBreeder */
			$newBreeder = $newBreeders[0];
			$newBreeder->setUID(empty($newBreeder->getUID()) ? null : $newBreeder->getUID());	// když se selectu chovatelů došla 0 přetypuji to na null, což je default když není chovatel vybrán
			if ($newBreeder->getUID() != $currentBreeder->getUID()) {
				$awaitingEntity = new AwaitingChangesEntity();
				$awaitingEntity->setZID($currentBreeder->getID());
				$awaitingEntity->setAktualniHodnota($currentBreeder->getUID());
				$awaitingEntity->setPozadovanaHodnota($newBreeder->getUID());
				$awaitingEntity->setPID($newBreeder->getPID());
				$awaitingEntity->setDatimVlozeno(new DateTime());
				$awaitingEntity->setTabulka(self::TBL_BREEDER_NAME);
				$awaitingEntity->setSloupec("uID");
				$awaitingEntity->setUID($this->user->getId());
				$awaitingEntity->setStav(DogChangeStateEnum::INSERTED);

				$changes[] = $awaitingEntity;
			}
		}
		$this->awaitingChangeRepository->writeChanges($changes);

		return (count($changes) > 0);
	}

	/**
	 * Pošle email uživateli a adminům, že na kartě psa došlo ke změně
	 * @param string $dogUrl
	 */
	public function sendInfoEmail($dogUrl) {
		$userEntity = $this->userRepository->getUser($this->user->getId());
		$emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);

		// email pro uživatele
		$body = sprintf(AWAITING_EMAIL_USER_DOG_BODY, $dogUrl);
		//EmailController::SendPlainEmail($emailFrom, $userEntity->getEmail(), AWAITING_EMAIL_USER_DOG_SUBJECT, $body);		// TODO
		// email pro admina/y
		$body = sprintf(AWAITING_EMAIL_ADMIN_DOG_BODY, $dogUrl);
		EmailController::SendPlainEmail($userEntity->getEmail(), $emailFrom, AWAITING_EMAIL_ADMIN_DOG_SUBJECT, $body);
	}


	// FUNKCE GENERUJICI HTML

	/**
	 * @param Presenter $presenter
	 * @param string $currentLang
	 * @return string
	 */
	public function generateAwaitingChangesHtml(Presenter $presenter, $currentLang) {
		$htmlOut = "
		<table class='table table-striped'>
			<thead>
				<tr>
					<th>" .AWAITING_CHANGES_DOG  . "</th>
					<th>". AWAITING_CHANGES_USER . "</th>
					<th>" .AWAITING_CHANGES_TIMESTAMP . "</th>
					<th>" .AWAITING_CHANGES_WHAT ."</th>
					<th>" .AWAITING_CHANGES_ORIGINAL_VALUE ."</th>
					<th>" .AWAITING_CHANGES_WANTED_VALUE ."</th>
					<th></th>
				</tr>
			</thead>
			<tbody>";

		$awaitingChanges = $this->awaitingChangeRepository->findAwaitingChanges();
		foreach ($awaitingChanges as $awaitingChange) {
			$htmlOut .= "<tr><td>";
			$dog = $this->dogRepository->getDog($awaitingChange->getpID());
			if ($dog != null) {
				$htmlOut .= "<a href='" . $presenter->link(':Frontend:FeItem1velord2:view', $dog->getID()) . "'>" . $dog->getTitulyPredJmenem() . " " . $dog->getJmeno() . " " . $dog->getTitulyZaJmenem() . "</a>";
			}
			$htmlOut .= '</td><td>';
			$user = $this->userRepository->getUser($awaitingChange->getUID());
			if ($user != null) {
				$htmlOut .= $user->getTitleBefore() . " " . $user->getName() . " " . $user->getSurname() . " " . $user->getTitleAfter();
			}
			$htmlOut .= '</td><td>';
			if ($awaitingChange->getDatimVlozeno() != null) {
				$htmlOut .= $awaitingChange->getDatimVlozeno()->format('d.m.Y H:i:s');
			}
			$htmlOut .= '</td>';

			$htmlOut .= $this->getChangeDetailByChange($awaitingChange, $currentLang);	// vrátí detail změny podle jejího typu pes/zdraví/apod
			
			$htmlOut .=  '<td class="alignRight" nowrap="nowrap">';
			$htmlOut .= "<a href='" . $presenter->link("proceedChange", $awaitingChange->getID()) . "'>";
			$htmlOut .= '<span class="glyphicon glyphicon-ok colorGreen"></span></a>&nbsp;&nbsp;';
			$htmlOut .= "<a href='#' data-href='" . $presenter->link('declineChange', $awaitingChange->getID()) . "' class='colorRed' data-toggle='modal' data-target='#confirm-delete' title='" . AWAITING_CHANGES_DECLINE . "'><span class='glyphicon glyphicon-remove colorRed'></span ></a>";
			$htmlOut .= '</td></tr>';
		}
		$htmlOut .=	'</tbody></table>';

		return $htmlOut;
    }
    
    /**
	 * @param Presenter $presenter
	 * @param string $currentLang
	 * @return string
	 */
	public function generateAwaitingChangesHtmlPerUser(Presenter $presenter, $currentLang, $uID) {
		$htmlOut = "";
		$awaitingChanges = $this->awaitingChangeRepository->findChangesByUser($uID);
		if (count($awaitingChanges)) {
			$htmlOut = "
				<table class='table table-striped'>
					<thead>
						<tr>
							<th>" . AWAITING_CHANGES_DOG . "</th>
							<th>" . AWAITING_CHANGES_USER . "</th>
							<th>" . AWAITING_CHANGES_TIMESTAMP . "</th>
							<th>" . AWAITING_CHANGES_WHAT . "</th>
							<th>" . AWAITING_CHANGES_ORIGINAL_VALUE . "</th>
							<th>" . AWAITING_CHANGES_WANTED_VALUE . "</th>
							<th>" . USER_REFERENCES_REQUESTED_CHANGES_STATE . "</th>
							<th></th>
						</tr>
					</thead>
					<tbody>";

			foreach ($awaitingChanges as $awaitingChange) {
				$htmlOut .= "<tr><td>";
				$dog = $this->dogRepository->getDog($awaitingChange->getpID());
				if ($dog != null) {
					$htmlOut .= "<a href='" . $presenter->link(':Frontend:FeItem1velord2:view',
							$dog->getID()) . "'>" . $dog->getTitulyPredJmenem() . " " . $dog->getJmeno() . " " . $dog->getTitulyZaJmenem() . "</a>";
				}
				$htmlOut .= '</td><td>';
				$user = $this->userRepository->getUser($awaitingChange->getUID());
				if ($user != null) {
					$htmlOut .= $user->getTitleBefore() . " " . $user->getName() . " " . $user->getSurname() . " " . $user->getTitleAfter();
				}
				$htmlOut .= '</td><td >';
				if ($awaitingChange->getDatimVlozeno() != null) {
					$htmlOut .= $awaitingChange->getDatimVlozeno()->format('d.m.Y H:i:s');
				}
				$htmlOut .= '</td>';

				$htmlOut .= $this->getChangeDetailByChange($awaitingChange,
					$currentLang);    // vrátí detail změny podle jejího typu pes/zdraví/apod

				$state = "";
				switch ($awaitingChange->getStav()) {
					case DogChangeStateEnum::INSERTED:
						$state = AWAITING_CHANGE_PROCEEDED_INS;
						break;
					case DogChangeStateEnum::PROCEEDED:
						$state = AWAITING_CHANGE_PROCEEDED_WHEN;
						break;
					case DogChangeStateEnum::DECLINED:
						$state = AWAITING_CHANGE_PROCEEDED_DEC;
						break;
				}
				$htmlOut .= "<td>". $state . "</td>";
				$htmlOut .= '<td class="alignRight">';
				$htmlOut .= "<a href='" . $presenter->link("User:DeleteUserChangeRequest", $awaitingChange->getID(),
						$uID) . "' class='colorRed' title='" . VET_CONFIRM_DELETE_DELETE . "'><span class='glyphicon glyphicon-remove'></span></a>";
				$htmlOut .= '</td></tr>';
			}
			$htmlOut .= '</tbody></table>';
		}

		return $htmlOut;
	}

    public function saveAwaitingChangeEntity(AwaitingChangesEntity $awaitingChangesEntity)
    {
        $this->awaitingChangeRepository->writeChanges([$awaitingChangesEntity]);
	}

	/**
	 * @param AwaitingChangesEntity $awaitingChangesEntity
	 * @param string $currentLang
	 * @return string
	 */
	private function getChangeDetailByChange(AwaitingChangesEntity $awaitingChangesEntity, $currentLang) {
		$result = "";
		$result .= '<td>';
		if (($awaitingChangesEntity->getTabulka() == self::TBL_BREEDER_NAME) || ($awaitingChangesEntity->getTabulka() == self::TBL_OWNER_NAME)) {
			$result .= ($awaitingChangesEntity->getTabulka() == self::TBL_BREEDER_NAME ? DOG_FORM_BREEDER : USER_OWNER);
		} else {
			switch ($awaitingChangesEntity->getSloupec()) {
				case "Vysledek":
				case "Komentar":
				case self::TBL_CLM_HEALTH_DATE:
				case self::TBL_CLM_HEALTH_VET:
					$result .= DOG_FORM_HEALTH . "(" . $this->enumerationRepository->findEnumItemByOrder($currentLang, $awaitingChangesEntity->getCID()) . ")";
					break;

				default:
					$result .= MATING_FORM_FID;
					break;
			}
		}
		$result .= ": {$awaitingChangesEntity->getSloupec()}</td>";
		$result .= '<td>';
		$val = $this->getChaneValueDetail($awaitingChangesEntity->getTabulka(), $awaitingChangesEntity->getSloupec(), $awaitingChangesEntity->getAktualniHodnota(), $awaitingChangesEntity->getCID(), $currentLang);
		$result .= (substr($val, 0, 4 ) === "http") ? "<a target='_blank' href='{$val}'>{$val}</a>" : $val;
		$result .= '</td>';

		$result .= '<td>';
		$val = $this->getChaneValueDetail($awaitingChangesEntity->getTabulka(), $awaitingChangesEntity->getSloupec(), $awaitingChangesEntity->getPozadovanaHodnota(), $awaitingChangesEntity->getCID(), $currentLang);
		$result .= (substr($val, 0, 4 ) === "http") ? "<a target='_blank' href='{$val}'>{$val}</a>" : $val;
		$result .= '</td>';

		return $result;
	}

	/**
	 * @param string $tabulka
	 * @param string $sloupec
	 * @param string $hodnota
	 * @param int $cid
	 * @param string $lang
	 * @return string
	 */
	private function getChaneValueDetail($tabulka, $sloupec, $hodnota, $cid, $lang) {
		$result = "";
		if ($tabulka == self::TBL_DOG_HEALTH_NAME) {	// tabulku zdravi osetrime jinak
			if (($sloupec == self::TBL_CLM_HEALTH_VET) && ($hodnota != null)) {
				$vet = $this->vetRepository->getVet($hodnota);
				$result .= trim($vet->getTitulyPrefix() . " " . $vet	->getJmeno() . " " . $vet->getPrijmeni() . " " . $vet->getTitulySuffix());
			} else if (($sloupec == self::TBL_CLM_HEALTH_DATE) && ($hodnota != null)) {
				$date = new DateTime($hodnota);
				$result .= $date->format(DogHealthEntity::MASKA_DATA);
			} else {
				$result .= $hodnota;
			}
		} else if (($tabulka == self::TBL_BREEDER_NAME) || ($tabulka == self::TBL_OWNER_NAME)) {
			$userAs = $this->userRepository->getUser($hodnota);
			if ($userAs != null) {
				$result .= trim($userAs->getTitleBefore() . " " . $userAs->getName() . " " . $userAs->getSurname() . " " . $userAs->getTitleAfter());
			}
		} else {
			if ($cid == null) {    // pes a není číselník
				if ((($sloupec == self::TBL_CLM_DOG_BIRTH) || ($sloupec == self::TBL_CLM_DOG_DEATH)) && ($hodnota != null)) {
					$date = new DateTime($hodnota);
					$result .= $date->format(DogEntity::MASKA_DATA);
				} else {
					$result .= $hodnota;
				}
			} else {    // je číselník
				$result .= $this->enumerationRepository->findEnumItemByOrder($lang, $hodnota);
			}
		}

		return $result;
	}
}
