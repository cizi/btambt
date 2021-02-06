<?php

namespace App\AdminModule\Presenters;

use App\Enum\LitterApplicationStateEnum;
use App\Enum\UserRoleEnum;
use App\Forms\LitterApplicationDetailForm;
use App\Forms\LitterApplicationFilterForm;
use App\Forms\LitterApplicationRewriteForm;
use App\Model\DogRepository;
use App\Model\Entity\BreederEntity;
use App\Model\Entity\DogEntity;
use App\Model\EnumerationRepository;
use App\Model\LitterApplicationRepository;
use App\Model\UserRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;

class LitterApplicationPresenter extends SignPresenter {

	/** @persistent */
	public $filter;

	/** @var LitterApplicationRepository */
	private $litterApplicationRepository;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var DogRepository */
	private $dogRepository;

	/** @var LitterApplicationRewriteForm */
	private $litterApplicationRewriteForm;

	/** @var UserRepository */
	private $userRepository;

	/** @var LitterApplicationFilterForm  */
	private $litterApplicationFilterForm;

	public function __construct(
		LitterApplicationRepository $litterApplicationRepository,
		EnumerationRepository $enumerationRepository,
		DogRepository $dogRepository,
		LitterApplicationRewriteForm $applicationRewriteForm,
		LitterApplicationRewriteForm $litterApplicationRewriteForm,
		UserRepository $userRepository,
		LitterApplicationFilterForm $litterApplicationFilterForm
	) {
		$this->litterApplicationRepository = $litterApplicationRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->dogRepository = $dogRepository;
		$this->litterApplicationRewriteForm = $litterApplicationRewriteForm;
		$this->userRepository = $userRepository;
		$this->litterApplicationFilterForm = $litterApplicationFilterForm;
	}

    public function startup() {
        parent::startup();
        $this->template->amIAdmin = ($this->getUser()->isLoggedIn() && $this->getUser()->getRoles()[0] == UserRoleEnum::USER_ROLE_ADMINISTRATOR);
	}

	/**
	 * @param int $id
	 */
	public function actionDefault($id) {
		$filter = $this->decodeFilterFromQuery();
		$this['litterApplicationFilterForm']->setDefaults($filter);

		$this->template->applications = $this->litterApplicationRepository->findLitterApplications($filter);
		$this->template->enumRepo = $this->enumerationRepository;
		$this->template->dogRepo = $this->dogRepository;
		$this->template->currentLang = $this->langRepository->getCurrentLang($this->session);
		$this->template->litterApplicationStateEnumInsert = LitterApplicationStateEnum::INSERT;			// php 5.4 workaround
	}

	/**
	 * @param int $id
	 */
	public function actionDelete($id) {
		if ($this->litterApplicationRepository->delete($id)) {
			$this->flashMessage(LITTER_APPLICATION_DELETED, "alert-success");
		} else {
			$this->flashMessage(LITTER_APPLICATION_DELETED_FAILED, "alert-danger");
		}
		$this->redirect("default");
	}

    /**
     * @param int $id
     * @throws AbortException
     */
    public function ActionHideApplication($id)
    {
        if ($this->litterApplicationRepository->hide($id)) {
            $this->flashMessage(LITTER_APPLICATION_HIDE_OK, "alert-success");
        } else {
            $this->flashMessage(LITTER_APPLICATION_HIDE_GENERAL_FAIL, "alert-danger");
        }
        $this->redirect("default");
	}

    /**
     * @param int $id
     * @throws AbortException
     */
    public function ActionUnhideApplication($id)
    {
        if ($this->litterApplicationRepository->unhide($id)) {
            $this->flashMessage(LITTER_APPLICATION_UNHIDE_OK, "alert-success");
        } else {
            $this->flashMessage(LITTER_APPLICATION_HIDE_GENERAL_FAIL, "alert-danger");
        }
        $this->redirect("default");
    }

	/**
	 * @param int $id
	 */
	public function actionRewriteDescendants($id) {
		$application = $this->litterApplicationRepository->getLitterApplication($id);
		if ($application != null) {
			if ($application->getZavedeno() == LitterApplicationStateEnum::REWRITTEN) {
				$this->flashMessage(LITTER_APPLICATION_REWRITE_DESCENDANTS_ALREADY_IN, "alert-danger");
				$this->redirect("default");
			}
			$appParams = $application->getDataDecoded();
			$chs = (isset($appParams['chs']) ? " " . trim($appParams['chs']) : "");
			$formData["Plemeno"] = (isset($appParams["Plemeno"]) ? $appParams["Plemeno"] : $appParams["plemeno"]);
			$formData["mID"] = $appParams["mID"];
			$formData["oID"] = $appParams["oID"];
			$formData["ID"] = $id;
			if (isset($appParams["datumnarozeni"]) && (trim($appParams["datumnarozeni"]) != "")) {
				$formData["DatNarozeni"] = $appParams["datumnarozeni"];
			}
			for($i = 1; $i <= LitterApplicationDetailForm::NUMBER_OF_LINES; $i++) {
				if (($this->getValueByKeyFromArray($appParams, $i, "mikrocip") == "") && ($this->getValueByKeyFromArray($appParams, $i, "jmeno")) == "" ) {
					unset($this['litterApplicationRewriteForm'][$i]["CisloZapisu"]);
					unset($this['litterApplicationRewriteForm'][$i]["Tetovani"]);
					unset($this['litterApplicationRewriteForm'][$i]["Cip"]);
					unset($this['litterApplicationRewriteForm'][$i]["Jmeno"]);
					unset($this['litterApplicationRewriteForm'][$i]["KontrolaVrhu"]);
					unset($this['litterApplicationRewriteForm'][$i]["SrstSel"]);
					unset($this['litterApplicationRewriteForm'][$i]["Srst"]);
					unset($this['litterApplicationRewriteForm'][$i]["BarvaSel"]);
					unset($this['litterApplicationRewriteForm'][$i]["Barva"]);
					unset($this['litterApplicationRewriteForm'][$i]["PohlaviSel"]);
					unset($this['litterApplicationRewriteForm'][$i]["Pohlavi"]);
				} else {
					$formData[$i]["Cip"] = $this->getValueByKeyFromArray($appParams, $i, "mikrocip");
					$formData[$i]["Jmeno"] = $this->getValueByKeyFromArray($appParams, $i, "jmeno") . $chs;
					$formData[$i]["SrstSel"] = $this->getValueByKeyFromArray($appParams, $i, "srst", true);
					$formData[$i]["Srst"] = $this->getValueByKeyFromArray($appParams, $i, "srst");
					$formData[$i]["BarvaSel"] = $this->getValueByKeyFromArray($appParams, $i, "barva", true);
					$formData[$i]["Barva"] = $this->getValueByKeyFromArray($appParams, $i, "barva");
					$formData[$i]["PohlaviSel"] = $this->getValueByKeyFromArray($appParams, $i, "pohlavi", true);
					$formData[$i]["Pohlavi"] = $this->getValueByKeyFromArray($appParams, $i, "pohlavi");
				}
			}
			$this['litterApplicationRewriteForm']->setDefaults($formData);
		} else {
			$this->flashMessage(LITTER_APPLICATION_REWRITE_DOES_NOT_EXIST, "alert-danger");
			$this->redirect("default");
		}
	}

	/**
	 * @param $array
	 * @param int $lineNumber
	 * @param string $key
	 * @return string
	 */
	private function getValueByKeyFromArray($array, $lineNumber, $key, $isSelect =  false) {
		$result = "";
		foreach ($array as $arrKey => $arrValue) {
			if (($key.$lineNumber) == $arrKey) {
				$result = $arrValue;
				break;
			}
		}

		return ($isSelect && ($result == "") ? null : $result);
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentLitterApplicationRewriteForm() {
		$currentLang = $this->langRepository->getCurrentLang($this->session);
		$form = $this->litterApplicationRewriteForm->create($currentLang);
		$form->onSubmit[] = [$this, 'submitRewrite'];

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function submitRewrite(Form $form) {
		try {
			$formArray = $form->getHttpData();
			$breeders = [];
			$dogs = [];

			if (isset($formArray['breeder'])) {	// chovatele
				$breederEntity = new BreederEntity();
				$breederEntity->hydrate($formArray['breeder']);
				$breeders[] = $breederEntity;
			}
			unset($formArray['breeder']);

			foreach ($formArray as $dogArr) { // psi
				if (is_array($dogArr)) {
					$dogEntity = new DogEntity();
					$dogEntity->hydrate($dogArr);
					if (($dogEntity->getJmeno() == "") && ($dogEntity->getCip() == "")) {
						continue;
					}
					if ($formArray["Plemeno"] != 0) {
						$dogEntity->setPlemeno($formArray["Plemeno"]);
					}
					$dogEntity->setMID($formArray["mID"]);
					$dogEntity->setOID($formArray["oID"]);
					if ($formArray["DatNarozeni"] != "") {
						$dogEntity->setDatNarozeni($formArray["DatNarozeni"]);
					}
					$dogs[] = $dogEntity;
				}
			}

			$application = $this->litterApplicationRepository->getLitterApplication($formArray['ID']);
			$owners = [];
			/* if (($application->getMajitelFeny() != null ) && (trim($application->getMajitelFeny()) != "")) {
				$dogOwners = explode(",", $application->getMajitelFeny());
				foreach ($dogOwners as $own) {
					$userEntity = $this->userRepository->getUser(trim($own));
					if ($userEntity != null) {
						$ownerEntity = new DogOwnerEntity();
						$ownerEntity->setSoucasny(true);
						$ownerEntity->setUID($userEntity->getId());
						$owners[] = $ownerEntity;
					}
				}
			} */
			$this->dogRepository->saveDescendants($dogs, $breeders, $owners, $application);

			$this->flashMessage(LITTER_APPLICATION_REWRITE_DESCENDANTS_OK, "alert-success");
			$this->redirect("default");
		} catch (AbortException $e) {
			throw $e;
		} catch (\Exception $e) {
			$this->flashMessage(LITTER_APPLICATION_REWRITE_DESCENDANTS_FAILED, "alert-danger");
		}
	}

	public function createComponentLitterApplicationFilterForm() {
		$form = $this->litterApplicationFilterForm->create($this->langRepository->getCurrentLang($this->session), true);
		$form->onSubmit[] = [$this, 'litterApplicationFilterSubmit'];

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-md-2';
		$renderer->wrappers['label']['container'] = 'div class="col-md-3 control-label"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
		$form->getElementPrototype()->class('form-vertical');

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function litterApplicationFilterSubmit(Form $form) {
		$filter = "1&";
		foreach ($form->getValues() as $key => $value) {
			if ($value != "") {
				$filter .= $key . "=" . $value . "&";
			}
		}
		$this->filter = $filter;
		$this->redirect("default");
	}
}
