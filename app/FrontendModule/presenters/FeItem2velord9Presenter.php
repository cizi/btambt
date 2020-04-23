<?php

namespace App\FrontendModule\Presenters;

use App\Forms\UserForm;
use App\Model\Entity\UserEntity;
use App\Model\UserRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;

class FeItem2velord9Presenter extends FrontendPresenter {

	/** @var UserForm */
	private $userForm;

	/** @var UserRepository */
	private $userRepository;

	public function startup() {
		parent::startup();
		if ($this->user->isLoggedIn() == false) {	// pokud nejsem přihlášen tak nemám co měnit -> tedy login
			$this->redirect(BasePresenter::PRESENTER_PREFIX . "1" . BasePresenter::LEVEL_ORDER_DELIMITER. "14:default");
		}
	}

	public function __construct(UserForm $userForm, UserRepository $userRepository) {
		$this->userForm = $userForm;
		$this->userRepository = $userRepository;
	}

	public function renderDefault() {
		$userEntity = $this->userRepository->getUser($this->user->getId());
		$this->template->user = $userEntity;

		if ($userEntity) {
			$this['editForm']->addHidden('id', $userEntity->getId());
			$this['editForm']['email']->setAttribute("readonly", "readonly");

			$data = $userEntity->extract();
			$breeds = explode(UserEntity::BREED_DELIMITER, $data['breed']);
			if (empty($breeds) || empty($data['breed'])) {
				unset($data['breed']);
			} else {
				$data['breed'] = $breeds;
			}
			$this['editForm']->setDefaults($data);
		}
		unset($this['editForm']['password']);
		unset($this['editForm']['passwordConfirm']);
	}

	/**
	 * Vytvoří komponentu pro registraci uživatele
	 * @return Form
	 */
	public function createComponentEditForm() {
		$form = $this->userForm->create($this->link("default"), $this->langRepository->getCurrentLang($this->session));
		$form->onSubmit[] = [$this, 'saveUser'];

		return $form;
	}

	/**
	 * Zedituje uživatele
	 * @param Form $form
	 */
	public function saveUser(Form $form) {
		$values = $form->getHttpData();
		$userEntityCurrent = $this->userRepository->getUser($this->user->getId());

		$userEntityNew = new UserEntity();
		$userEntityNew->hydrate((array)$values);

		$breeds = ((isset($values['breed']) && $values['breed'] != 0) ? implode($values['breed'], UserEntity::BREED_DELIMITER) : NULL);
		$userEntityNew->setBreed($breeds);

		$userEntityNew->setId($userEntityCurrent->getId());
		$userEntityNew->setEmail($userEntityCurrent->getEmail());
		$userEntityNew->setRole($userEntityCurrent->getRole());
		$userEntityNew->setActive($userEntityCurrent->isActive());
		$userEntityNew->setPassword($userEntityCurrent->getPassword());

		try {
			$this->userRepository->saveUser($userEntityNew);
			if (isset($values['id']) && $values['id'] != "") {
				$this->flashMessage(USER_EDITED, "alert-success");
			} else {
				$this->flashMessage(USER_ADDED, "alert-success");
			}
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				//dump($e->getMessage(), $values); die;
				$this->flashMessage(USER_EDIT_SAVE_FAILED, "alert-danger");
			}
		}
		$this->redirect("Default");
	}


	/**
	 * @param int $id
	 */
	public function actionEdit($id) {
		$this->template->user = null;
		$userEntity = $this->userRepository->getUser($id);
		$this->template->user = $userEntity;

		if ($userEntity) {
			$this['editForm']->addHidden('id', $userEntity->getId());
			$this['editForm']['email']->setAttribute("readonly", "readonly");
			$this['editForm']->setDefaults($userEntity->extract());
		}
	}
}