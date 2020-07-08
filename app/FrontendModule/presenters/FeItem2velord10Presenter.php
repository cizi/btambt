<?php

namespace App\FrontendModule\Presenters;

use App\Forms\UserChangePasswordForm;
use App\Model\Entity\UserEntity;
use App\Model\UserRepository;
use Nette\Application\AbortException;
use Nette\Security\Passwords;
use Nette\Application\UI\Form;

class FeItem2velord10Presenter extends FrontendPresenter {

	/** @var UserChangePasswordForm */
	private $userChangePasswordForm;

	/** @var UserRepository */
	private $userRepository;

	public function __construct(UserChangePasswordForm $userChangePasswordForm, UserRepository $userRepository) {
		$this->userChangePasswordForm = $userChangePasswordForm;
		$this->userRepository = $userRepository;
	}

	public function startup() {
		parent::startup();
		if ($this->user->isLoggedIn() == false) {	// pokud nejsem přihlášen tak nemám co měnit -> tedy login
			$this->redirect(BasePresenter::PRESENTER_PREFIX . "1" . BasePresenter::LEVEL_ORDER_DELIMITER. "14:default");
		}
    }

	/**
	 * Vytvori komponentu pro zmen hesla uzivatele
	 */
	public function createComponentChangePasswordForm() {
		$form = $this->userChangePasswordForm->create();
        $form->onSubmit[] = [$this, 'updatePassword'];

        return $form;
    }

	/**
	 * Zvaliduje formular zmeny hesla
	 * @param Form $form $form
	 */
	public function updatePassword(Form $form) {
		try {
            $values = $form->getValues();
            $userEntity = $this->userRepository->getUser($this->user->getId());
        
			if (!Passwords::verify($values['passwordCurrent'], $userEntity->getPassword())) {
				$this->flashMessage(USER_EDIT_CURRENT_PASSWORD_FAILED, "alert-danger");
				$form->addError(USER_EDIT_CURRENT_PASSWORD_FAILED);
			} elseif ((trim($values['passwordNewConfirm']) == "") || (trim($values['passwordNew']) == "")) {
				$this->flashMessage(USER_EDIT_PASSWORDS_EMPTY, "alert-danger");
				$form->addError(USER_EDIT_PASSWORDS_EMPTY);
			} elseif (trim($values['passwordNewConfirm']) != trim($values['passwordNew'])) {
				$this->flashMessage(USER_EDIT_PASSWORDS_DOESNT_MATCH, "alert-danger");
				$form->addError(USER_EDIT_PASSWORDS_DOESNT_MATCH);
			} else {
				if ($this->userRepository->changePassword($this->user->getId(), $values['passwordNew'])) {
					$this->flashMessage(USER_EDIT_PASSWORD_CHANGED, "alert-success");
					$this->redirect("default");
				} else {
					$this->flashMessage(USER_EDIT_SAVE_FAILED, "alert-danger");
					$form->addError(USER_EDIT_SAVE_FAILED);
				}
			}
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				$this->flashMessage(USER_EDIT_SAVE_FAILED, "alert-danger");
				$form->addError(USER_EDIT_SAVE_FAILED);
			}
        }
    }
}