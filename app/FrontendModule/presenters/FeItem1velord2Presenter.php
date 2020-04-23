<?php

namespace App\FrontendModule\Presenters;

use App\Controller\FileController;
use App\Enum\DogFileEnum;
use App\Enum\StateEnum;
use App\Enum\UserRoleEnum;
use App\Forms\DogFilterForm;
use App\Forms\DogForm;
use App\Model\DogRepository;
use App\Model\Entity\BreederEntity;
use App\Model\Entity\DogEntity;
use App\Model\Entity\DogFileEntity;
use App\Model\Entity\DogHealthEntity;
use App\Model\Entity\DogOwnerEntity;
use App\Model\Entity\DogPicEntity;
use App\Model\Entity\EnumerationItemEntity;
use App\Model\EnumerationRepository;
use App\Model\ShowDogRepository;
use App\Model\UserRepository;
use App\Model\VetRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use Nette\Http\FileUpload;
use Nette\Utils\Paginator;

class FeItem1velord2Presenter extends FrontendPresenter {
	
	/** @persistent */
	public $filter;

	/** @const počet obrázků v hlavičce pohledu  */
	const TOP_IMAGE_COUNT = 2;

	/** @var DogRepository */
	private $dogRepository;

	/** @var DogFilterForm */
	private $dogFilterForm;

	/** @var DogForm */
	private $dogForm;

	/** @var UserRepository  */
	private $userRepository;

	/** @var EnumerationRepository  */
	private $enumerationRepository;

	/** @var ShowDogRepository */
	private $showDogRepository;

	/** @var VetRepository */
	private $vetRepository;

	/**
	 * FeItem1velord2Presenter constructor.
	 * @param DogFilterForm $dogFilterForm
	 * @param DogForm $dogForm
	 * @param DogRepository $dogRepository
	 * @param EnumerationRepository $enumerationRepository
	 * @param UserRepository $userRepository
	 * @param ShowDogRepository $showDogRepository
	 * @param VetRepository $vetRepository
	 */
	public function __construct(
		DogFilterForm $dogFilterForm,
		DogForm $dogForm,
		DogRepository $dogRepository,
		EnumerationRepository $enumerationRepository,
		UserRepository $userRepository,
		ShowDogRepository $showDogRepository,
		VetRepository $vetRepository
	) {
		$this->dogFilterForm = $dogFilterForm;
		$this->dogForm = $dogForm;
		$this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->userRepository = $userRepository;
		$this->showDogRepository = $showDogRepository;
		$this->vetRepository = $vetRepository;
	}

	public function startup() {
		$this->template->amIAdmin = ($this->getUser()->isLoggedIn() && $this->getUser()->getRoles()[0] == UserRoleEnum::USER_ROLE_ADMINISTRATOR);
		$this->template->canDirectEdit = ($this->getUser()->isLoggedIn() && ($this->getUser()->getRoles()[0] == UserRoleEnum::USER_ROLE_ADMINISTRATOR) || ($this->getUser()->getRoles()[0] == UserRoleEnum::USER_EDITOR));
		parent::startup();
	}

	/**
	 * @param int $id
	 */
	public function actionDefault($id) {
		$filter = $this->decodeFilterFromQuery();
		$this['dogFilterForm']->setDefaults($filter);

		$recordCount = $this->dogRepository->getDogsCount($filter);
		$page = (empty($id) ? 1 : $id);
		$paginator = new Paginator();
		$paginator->setItemCount($recordCount); // celkový počet položek
		$paginator->setItemsPerPage(50); // počet položek na stránce
		$paginator->setPage($page); // číslo aktuální stránky, číslováno od 1

		$this->template->paginator = $paginator;
		$this->template->dogs = $this->dogRepository->findDogs($paginator, $filter);
		$this->template->dogRepository = $this->dogRepository;
		$this->template->currentLang = $this->langRepository->getCurrentLang($this->session);
		$this->template->enumRepository = $this->enumerationRepository;
		$this->template->filterActivated = (!empty($filter) ? true : false);
		$this->template->recordCount = $recordCount;
		$this->template->pageCount = $paginator->getPageCount();
	}

	/**
	 * @param Form $form
	 */
	public function dogFilter(Form $form) {
		$filter = "1&";
		foreach ($form->getHttpData() as $key => $value) {
			if ($value != "") {
				$filter .= $key . "=" . $value . "&";
			}
		}
		$this->filter = $filter;
		$this->redirect("default");
	}

	/**
	 * Vytvoří komponentu pro změnu hesla uživatele
	 */
	public function createComponentDogFilterForm() {
		$form = $this->dogFilterForm->create($this->langRepository->getCurrentLang($this->session));
		$form->onSubmit[] = [$this, 'dogFilter'];

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-md-3';
		$renderer->wrappers['label']['container'] = 'div class="col-md-3 control-label"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
		//$form->getElementPrototype()->class('form-horizontal');

		return $form;
	}

	/**
	 * Vytvoří komponentu pro změnu hesla uživatele
	 */
	public function createComponentDogForm() {
		$form = $this->dogForm->create($this->langRepository->getCurrentLang($this->session), $this->link("default"));
		$form->onSubmit[] = [$this, 'saveDog'];

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-md-6';
		$renderer->wrappers['label']['container'] = 'div class="col-md-4 control-label"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
		$form->getElementPrototype()->class('form-horizontal');

		return $form;
	}

	/**
	 * @param int $id
	 */
	public function actionEdit($id) {
		if ($this->template->canDirectEdit == false) {	// pokud nejsem admin nemůžu editovat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("default");
		}

		if ($id == null) {
			$this->template->currentDog = null;
			$this->template->previousOwners = [];
			$this->template->dogFiles = [];
			$this->template->mIDFound = true;
			$this->template->oIDFound = true;
		} else {
			$dog = $this->dogRepository->getDog($id);
			$this->template->mIDFound = ($dog->getMID() == NULL || isset($this['dogForm']['mID']->getItems()[$dog->getMID()]));
			if ($this->template->mIDFound == false) {	// pokud mID psa není v selectu vyjmu ho
				$dog->setMID(0);
			}

			$this->template->oIDFound = ($dog->getOID() == NULL || isset($this['dogForm']['oID']->getItems()[$dog->getOID()]));
			if ($this->template->oIDFound == false) {	// pokud oID psa není v selectu vyjmu ho
				$dog->setOID(0);
			}

			$this->template->currentDog = $dog;
			$this->template->previousOwners = $this->userRepository->findDogPreviousOwners($id);
			$this->template->dogFiles = $this->dogRepository->findDogFiles($id);
			$this->template->dogFileEnum = new DogFileEnum();

			$this['dogForm']->setDefaults($dog->extract());
			if ($dog->getDatNarozeni() != null) {
				$this['dogForm']['DatNarozeni']->setDefaultValue($dog->getDatNarozeni()->format(DogEntity::MASKA_DATA));
			}
			if ($dog->getDatUmrti() != null) {
				$this['dogForm']['DatUmrti']->setDefaultValue($dog->getDatUmrti()->format(DogEntity::MASKA_DATA));
			}
			if ($dog) {
				$this['dogForm']->addHidden('ID', $dog->getID());
			}
			$zdravi = $this->enumerationRepository->findEnumItems($this->langRepository->getCurrentLang($this->session), 14);
			/** @var EnumerationItemEntity $enumEntity */
			foreach ($zdravi as $enumEntity) {
				$dogHealthEntity = $this->dogRepository->getHealthEntityByDogAndType($enumEntity->getOrder(), $id);
				if ($dogHealthEntity != null) {
					$this['dogForm']['dogHealth'][$enumEntity->getOrder()]->setDefaults($dogHealthEntity->extract());
					$this['dogForm']['dogHealth'][$enumEntity->getOrder()]->addHidden('ID', $dogHealthEntity->getID());
					if ($dogHealthEntity->getDatum() != null) {
						$this['dogForm']['dogHealth'][$enumEntity->getOrder()]['Datum']->setDefaultValue($dogHealthEntity->getDatum()->format(DogHealthEntity::MASKA_DATA));
					}
				}
			}
			$breeder = $this->userRepository->getBreederByDog($id);
			if ($breeder) {
				$this['dogForm']['breeder']->addHidden("ID", $breeder->getID())->setAttribute("class", "form-control");
				$this['dogForm']['breeder']['uID']->setValue($breeder->getUID());
			}

			$owners = $this->userRepository->findDogOwners($id);
			$this['dogForm']['owners']['uID']->setDefaultValue($owners);
		}
		$this->template->currentLang = $this->langRepository->getCurrentLang($this->session);
		$this->template->dogPics = $this->dogRepository->findDogPics($id);
	}

	/**
	 * Aktualizuje vychozí obrázek u psa
	 */
	public function actionDefaultDogPic() {
		$data = $this->getHttpRequest()->getQuery();
		$dogId = (isset($data['dogId']) ? $data['dogId'] : null);
		$picId = (isset($data['picId']) ? $data['picId'] : null);
		if ($dogId != null && ($picId != null)) {
			$this->dogRepository->setDefaultDogPic($dogId, $picId);
		}
		$this->terminate();
	}

	/**
	 * @param int $id
	 */
	public function actionDelete($id) {
		if ($this->template->amIAdmin == false) {	// pokud nejsem admin nemůžu mazat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("default");
		}

		if ($this->dogRepository->delete($id)) {
			$this->flashMessage(DOG_TABLE_DOG_DELETED, "alert-success");
		} else {
			$this->flashMessage(DOG_TABLE_DOG_DELETED_FAILED, "alert-danger");
		}
		$this->redirect("default");
	}

	/**
	 * @param int $id
	 * @param int $pID
	 */
	public function actionDeleteDogPic($id, $pID) {
		$this->dogRepository->deleteDogPic($id);
		$this->redirect("edit", $pID);
	}

	/**
	 * @param int $id
	 * @param int $pID
	 */
	public function actionDeleteDogFile($id, $pID) {
		if ($this->template->amIAdmin == false) {	// pokud nejsem admin nemůžu mazat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("default");
		}
		$this->dogRepository->deleteDogFile($id);
		$this->redirect("edit", $pID);
	}

	/**
	 * @param int $id
	 * @param int $genLev
	 */
	public function actionView($id, $genLev = 3) {
		$dog = $this->dogRepository->getDog($id);
		if ($dog == NULL) {
			$this->flashMessage(DOG_FORM_REQUEST_NOT_EXISTS, "alert-danger");
			$this->redirect("default");
		}

		$this->template->dog = $dog;
		$zdravi = [];
		$lang = $this->langRepository->getCurrentLang($this->session);
		$zdraviOptions = $this->enumerationRepository->findEnumItems($this->langRepository->getCurrentLang($this->session), 14);
		/** @var EnumerationItemEntity $enumEntity */
		foreach ($zdraviOptions as $enumEntity) {
			$dogHealthEntity = $this->dogRepository->getHealthEntityByDogAndType($enumEntity->getOrder(), $dog->getID());
			if ($dogHealthEntity != null) {
				$zdravi[] = $dogHealthEntity;
			}
		}

		$this->template->vetRepo = $this->vetRepository;
		$this->template->dogRepository = $this->dogRepository;
		$this->template->userRepository = $this->userRepository;
		$this->template->stateEnum = new StateEnum();
		$this->template->coef = $this->dogRepository->genealogRelationship($dog->getOID(), $dog->getMID());
		$this->template->coefComment = ((isset($GLOBALS['lastRship']) &&  ($GLOBALS['lastRship'] === false)) ? DOG_FORM_PEDIGREE_COEF_NOT_FULL : "");
		$this->template->genLev = $genLev;
		$this->template->pedigreeTable = $this->dogRepository->genealogDeepPedigree($dog->getID(), $genLev, $lang, $this->presenter, $this->template->canDirectEdit);

		$dogPics = $this->dogRepository->findDogPics($id);
		$this->template->dogPics = $dogPics;
		$this->template->dogFiles = $this->dogRepository->findDogFiles($id);
		$this->template->dogFileEnum = new DogFileEnum();
		$this->template->previousOwners = $this->userRepository->findDogPreviousOwners($id);
		$this->template->lang = $lang;
		$this->template->enumRepo = $this->enumerationRepository;
		$this->template->majitele = $this->userRepository->findDogOwnersAsUser($id);
		$this->template->chovatel = $this->userRepository->getBreederByDogAsUser($id);
		$this->template->zdravi = $zdravi;
		$this->template->siblings = $this->dogRepository->findSiblings($id);
		$this->template->descendants = $this->dogRepository->findDescendants($id);
		$this->template->showDogRepo = $this->showDogRepository;
		$this->template->showTitles = $this->showDogRepository->findTitlesByDog($id);
	}

	/**
	 * @param int $id
	 */
	public function actionUserView($id) {
		$user = $this->userRepository->getUser($id);
		if ($user != null) {
			$this->template->lang = $this->langRepository->getCurrentLang($this->session);
			$this->template->user = $user;
			$this->template->stateEnum = new StateEnum();
			$this->template->enumRepo = $this->enumerationRepository;
			$this->template->dogRepository = $this->dogRepository;
			$this->template->dogsByBreeder = $this->dogRepository->findDogsByBreeder($id);
			$this->template->dogByCurrentOwner = $this->dogRepository->findDogsByCurrentOwner($id);
			$this->template->dogByPreviousOwner = $this->dogRepository->findDogsByPreviousOwner($id);
		}
	}

	/**
	 * @param int $id
	 * @param int $uID
	 */
	public function actionDeleteDogOwner($id, $uID) {
		if ($this->template->amIAdmin == false) {	// pokud nejsem admin nemůžu mazat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("default");
		}
		$this->userRepository->deleteOwner($id);
		$this->redirect("userView", $uID);
	}

	/**
	 * @param int $id
	 * @param int $uID
	 */
	public function actionDeleteDogBreeder($id, $uID) {
		if ($this->template->amIAdmin == false) {	// pokud nejsem admin nemůžu mazat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("default");
		}
		$this->userRepository->deleteBreeder($id);
		$this->redirect("userView", $uID);
	}

	/**
	 * @param Form $form
	 */
	public function saveDog(Form $form){
        $supportedPicFormats = ["jpg", "png", "gif", "jpeg", "webp", "bmp"];
		$supportedFileFormats = ["jpg", "png", "gif", "doc", "docx", "pdf", "xls", "xlsx"];
		$dogEntity = new DogEntity();
		$pics = [];
		$files = [];
		$health = [];
		$breeders = [];
		$owners = [];
		try {
			$formData = $form->getHttpData();
			// zdraví
			foreach($formData['dogHealth'] as $typ => $hodnoty) {
				$healthEntity = new DogHealthEntity();
				$healthEntity->hydrate($hodnoty);
				$healthEntity->setTyp($typ);
				$health[] = $healthEntity;
			}
			unset($formData['dogHealth']);

			/** @var FileUpload $file */
			foreach($formData['pics'] as $file) {
				if ($file != null) {
					$fileController = new FileController();
					if ($fileController->upload($file, $supportedPicFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
						throw new \Exception("Nelze nahrát soubor.");
						break;
					}
					$dogPic = new DogPicEntity();
					$dogPic->setCesta($fileController->getPathDb());
					$pics[] = $dogPic;
				}
			}
			unset($formData['pics']);

			// chovatele
			if (isset($formData['breeder'])) {
				$breederEntity = new BreederEntity();
				$breederEntity->hydrate($formData['breeder']);
				$breeders[] = $breederEntity;
			}
			unset($formData['breeder']);

			// majitel
			if (isset($formData['owners'])) {
				foreach ($formData['owners']['uID'] as $owner) {
					$ownerEntity = new DogOwnerEntity();
					$ownerEntity->setUID($owner);
					$ownerEntity->setSoucasny(true);
					$owners[] = $ownerEntity;
				}
				unset($formData['owners']['uID']);
			}

			// bonitační soubory
			/** @var FileUpload $file */
			foreach($formData['BonitaceSoubory'] as $file) {
				if ($file != null) {
					$fileController = new FileController();
					if ($fileController->upload($file, $supportedFileFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
						throw new \Exception("Nelze nahrát soubor.");
						break;
					}
					$dogFile = new DogFileEntity();
					$dogFile->setCesta($fileController->getPathDb());
					$dogFile->setTyp(DogFileEnum::BONITACNI_POSUDEK);
					$files[] = $dogFile;
				}
			}
			$dogEntity->hydrate($formData);

			$mIdOrOidForNewDog = (isset($formData['mIdOrOidForNewDog']) ? $formData['mIdOrOidForNewDog'] : null);
			$this->dogRepository->save($dogEntity, $pics, $health, $breeders, $owners, $files, $mIdOrOidForNewDog);
			$this->flashMessage(DOG_FORM_ADDED, "alert-success");
			$this->redirect("default");
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				// dump($e->getMessage()); die;
				$form->addError(DOG_FORM_ADD_FAILED);
				$this->flashMessage(DOG_FORM_ADD_FAILED, "alert-danger");
			}
		}
	}

	/**
	 * @param int $pID
	 */
	public function actionAddMissingDog($pID) {
		if ($this->template->canDirectEdit == false) {	// pokud nejsem admin nemůžu sem
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("default");
		}

		$this->setView("edit");
		$this->template->currentDog = null;
		$this->template->previousOwners = [];
		$this->template->dogFiles = [];
		$this->template->mIDFound = true;
		$this->template->oIDFound = true;
		$this->template->currentLang = $this->langRepository->getCurrentLang($this->session);
		$this->template->dogPics = [];
		if ($pID != "") {
			$this['dogForm']->addHidden("mIdOrOidForNewDog", $pID);
		}
	}

}
