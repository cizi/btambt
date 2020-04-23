<?php

namespace App\FrontendModule\Presenters;

use App\Forms\SignForm;
use App\Model\UserRepository;
use Nette\Forms\Form;

// LOGIN
class FeItem1velord14Presenter extends FrontendPresenter {

	/** @var SignForm */
	private $signInForm;

	/** @var UserRepository */
	private $userRepository;

	/**
	 * @param SignForm $signForm
	 * @param UserRepository $userRepository
	 */
	public function __construct(SignForm $signForm, UserRepository $userRepository) {
		$this->signInForm = $signForm;
		$this->userRepository = $userRepository;
	}

	public function startup() {
		parent::startup();

		if ($this->user->isLoggedIn()) {
			$this->redirect("Homepage:default");
		}
	}

	/**
	 * Sign-in form factory.
	 * @return Form
	 */
	public function createComponentSignInForm(){
		$form = $this->signInForm->create();

		$langs = $this->langRepository->findLanguages();
		if (count($langs) == 0) {
			$form['lang']->setAttribute("style", "display: none");
		} else {
			$form['lang']->setItems($langs);
		}

		$form->onSubmit[] = [$this, 'formSucceeded'];

		return $form;
	}

	/**
	 * @param Form $form
	 * @param $values
	 */
	public function formSucceeded(Form $form) {
		$values = $form->getHttpData();
		if (isset($values['remember'])) {
			$this->user->setExpiration('14 days', false);
		} else {
			$this->user->setExpiration('20 minutes', true);
		}

		try {
			$credentials = ['email' => $values['login'], 'password' => $values['password']];
			$identity = $this->user->getAuthenticator()->authenticate($credentials);
			$this->user->login($identity);
			$this->userRepository->updateLostLogin($identity->getId());

			$this->flashMessage(ADMIN_LOGIN_SUCCESS, "alert-success");
			$userEntity = $this->userRepository->getUser($identity->getId());
			if ($userEntity->isPrivacy() == false) {
				$this->userRepository->updatePrivacyTriesCount($identity->getId());
				$this->flashMessage(ADMIN_LOGIN_SUCCESS_NO_PRIVACY, "alert-warning");
				$this->redirect("FeItem2velord9:default");
			} else {
				$this->redirect("Homepage:default");
			}
		} catch (\Nette\Security\AuthenticationException $e) {
			$this->flashMessage(ADMIN_LOGIN_FAILED, "alert-danger");
			$form->addError(ADMIN_LOGIN_FAILED);
		}
	}
}