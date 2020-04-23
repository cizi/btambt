<?php

namespace App\FrontendModule\Presenters;

use App\Enum\StateEnum;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;

class FeItem1velord5Presenter extends FrontendPresenter {

	/** @var UserRepository  */
	private $userRepository;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	public function __construct(UserRepository $userRepository, EnumerationRepository $enumerationRepository) {
		$this->userRepository = $userRepository;
		$this->enumerationRepository = $enumerationRepository;
	}

	public function actionDefault() {
		$this->template->users = $this->userRepository->findCatteries();
		$this->template->stateEnum = new StateEnum();
		$this->template->lang = $this->langRepository->getCurrentLang($this->session);
		$this->template->enumRepo = $this->enumerationRepository;
	}
}