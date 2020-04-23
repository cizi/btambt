<?php

namespace App\Model;

use App\Enum\StateEnum;
use App\Enum\UserRoleEnum;
use App\Model\Entity\BreederEntity;
use App\Model\Entity\DogOwnerEntity;
use Nette;
use App\Model\Entity\UserEntity;
use Nette\Security\Passwords;

class UserRepository extends BaseRepository implements Nette\Security\IAuthenticator {

	/**  */
	const USER_CURRENT_PAGE = "user_current_page";

	const USER_SEARCH_FIELD = "user_search_field";

	const PASSWORD_COLUMN = 'password';

	/** string znak pro nevybraného veterinář v selectu  */
	const NOT_SELECTED = "-";

	/**
	 * Performs an authentication.
	 *
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials) {
		$email = (isset($credentials['email']) ? $credentials['email'] : "");
		$password = (isset($credentials['password']) ? $credentials['password'] : "");

		$query = ["select * from user where email = %s", $email, " and active = 1"];
		$row = $this->connection->query($query)->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!Passwords::verify($password, $row[self::PASSWORD_COLUMN])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		$userEntity = new UserEntity();
		$userEntity->hydrate($row->toArray());

		$arr = $row->toArray();
		unset($arr[self::PASSWORD_COLUMN]);

		return new Nette\Security\Identity($userEntity->getId(), $userEntity->getRole(), $arr);
	}

	/**
	 * @return UserEntity[]
	 */
	public function findUsers(Nette\Utils\Paginator $paginator, $filter) {
		if ($filter != null) {
			$dbDriver = $this->connection->getDriver();
			$query = ["select * from user where (CONCAT_WS(' ', `name`, `surname`, `email`) like %~like~) limit %i , %i", $filter, $paginator->getOffset(), $paginator->getLength()];

		} else {
			$query = ["select * from user limit %i , %i", $paginator->getOffset(), $paginator->getLength()];
		}
		$result = $this->connection->query($query);

		$users = [];
		foreach ($result->fetchAll() as $row) {
			$user = new UserEntity();
			$user->hydrate($row->toArray());
			$users[] = $user;
		}

		return $users;
	}

	/**
	 * @param int $id
	 * @return UserEntity
	 */
	public function getUser($id) {
		$query = ["select * from user where id = %i", $id];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$userEntity = new UserEntity();
			$userEntity->hydrate($row->toArray());
			return $userEntity;
		}
	}

	/**
	 * @param int $id
	 * @return UserEntity
	 */
	public function getUserByEmail($email) {
		$query = ["select * from user where email = %s", $email];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$userEntity = new UserEntity();
			$userEntity->hydrate($row->toArray());
			return $userEntity;
		}
	}

	/**
	 * @param int $id
	 * @return UserEntity
	 */
	public function resetUserPassword(UserEntity $userEntity) {
		$input = 'abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$password = '';
		for ($i = 0; $i < 8; $i++) {
			$password .= $input[mt_rand(0, 60)];
		}

		$query = [
			"update user set password = %s where email = %s",
			Passwords::hash($password),
			$userEntity->getEmail()
		];
		$this->connection->query($query);

		return $password;
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function deleteUser($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from user where id = %i", $id];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}

		return $return;
	}

	/**
	 * @param UserEntity $userEntity
	 */
	public function saveUser(UserEntity $userEntity) {
		if ($userEntity->getId() == null) {
			$userEntity->setLastLogin('0000-00-00 00:00:00');
			$userEntity->setRegisterTimestamp(date('Y-m-d H:i:s'));
			$query = ["insert into user ", $userEntity->extract()];
		} else {
			$updateArray = $userEntity->extract();
			unset($updateArray['id']);
			unset($updateArray['register_timestamp']);
			unset($updateArray['last_login']);
			$query = ["update user set ", $updateArray, "where id=%i", $userEntity->getId()];
		}

		$this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @param string $newPassString
	 * @return \Dibi\Result|int
	 */
	public function changePassword($id, $newPassString) {
		$newPassHashed = Passwords::hash($newPassString);
		$query = ["update user set password = %s where id = %i", $newPassHashed, $id];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function setUserActive($id) {
		$query = ["update user set active = 1 where id = %i", $id];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function setUserInactive($id) {
		$query = ["update user set active = 0 where id = %i", $id];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function updateLostLogin($id) {
		$query = ["update user set last_login = NOW() where id = %i", $id];
		return $this->connection->query($query);
	}

	/**
	 * @return array
	 */
	public function findBreedersForSelect() {
		$breeders[0] = self::NOT_SELECTED;
		$query = ["select `id`,`title_before`,`name`,`surname`,`title_after` from user"]; // where role in %in", [UserRoleEnum::USER_BREEDER, UserRoleEnum::USER_ROLE_ADMINISTRATOR]];
		$result = $this->connection->query($query);

		foreach ($result->fetchAll() as $row) {
			$user = $row->toArray();
			$breeders[$user['id']] = trim($user['title_before'] . " " . $user['name'] . " " . $user['surname'] . " " . $user['title_after']);
		}

		return $breeders;
	}

	/**
	 * @param int $pID
	 * @return BreederEntity
	 */
	public function getBreederByDog($pID) {
		$query = ["select * from appdata_chovatel where pID = %i", $pID];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$breederEntity = new BreederEntity();
			$breederEntity->hydrate($row->toArray());
			return $breederEntity;
		}
	}

	/**
	 * @param int $pID
	 * @return bool
	 */
	public function deleteOwner($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_majitel where ID = %i", $id];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}
		return $return;
	}

	/**
	 * @param int $pID
	 * @return bool
	 */
	public function deleteBreeder($id) {
		$return = false;
		if (!empty($id)) {
			$query = ["delete from appdata_chovatel where ID = %i", $id];
			$return = ($this->connection->query($query) == 1 ? true : false);
		}
		return $return;
	}

	/**
	 * @param int $pID
	 * @return UserEntity
	 */
	public function getBreederByDogAsUser($pID) {
		$query = ["select *, u.id as id from appdata_chovatel as ac left join `user` as u on ac.uID = u.id where pID = %i", $pID];
		$row = $this->connection->query($query)->fetch();
		if ($row) {
			$userEntity = new UserEntity();
			$userEntity->hydrate($row->toArray());
			return $userEntity;
		}
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function updatePrivacyTriesCount($id) {
		$query = ["update user set privacy_tries_count = privacy_tries_count + 1 where id = %i", $id];
		return $this->connection->query($query);
	}

	/**
	 * @param int $pID
	 * @return DogOwnerEntity[]
	 */
	public function findDogOwnersAsEntities($pID) {
		$owners = [];
		$query = ["select * from appdata_majitel where pID = %i and Soucasny = %i", $pID, 1];
		$result = $this->connection->query($query);

		foreach ($result->fetchAll() as $row) {
			$dogOwnerEntity = new DogOwnerEntity();
			$dogOwnerEntity->hydrate($row->toArray());
			$owners[] = $dogOwnerEntity;
		}

		return $owners;
	}

	/**
	 * @param int $pID
	 * @return array
	 */
	public function findDogOwners($pID) {
		$owners = [];
		$query = ["select * from appdata_majitel where pID = %i and Soucasny = %i", $pID, 1];
		$result = $this->connection->query($query);

		foreach ($result->fetchAll() as $row) {
			$dogOwnerEntity = new DogOwnerEntity();
			$dogOwnerEntity->hydrate($row->toArray());
			$owners[] = $dogOwnerEntity->getUID();
		}

		return $owners;
	}

	/**
	 * @param int $pID
	 * @return UserEntity[]
	 */
	public function findDogOwnersAsUser($pID) {
		$users = [];
		$query = ["select *, u.id as id from appdata_majitel as am left join `user` as u on am.uID = u.id where am.pID = %i and Soucasny = %i", $pID, 1];
		$result = $this->connection->query($query);

		foreach ($result->fetchAll() as $row) {
			$userEntity = new UserEntity();
			$userEntity->hydrate($row->toArray());
			$users[] = $userEntity;
		}

		return $users;
	}

	/**
	 * @param int $pID
	 * @return array
	 */
	public function findDogPreviousOwners($pID) {
		$owners = [];
		$query = [
			"select * from appdata_majitel as am left join user as u on am.uID = u.id where am.pID = %i and am.Soucasny = %i",
			$pID,
			0
		];
		$result = $this->connection->query($query);

		foreach ($result->fetchAll() as $row) {
			$user = new UserEntity();
			$user->hydrate($row->toArray());
			$owners[] = $user;
		}

		return $owners;
	}

	/**
	 * @return array
	 */
	public function findOwnersForSelect() {
		$owners = [];
		$query = ["select * from user"]; //where, UserRoleEnum::USER_OWNER];
		$result = $this->connection->query($query);

		foreach ($result->fetchAll() as $row) {
			$user = new UserEntity();
			$user->hydrate($row->toArray());
			$name = trim($user->getTitleBefore() . " " . $user->getName() . " " . $user->getSurname() . " " . $user->getTitleAfter());
			$name = (strlen($name) > 60 ? substr($name, 0, 60) . "..." : $name);
			$owners[$user->getId()] = $name;
		}

		return $owners;
	}

	public function getUsersCount($filter) {
		if ($filter != null) {
			$dbDriver = $this->connection->getDriver();
			$query = ["select count(id) as pocet from user where (CONCAT_WS(' ', `name`, `surname`, `email`) like %~like~)", $filter];
		} else {
			$query = "select count(id) as pocet from user";
		}
		$row = $this->connection->query($query);

		return ($row ? $row->fetch()['pocet'] : 0);
	}

	/**
	 * @return array
	 */
	public function migrateUserFromOldStructure() {
		$defaultPass = "geneAheslo";
		$result['zmigrováno'] = 0;
		$result['duplicita'] = [];
		$result['chyba'] = [];
		$result['prazdne_emaily'] = [];
		$fakeEmailCounter = 1;
		$query = "select * from uzivatel";
		$users = $this->connection->query($query);
		foreach ($users->fetchAll() as $user) {
			// flagy co vše se mohlo po...
			$emptyEmail = false;
			$duplicateEmail = false;
			try {
				$role = UserRoleEnum::USER_BREEDER;
				if ($user['Editor'] == 1) {
					$role = UserRoleEnum::USER_EDITOR;
				} else {
					if ($user['Administrator'] == 1) {
						$role = UserRoleEnum::USER_ROLE_ADMINISTRATOR;
					}
				}

				$states = new StateEnum();
				$state = array_keys($states->arrayKeyValue());
				if (isset($state[$user['Stat'] - 1])) {
					$userState = $state[$user['Stat'] - 1];
				} else {
					$userState = "CZECH_REPUBLIC";
				}

				if ((trim($user['Email']) == "")) {
					$emptyEmail = true;
					$emailAddress = "unknow_{$fakeEmailCounter}@email.cz";
					$fakeEmailCounter++;
				} else {
					$emailAddress = $user['Email'];
				}

				/** @var UserEntity $userByEmail */
				$userByEmail = $this->getUserByEmail($emailAddress);
				while ($userByEmail != null){
					$emailAddress = "migracniPrefix_" . $fakeEmailCounter . $userByEmail->getEmail();
					$fakeEmailCounter++;
					$duplicateEmail = true;
					$userByEmail = $this->getUserByEmail($emailAddress) != null;
				}

				switch ($user['Sdileni']) {
					case 1:
						$sharing = 31;
						break;
					case 2:
						$sharing = 32;
						break;
					case 3:
						$sharing = 33;
						break;
					case 4:
						$sharing = 34;
						break;
					default:
						$sharing = null;
						break;
				}

				switch ($user['Klub']) {
					case 1:
						$klub = 82;
						break;

					case 2:
						$klub = 83;
						break;

					case 3:
						$klub = 85;
						break;

					case 4:
						$klub = 84;
						break;

					case 5:
						$klub = 86;
						break;

					case 6:
						$klub = 87;
						break;

					case 7:
						$klub = 88;
						break;

					default:
						$klub = null;
						break;
				}

				$newUserData = [
					'id' => $user['ID'],
					'email' => $emailAddress,
					'password' => (trim($user['Password']) == "" ? $defaultPass : trim($user['Password'])),
					'role' => $role,
					'active' => 1,
					'register_timestamp' => date('Y-m-d H:i:s'),
					'last_login' => '0000-00-00 00:00:00',
					'title_before' => $user['TitulyPrefix'],
					'name' => $user['Jmeno'],
					'surname' => $user['Prijmeni'],
					'title_after' => $user['TitulySuffix'],
					'street' => $user['Ulice'],
					'city' => $user['Mesto'],
					'zip' => $user['PSC'],
					'state' => $userState,
					'web' => $user['Www'],
					'phone' => $user['Telefon'],
					'fax' => $user['Fax'],
					'station' => $user['CHS'],
					'sharing' => $sharing,
					'news' => $user['News'],
					'breed' => NULL,
					'deleted' => 0,
					'club' => $klub,
					'clubNo' => $user['KlubCislo']
				];
				$userEntity = new UserEntity();
				$userEntity->hydrate($newUserData);
				$userEntity->setPassword(Passwords::hash($userEntity->getPassword()));
				$query = ["insert into user ", $userEntity->extract()];
				$this->connection->query($query);
			} catch (\Exception $ex) {
				$result['chyba'][] = (isset($newUserData) ? implode(";", $newUserData) . "; " . $ex->getMessage() : "");
			}
			// zápis stavu migrace
			if ($emptyEmail) {
				$result['prazdne_emaily'][] = (isset($newUserData) ? implode(";", $newUserData) : "");
			} else if ($duplicateEmail) {
				$result['duplicita'][] = (isset($newUserData) ? implode(";", $newUserData) : "");
			} else {
				$result['zmigrováno'] = $result['zmigrováno'] + 1;	//
			}
		}

		try {
			$admin = new UserEntity();
			$alreadyExist = $this->getUserByEmail('cizi@email.cz');
			if ($alreadyExist != null) {
				$admin->setId($alreadyExist->getId());
				$admin->setEmail($alreadyExist->getEmail());
			} else {
				$admin->setEmail('cizi@email.cz');
			}

			$admin->setPassword(Passwords::hash("kreslo"));
			$admin->setRole(UserRoleEnum::USER_ROLE_ADMINISTRATOR);
			$admin->setActive(1);
			$admin->setRegisterTimestamp(date('Y-m-d H:i:s'));
			$admin->setName("Jan");
			$admin->setSurname("Cimler");
			$admin->setStreet("Studánkova 4");
			$admin->setCity("elHomo");
			$admin->setZip("37001");
			$admin->setStreet("CZECH_REPUBLIC");
			$admin->setWeb("cizinet.cz");
			$admin->setLastLogin('0000-00-00 00:00:00');
			$this->saveUser($admin);
		} catch (\Exception $ex) {
			$result['chyba'][] = (isset($newUserData) ? implode(";", $newUserData) . "; " . $ex->getMessage() : "");
		}
		$this->connection->query("RENAME TABLE uzivatel TO migrated_uzivatel");

		return $result;
	}

	/**
	 * Vrátí uživatele, kteří mají nastavený sharing
	 * @return array
	 */
	public function findCatteries() {
		$query = "select * from user where (trim(station) != '') and (sharing is not null) order by station";
		$result = $this->connection->query($query);

		$users = [];
		foreach ($result->fetchAll() as $row) {
			$user = new UserEntity();
			$user->hydrate($row->toArray());
			if ($user->getSharing() != null) {
				$users[] = $user;
			}
		}

		return $users;
	}
}