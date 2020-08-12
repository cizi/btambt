<?php

namespace App\Model\Entity;

use Dibi\DateTime;

class UserEntity {

	const BREED_DELIMITER = "~";

	/** @var int */
	private $id;

	/** @var string */
	private $email;

	/** @var string */
	private $password;

	/** @var int */
	private $role;

	/** @var bool */
	private $active;

	/** @var string  */
	private $lastLogin;

	/** @var string  */
	private $registerTimestamp;

	/** @var string */
	private $titleBefore;

	/** @var string */
	private $name;

	/** @var string */
	private $surname;

	/** @var string */
	private $titleAfter;

	/** @var string */
	private $street;

	/** @var string */
	private $city;

	/** @var int */
	private $zip;

	/** @var string */
	private $state;

	/** @var string */
	private $web;

	/** @var string */
	private $phone;

	/** @var string */
	private $fax;

	/** @var string */
	private $station;

	/** @var int */
	private $sharing;

	/** @var int */
	private $breed;

	/** @var bool  */
	private $news;

	/** @var int */
	private $club;

	/** @var string */
	private $clubNo;

	/** @var bool */
	private $deleted;

	/** @var bool */
	private $privacy;

	/** @var int */
	private $privacyTriesCount;


	/**
	 * @return string
	 */
	public function getTitleBefore() {
		return $this->titleBefore;
	}

	/**
	 * @param string $titleBefore
	 */
	public function setTitleBefore($titleBefore) {
		$this->titleBefore = $titleBefore;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getSurname() {
		return $this->surname;
	}

	/**
	 * @param string $surname
	 */
	public function setSurname($surname) {
		$this->surname = $surname;
	}

	/**
	 * @return string
	 */
	public function getTitleAfter() {
		return $this->titleAfter;
	}

	/**
	 * @param string $titleAfter
	 */
	public function setTitleAfter($titleAfter) {
		$this->title_after = $titleAfter;
	}

	/**
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @param string $street
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param string $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @return int
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * @param int $zip
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * @return string
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param string $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @return string
	 */
	public function getWeb() {
		return $this->web;
	}

	/**
	 * @param string $web
	 */
	public function setWeb($web) {
		$this->web = $web;
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @return string
	 */
	public function getFax() {
		return $this->fax;
	}

	/**
	 * @param string $fax
	 */
	public function setFax($fax) {
		$this->fax = $fax;
	}

	/**
	 * @return int
	 */
	public function getStation() {
		return $this->station;
	}

	/**
	 * @param int $station
	 */
	public function setStation($station) {
		$this->station = $station;
	}

	/**
	 * @return int
	 */
	public function getSharing() {
		return $this->sharing;
	}

	/**
	 * @param int $sharing
	 */
	public function setSharing($sharing) {
		$this->sharing = $sharing;
	}

	/**
	 * @return int
	 */
	public function getBreed() {
		return $this->breed;
	}

	/**
	 * @param int $breed
	 */
	public function setBreed($breed) {
		$this->breed = $breed;
	}

	/**
	 * @return boolean
	 */
	public function isDeleted() {
		return $this->deleted;
	}

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getLastLogin() {
		return $this->lastLogin;
	}

	/**
	 * @param string $lastLogin
	 */
	public function setLastLogin($lastLogin) {
		$this->lastLogin = $lastLogin;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $login
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return int
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param int $role
	 */
	public function setRole($role) {
		$this->role = $role;
	}

	/**
	 * @return boolean
	 */
	public function isActive() {
		return $this->active;
	}

	/**
	 * @param boolean $active
	 */
	public function setActive($active) {
		$this->active = $active;
	}

	/**
	 * @return DateTime
	 */
	public function getRegisterTimestamp() {
		return $this->registerTimestamp;
	}

	/**
	 * @param DateTime $registerTimestamp
	 */
	public function setRegisterTimestamp($registerTimestamp) {
		$this->registerTimestamp = $registerTimestamp;
	}

	/**
	 * @return int
	 */
	public function getClub() {
		return $this->club;
	}

	/**
	 * @param int $club
	 */
	public function setClub($club) {
		$this->club = $club;
	}

	/**
	 * @return string
	 */
	public function getClubNo() {
		return $this->clubNo;
	}

	/**
	 * @param string $clubNo
	 */
	public function setClubNo($clubNo) {
		$this->clubNo = $clubNo;
	}

	/**
	 * @return boolean
	 */
	public function isNews() {
		return $this->news;
	}

	/**
	 * @param boolean $news
	 */
	public function setNews($news) {
		$this->news = $news;
	}

	/**
	 * @return bool
	 */
	public function isPrivacy() {
		return $this->privacy;
	}
	/**
	 * @param bool $privacy
	 */
	public function setPrivacy($privacy) {
		$this->privacy = $privacy;
	}
	/**
	 * @return int
	 */
	public function getPrivacyTriesCount() {
		return $this->privacyTriesCount;
	}
	/**
	 * @param int $privacyTriesCount
	 */
	public function setPrivacyTriesCount($privacyTriesCount) {
		$this->privacyTriesCount = $privacyTriesCount;
	}

	/**
	 * Celé jméno uživatele ořezané o případné tituly apod.
	 * @return string
	 */
	public function getFullName() {
		return trim($this->getTitleBefore() . " " . $this->getName() . " " . $this->getSurname() . " " . $this->getTitleAfter());
    }
    
    /**
	 * Celá adresa uživatele ořezaná o případné mezery
	 * @return string
	 */
	public function getFullAddress() {
		return trim($this->getStreet() . " " . $this->getCity() . " " . $this->getZip()); // . " " . $this->getState());
    }

	/**
	 * @param array $data
	 */
	public function hydrate(array $data) {
		$this->id = (isset($data['id']) ? $data['id'] : null);
		$this->email = (isset($data['email']) ? $data['email']: null);
		$this->password = (isset($data['password']) ? $data['password'] : null);
		$this->role = (isset($data['role']) ? $data['role'] : null);
		$this->active = (isset($data['active']) ? $data['active'] : null);
		$this->lastLogin = (isset($data['last_login']) ? $data['last_login'] : null);
		$this->registerTimestamp = (isset($data['register_timestamp']) ? $data['register_timestamp'] : null);
		$this->titleBefore = (isset($data['title_before']) ? $data['title_before'] : null);
		$this->name = (isset($data['name']) ? $data['name'] : null);
		$this->surname = (isset($data['surname']) ? $data['surname'] : null);
		$this->titleAfter = (isset($data['title_after']) ? $data['title_after'] : null);
		$this->street = (isset($data['street']) ? $data['street'] : null);
		$this->city = (isset($data['city']) ? $data['city'] : null);
		$this->zip = (isset($data['zip']) ? $data['zip'] : null);
		$this->state = (isset($data['state']) ? $data['state'] : null);
		$this->web = (isset($data['web']) ? $data['web'] : null);
		$this->phone = (isset($data['phone']) ? $data['phone'] : null);
		$this->fax = (isset($data['fax']) ? $data['fax'] : null);
		$this->station = (isset($data['station']) ? $data['station'] : null);
		$this->sharing = (isset($data['sharing']) ? $data['sharing'] : null);
		$this->breed = (isset($data['breed']) ? $data['breed'] : null);
		$this->news = (!empty($data['news']) ? 1 : 0);
		$this->club = (isset($data['club']) ? $data['club'] : null);
		$this->clubNo = (isset($data['clubNo']) ? $data['clubNo'] : null);
		$this->deleted = (isset($data['deleted']) ? $data['deleted'] : 0);
		$this->privacy = (!empty($data['privacy']) ? 1 : 0);
		$this->privacyTriesCount = (isset($data['privacy_tries_count']) ? $data['privacy_tries_count'] : 0);
	}

	/**
	 * @return array
	 */
	public function extract() {
		return [
			'id' => $this->id,
			'email' => $this->email,
			'password' => $this->password,
			'role' => $this->role,
			'active' => $this->active,
			'last_login' => $this->lastLogin,
			'register_timestamp' => $this->registerTimestamp,
			'title_before' => $this->getTitleBefore(),
			'name' => $this->getName(),
			'surname' => $this->getSurname(),
			'title_after' => $this->getTitleAfter(),
			'street' => $this->getStreet(),
			'city' => $this->getCity(),
			'zip' => $this->getZip(),
			'state' => $this->getState(),
			'web' => $this->getWeb(),
			'phone' => $this->getPhone(),
			'fax' => $this->getFax(),
			'station' => $this->getStation(),
			'sharing' => $this->getSharing(),
			'breed' => $this->getBreed(),
			'news' => $this->isNews(),
			'club' => $this->getClub(),
			'clubNo' => $this->getClubNo(),
			'deleted' => $this->isDeleted(),
			'privacy' => $this->isPrivacy(),
			'privacy_tries_count' => $this->getPrivacyTriesCount()
		];
	}
}