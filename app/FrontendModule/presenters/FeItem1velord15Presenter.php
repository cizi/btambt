<?php

namespace App\FrontendModule\Presenters;

// PRESENTER REGISTRACE UŽIVATELE
use App\Controller\EmailController;
use App\Enum\UserRoleEnum;
use App\Forms\UserForm;
use App\Model\Entity\UserEntity;
use App\Model\UserRepository;
use App\Model\WebconfigRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use Nette\Security\Passwords;

class FeItem1velord15Presenter extends FrontendPresenter {

	/** @var UserForm */
	private $userForm;

	/** @var UserRepository */
	private $userRepository;

	public function __construct(UserForm $userForm, UserRepository $userRepository) {
		$this->userForm = $userForm;
		$this->userRepository = $userRepository;
	}

	public function startup() {
		parent::startup();

		if ($this->user->isLoggedIn()) {
			$this->redirect("Homepage:default");
		}
	}

	public function renderDefault($id) {
		$this['editForm']['privacy']->setAttribute("class", "tinym_required_field");
		$this['editForm']['privacy']->setAttribute("validation", USER_EDIT_PRIVACY_VALIDATION);
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

	public function saveUser(Form $form) {
		$values = $form->getHttpData();
		$userEntity = new UserEntity();
		$userEntity->hydrate($values);

		$userEntity->setRole(UserRoleEnum::USER_REGISTERED);
		$userEntity->setActive(true);
		$userEntity->setDeleted(false);
		$userEntity->setPassword(Passwords::hash($userEntity->getPassword()));

		$breeds = ((isset($values['breed']) && $values['breed'] != 0) ? implode($values['breed'], UserEntity::BREED_DELIMITER) : NULL);
		$userEntity->setBreed($breeds);

		try {
			if ((trim($values['passwordConfirm']) == "") || (trim($values['password']) == "")) {
				$this->flashMessage(USER_EDIT_PASSWORDS_EMPTY, "alert-danger");
				$form->addError(USER_EDIT_PASSWORDS_EMPTY);
			} elseif (trim($values['passwordConfirm']) != trim($values['password'])) {
				$this->flashMessage(USER_EDIT_PASSWORDS_DOESNT_MATCH, "alert-danger");
				$form->addError(USER_EDIT_PASSWORDS_DOESNT_MATCH);
			} elseif ($this->userRepository->getUserByEmail($values['email']) == null) {
				$this->userRepository->saveUser($userEntity);
				if (isset($values['id']) && $values['id'] != "") {
					$this->flashMessage(USER_EDITED, "alert-success");
				} else {
					$emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
					$subject = USER_CREATED_MAIL_SUBJECT;
					$body = sprintf(USER_CREATED_MAIL_BODY, $this->getHttpRequest()->getUrl()->getBaseUrl(), $userEntity->getEmail(), $values['password']);
					EmailController::SendPlainEmail($emailFrom, $userEntity->getEmail(), $subject, $body);
					$this->flashMessage(USER_ADDED, "alert-success");
				}
				$this->redirect($this->getPresenterLink(1, 14) . ":default");
			} else {
				$this->flashMessage(USER_EMAIL_ALREADY_EXISTS, "alert-danger");
				$form->addError(USER_EMAIL_ALREADY_EXISTS);
			}
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				// dump($e->getMessage(), $userEntity); die;
				$this->flashMessage(USER_EDIT_SAVE_FAILED, "alert-danger");
				$form->addError(USER_EDIT_SAVE_FAILED);
			}
		}
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