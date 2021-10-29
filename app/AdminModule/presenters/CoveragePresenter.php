<?php

namespace App\AdminModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Model\UserRepository;
use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\CoverageApplicationRepository;
use App\Forms\CoverageFilterForm;

class CoveragePresenter extends SignPresenter {

    /** @persistent */
	public $filter;

    /** @var CoverageApplicationRepository */
    private $coverageApplicationRepository;
    
    /** @var UserRepository  */
	private $userRepository;

	/** @var EnumerationRepository  */
    private $enumerationRepository;
    
    /** @var DogRepository */
    private $dogRepository;
    
    /** @var CoverageFilterForm */
    private $coverageFilterForm;

	/**
	 * @param CoverageApplicationRepository $coverageApplicationRepository
     * @param DogRepository $dogRepository
	 * @param EnumerationRepository $enumerationRepository
	 * @param UserRepository $userRepository
     * @param CoverageFilterForm $coverageFilterForm
	 */
	public function __construct(
        CoverageApplicationRepository $coverageApplicationRepository,
        DogRepository $dogRepository,
		EnumerationRepository $enumerationRepository,
        UserRepository $userRepository, 
        CoverageFilterForm $coverageFilterForm
    ) {
        $this->coverageApplicationRepository = $coverageApplicationRepository;
        $this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
        $this->userRepository = $userRepository;
        $this->coverageFilterForm = $coverageFilterForm;
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
        $filter = $this->decodeFilterFromQuery();
        $this['coverageFilterForm']->setDefaults($filter);

        $covers = $this->coverageApplicationRepository->findCoverageApplications($filter);
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

    /**
     * @param int $id
     */
    public function actionDeleteAttachment($id) {
        $result = $this->coverageApplicationRepository->deleteAttachment($id);
        if ($result) {
            $this->flashMessage(MENU_SETTINGS_ITEM_DELETED, "alert-success");
        } else {
            $this->flashMessage(BLOCK_SETTINGS_ITEM_DELETED_FAILED, "alert-danger");
        }
        $this->redirect("Default");
    }

    public function createComponentCoverageFilterForm() {
        $currentLang = $this->langRepository->getCurrentLang($this->session);
        $form = $this->coverageFilterForm->create($currentLang);
        $form->onSuccess[] = [$this, 'filterCoverage'];

        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = NULL;
        $renderer->wrappers['pair']['container'] = 'div class=form-group';
        $renderer->wrappers['pair']['.error'] = 'has-error';
        $renderer->wrappers['control']['container'] = 'div class=col-md-4';
        $renderer->wrappers['label']['container'] = 'div class="col-md-2 control-label"';
        $renderer->wrappers['control']['description'] = 'span class=help-block';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
        $form->getElementPrototype()->class('form-vertical');

		return $form;
    }

    public function filterCoverage($form) {
        if (isset($form->getHttpData()["clearFilter"])) {
            $this->filter = "";
        } else {
            $filter = "1&";
            foreach ($form->getValues() as $key => $value) {
                if ($value != "") {
                    $filter .= $key . "=" . $value . "&";
                }
            }
            $this->filter = $filter;
        }
        $this->redirect("default");
    }
}