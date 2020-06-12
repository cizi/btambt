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
     * @param string $name
     * @param string $surname
     * @return UserEntity
     */
    public function getUserByNameSurname($name, $surname) {
        $query = ["select * from user where name = %s and surname = %s", $name, $surname];
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
            $majitele = $this->findDogOwnersAsEntitiesUserById($id);
            $chovatele = $this->findDogBreedersAsEntitiesUserById($id);
            if ((count($majitele) == 0) && (count($chovatele) == 0)) {
                $query = ["delete from user where id = %i", $id];
                $return = ($this->connection->query($query) == 1 ? true : false);
            }
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
		$query = ["select `id`,`title_before`,`name`,`surname`,`title_after` from user"];
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
	 * @return DogOwnerEntity[]
	 */
	public function findDogOwnersAsEntitiesUserById($uID) {
		$owners = [];
		$query = ["select * from appdata_majitel where uID = %i", $uID];
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
	 * @return DogBreederEntity[]
	 */
	public function findDogBreedersAsEntitiesUserById($uID) {
		$breeders = [];
		$query = ["select * from appdata_chovatel where uID = %i", $uID];
		$result = $this->connection->query($query);

		foreach ($result->fetchAll() as $row) {
			$dogBreederEntity = new BreederEntity();
			$dogBreederEntity->hydrate($row->toArray());
			$breeders[] = $dogBreederEntity;
		}

		return $breeders;
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
    

    public function migrateUserFromOldStructure() {
        $this->connection->query("SET sql_mode = ''");
        $defaultPass = "geneAheslo";
        $fakeEmailCounter = 0;
        $tables = ["gene002", "gene003"];
        $usersCreated = 0;
        foreach ($tables as $table) {
            $query = "select ID, Majitel, mAdresa, mTelefon, mMail, mWww, Heslo from {$table}";
            $users = $this->connection->query($query);
            foreach ($users->fetchAll() as $user) {
                if (empty(trim($user["mMail"]))) {
                    $fakeEmailCounter++;
                    $email = "unknow_{$fakeEmailCounter}@email.cz";
                } else {
                    $email = trim(preg_replace('/\s+/', '', $user["mMail"]));
                }

                if (empty(trim($user["Heslo"]))) {
                    $fakeEmailCounter++;
                    $heslo = $defaultPass;
                } else {
                    $heslo = trim($user["Heslo"]);
                }
                $celeJmenoArr = explode(" ", $user["Majitel"]);
                $jmeno = (!empty($celeJmenoArr[0]) ? trim($celeJmenoArr[0]) : "");
                $jmeno = \str_replace(",", "", $jmeno);
                $celeJmenoArr[0] = "";

                $prijmeni = (!empty($celeJmenoArr[1]) ? trim($celeJmenoArr[1]) : "");
                $prijmeni = implode(" ", $celeJmenoArr); //  \str_replace(",", "", $prijmeni);

                if (!empty($jmeno) && (!empty($prijmeni))) {
                    if (empty($this->getUserByNameSurname($jmeno, $prijmeni))) {
                        $userByEmail = $this->getUserByEmail($email);
                        if ($userByEmail != null) {
                            $fakeEmailCounter++;
                            $email = $email."_duplikát".$fakeEmailCounter;
                        }
                        $this->createUser($jmeno, $prijmeni, $user["mAdresa"], $user["mTelefon"], $email, $user["mWww"], $heslo);
                        $usersCreated++;
                    }
                }
            }
        }
        echo "Bylo vytvoreno {$usersCreated} uživatelů";
    }

    public function createUser($jmeno, $prijmeni, $mAdresa, $mTelefon, $mMail, $mWww, $mHeslo) {
        $userState = "CZECH_REPUBLIC";
        $sharing = 32;
        $role = 33;
        try {
            $newUserData = [
                'email' => $mMail,
                'password' => $mHeslo,
                'role' => $role,
                'active' => 1,
                'register_timestamp' => date('Y-m-d H:i:s'),
                'last_login' => '0000-00-00 00:00:00',
                'title_before' => NULL,
                'name' => $jmeno,
                'surname' => $prijmeni,
                'title_after' => NULL,
                'street' => $mAdresa,
                'city' => NULL,
                'zip' => NULL,
                'state' => $userState,
                'web' => $mWww,
                'phone' => $mTelefon,
                'fax' => NULL,
                'station' => NULL,
                'sharing' => $sharing,
                'news' => 1,
                'breed' => NULL,
                'deleted' => 0,
                'club' => NULL,
                'clubNo' => NULL
            ];
            $userEntity = new UserEntity();
            $userEntity->hydrate($newUserData);
            $userEntity->setPassword(Passwords::hash($userEntity->getPassword()));
            $query = ["insert into user ", $userEntity->extract()];
            $this->connection->query($query);
        } catch (Exception $e) {
            dump($e); die;
        }

        return $this->connection->getInsertId();
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