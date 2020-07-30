<?php

namespace App\AdminModule\Presenters;

use App\Controller\FileController;
use App\Enum\UserRoleEnum;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

class CoveragePresenter extends SignPresenter {

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

	}

    
}