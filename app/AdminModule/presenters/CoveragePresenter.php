<?php

namespace App\AdminModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Model\LangRepository;
use App\Model\UserRepository;
use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\CoverageApplicationRepository;

class CoveragePresenter extends SignPresenter {

    /** @var CoverageApplicationRepository */
    private $coverageApplicationRepository;
    
    /** @var UserRepository  */
	private $userRepository;

	/** @var EnumerationRepository  */
    private $enumerationRepository;
    
    /** @var DogRepository */
	private $dogRepository;

	/**
	 * @param CoverageApplicationRepository $coverageApplicationRepository
     * @param DogRepository $dogRepository
	 * @param EnumerationRepository $enumerationRepository
	 * @param UserRepository $userRepository
	 */
	public function __construct(
        CoverageApplicationRepository $coverageApplicationRepository,
        DogRepository $dogRepository,
		EnumerationRepository $enumerationRepository,
		UserRepository $userRepository
    ) {
        $this->coverageApplicationRepository = $coverageApplicationRepository;
        $this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->userRepository = $userRepository;
	}

	/**
	 * Pokud nejsem admin tak tady nemám co dělat
	 */
	public function startup() {
		parent::startup();
		if (($this->getUser()->getRoles()[0] < UserRoleEnum::USER_EDITOR)) {
            $this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
            $this->redirect("Dashboard:Default");
		}
	}

    /**
     * 
     */
	public function actionDefault() {
        $covers = $this->coverageApplicationRepository->findCoverageApplications();
        $this->template->covers = $covers;
        $this->template->coverageApplicationRepo = $this->coverageApplicationRepository;
        $this->template->enumRepo = $this->enumerationRepository;
        $this->template->dogRepository = $this->dogRepository;
        $this->template->userRepository = $this->userRepository;
        $this->template->currentLang = $this->langRepository->getCurrentLang($this->session);
    }
    
    public function actionDelete($id) {
        $result = $this->coverageApplicationRepository->delete($id);
        if ($result) {
            $this->flashMessage(MENU_SETTINGS_ITEM_DELETED, "alert-success");
        } else {
            $this->flashMessage(BLOCK_SETTINGS_ITEM_DELETED_FAILED, "alert-danger");
        }
        $this->redirect("Default");
    }
  
}