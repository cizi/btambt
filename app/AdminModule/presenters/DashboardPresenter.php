<?php

namespace App\AdminModule\Presenters;

use App\Controller\DogChangesComparatorController;
use App\Controller\EmailController;
use App\Enum\UserRoleEnum;
use App\Model\AwaitingChangesRepository;
use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;
use App\Model\WebconfigRepository;

class DashboardPresenter extends SignPresenter {

	/** @var AwaitingChangesRepository */
	private $awaitingRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var DogRepository */
	private $dogRepository;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var DogChangesComparatorController */
	private $dogChangesComparatorController;

	public function __construct(
		AwaitingChangesRepository $awaitingChangesRepository,
		UserRepository $userRepository,
		DogRepository $dogRepository,
		EnumerationRepository $enumerationRepository,
		DogChangesComparatorController $dogChangesComparatorController
	) {
		$this->awaitingRepository = $awaitingChangesRepository;
		$this->userRepository = $userRepository;
		$this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->dogChangesComparatorController = $dogChangesComparatorController;
		parent::__construct();
	}

	/**
	 * Pokud nejsem admin tak tady nemám co dělat
	 */
	public function startup() {
		parent::startup();
		if (($this->getUser()->getRoles()[0] == UserRoleEnum::USER_EDITOR)) {
			$this->redirect("Referee:Default");
		}
	}

	public function actionDefault() {
		$currentLang = $this->langRepository->getCurrentLang($this->session);
		$this->template->awaitingChanges = $this->dogChangesComparatorController->generateAwaitingChangesHtml($this->presenter, $currentLang);
		$this->template->proceededChanges = []; //$this->awaitingRepository->findProceededChanges();
		$this->template->declinedChanges = []; //$this->awaitingRepository->findDeclinedChanges();

		$this->template->currentLang = $this->langRepository->getCurrentLang($this->session);
		$this->template->userRepository = $this->userRepository;
		$this->template->dogRepository = $this->dogRepository;
		$this->template->enumerationRepository = $this->enumerationRepository;
	}

	/**
	 * @param int $id
	 */
	public function actionProceedChange($id) {
		$awaitingChange = $this->awaitingRepository->getAwaitingChange($id);
		if ($awaitingChange != null) {
			try {
				$this->awaitingRepository->proceedChange($awaitingChange, $this->getUser());
				$this->flashMessage(AWAITING_CHANGE_CHANGE_ACCEPT, "alert-success");

				$userRequestEntity = $this->userRepository->getUser($awaitingChange->getUID());
				$emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
				$linkToDogView = $this->presenter->link("//:Frontend:FeItem1velord2:view", $awaitingChange->getPID());
				$body = sprintf(AWAITING_CHANGE_PROCEEDED_OK_BODY, $linkToDogView);
				EmailController::SendPlainEmail($emailFrom, $userRequestEntity->getEmail(), AWAITING_CHANGE_PROCEEDED_OK_SUBJECT, $body);
			} catch (\Exception $e) {
				$this->flashMessage(AWAITING_CHANGE_CHANGE_ERR, "alert-danger");
			}
		}
		$this->redirect("default");
	}

	/**
	 * @param int $id
	 */
	public function actionDeclineChange($id) {
		$awaitingChange = $this->awaitingRepository->getAwaitingChange($id);
		if ($awaitingChange != null) {
			try {
				$this->awaitingRepository->declineChange($awaitingChange, $this->getUser());
				$this->flashMessage(AWAITING_CHANGE_CHANGE_DECLINE, "alert-success");

				$userRequestEntity = $this->userRepository->getUser($awaitingChange->getUID());
				$emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
				$linkToDogView = $this->presenter->link(":Frontend:FeItem1velord2:view", $awaitingChange->getPID());
				$body = sprintf(AWAITING_CHANGE_PROCEEDED_DECLINE_BODY, $linkToDogView);
				//EmailController::SendPlainEmail($emailFrom, $userRequestEntity->getEmail(), AWAITING_CHANGE_PROCEEDED_DECLINE_SUBJECT, $body);
			} catch (\Exception $e) {
				$this->flashMessage(AWAITING_CHANGE_CHANGE_ERR, "alert-danger");
			}
		}
		$this->redirect("default");
	}

}
