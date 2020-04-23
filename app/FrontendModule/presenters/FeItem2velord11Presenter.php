<?php

namespace App\FrontendModule\Presenters;

use App\Controller\DogChangesComparatorController;
use App\Controller\FileController;
use App\Enum\DogFileEnum;
use App\Enum\DogStateEnum;
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
use App\Model\UserRepository;
use App\Model\WebconfigRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use Nette\Http\FileUpload;
use Nette\Utils\Paginator;

class FeItem2velord11Presenter extends FrontendPresenter {

	/** @persistent */
	public $filter;

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

	/** @var DogChangesComparatorController */
	private $dogChangesComparatorController;

	public function __construct(
		DogFilterForm $dogFilterForm,
		DogForm $dogForm,
		DogRepository $dogRepository,
		EnumerationRepository $enumerationRepository,
		UserRepository $userRepository,
		DogChangesComparatorController $changesComparatorController
	) {
		$this->dogFilterForm = $dogFilterForm;
		$this->dogForm = $dogForm;
		$this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->userRepository = $userRepository;
		$this->dogChangesComparatorController = $changesComparatorController;
	}

	/**
	 * @param int $id
	 */
	public function actionDefault($id) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}

		$filter = $this->decodeFilterFromQuery();
		$this['dogFilterForm']->setDefaults($filter);

		$recordCount = $this->dogRepository->getDogsCount($filter, $this->getUser()->getId(), $this->getUser()->getId());
		$page = (empty($id) ? 1 : $id);
		$paginator = new Paginator();
		$paginator->setItemCount($recordCount); // celkový počet položek
		$paginator->setItemsPerPage(50); // počet položek na stránce
		$paginator->setPage($page); // číslo aktuální stránky, číslováno od 1

		$this->template->paginator = $paginator;
		$this->template->dogs = $this->dogRepository->findDogs($paginator, $filter, $this->getUser()->getId(), $this->getUser()->getId());
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
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}

		if ($id == null) {
			$this->template->currentDog = null;
			$this->template->previousOwners = [];
			$this->template->dogFiles = [];
			$this->template->mIDFound = true;
			$this->template->oIDFound = true;
			$owners[] = $this->getUser()->getId();
			$this['dogForm']['owners']['uID']->setDefaultValue($owners);
		} else {
			$owners = $this->userRepository->findDogOwners($id);	// pokud nejsem majitelem, nemůžu ho editovat
			$breeders = [];
			$breeder = $this->userRepository->getBreederByDog($id);  // pokud nejsem chovatelem, nemůžu ho editovat
			if ($breeder != null) {
				$breeders[] = $breeder->getUID();
			}
			if (in_array($this->getUser()->getId(), array_merge($owners, $breeders)) == false) {
				$this->flashMessage(DOG_FORM_NOT_TRUE_OWNER, "alert-danger");
				$this->redirect("default");
			}

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
			$owners = $this->userRepository->findDogOwners($dogId);	// pokud nejsem majitelem, nemůžu ho mazat
			if ($this->getUser()->isLoggedIn() == true && in_array($this->getUser()->getId(), $owners)) {
				$this->dogRepository->setDefaultDogPic($dogId, $picId);
			}
		}
		$this->terminate();
	}

	/**
	 * @param int $id
	 * @param int $pID
	 */
	public function actionDeleteDogPic($id, $pID) {
		$owners = $this->userRepository->findDogOwners($pID);	// pokud nejsem majitelem, nemůžu mazat
		if ($this->getUser()->isLoggedIn() == false || (in_array($this->getUser()->getId(), $owners) == false)) {
			$this->flashMessage(DOG_FORM_NOT_TRUE_OWNER, "alert-danger");
			$this->redirect("default");
		} else {
			$this->dogRepository->deleteDogPic($id);
		}
		$this->redirect("edit", $pID);
	}

	/**
	 * Zapíše změny do logu, který musí schválit admin
	 * @param Form $form
	 * @throws AbortException
	 */
	public function saveDog(Form $form) {
		$supportedPicFormats = ["jpg", "png", "gif", "jpeg", "webp", "bmp"];
		$supportedFileFormats = ["jpg", "png", "gif", "doc", "docx", "pdf", "xls", "xlsx"];
		$newDogEntity = new DogEntity();
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
				if (isset($formData['ID'])) {
					$healthEntity->setPID($formData['ID']);
				}
				$health[] = $healthEntity;
			}
			unset($formData['dogHealth']);

			// chovatele
			if (isset($formData['breeder'])) {
				$breederEntity = new BreederEntity();
				$breederEntity->hydrate($formData['breeder']);
				if (isset($formData['ID'])) {
					$breederEntity->setPID($formData['ID']);
				}
				$breeders[] = $breederEntity;
			}
			unset($formData['breeder']);

			// majitel
			if (isset($formData['owners'])) {
				foreach ($formData['owners']['uID'] as $owner) {
					$ownerEntity = new DogOwnerEntity();
					$ownerEntity->setUID($owner);
					$ownerEntity->setSoucasny(true);
					if (isset($formData['ID'])) {
						$ownerEntity->setPID($formData['ID']);
					}
					$owners[] = $ownerEntity;
				}
				unset($formData['owners']['uID']);
			}

			$adminsEmails = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
			$loggedUser = $this->userRepository->getUser($this->getUser()->getId());
			$isCommonUser = (strpos($adminsEmails, $loggedUser->getEmail()) === false);
			$isDirectEditAllowed = in_array($this->getUser()->getRoles()[0], [UserRoleEnum::USER_ROLE_ADMINISTRATOR, UserRoleEnum::USER_EDITOR]);
			// editace => schvalování adminem, ale jen tehndy pokud aktuálně přihlášený uživatel není schvalovací admin a nejsem ani v roli admina a editora
			if (isset($formData['ID']) && $isCommonUser && ($isDirectEditAllowed == false)) {
				// načtu si aktuální data psa
				$currentDogEntity = $this->dogRepository->getDog($formData['ID']);
				$this->directPicsUpload($currentDogEntity, $supportedPicFormats, (isset($formData['pics']) ? $formData['pics'] : []));
				$this->directFilesUpload($currentDogEntity, $supportedFileFormats, (isset($formData['BonitaceSoubory']) ? $formData['BonitaceSoubory'] : []));
				$newDogEntity->hydrate($formData);
				$newDogEntity->setPosledniZmena($currentDogEntity->getPosledniZmena());	// tohle bych řešit neměl, takže to převezmu ze stávající hotnoty

				$dogChanged = $this->dogChangesComparatorController->compareSaveDog($currentDogEntity, $newDogEntity);

				$currentDogHealth =$this->dogRepository->findAllHealthsByDogId($formData['ID']);
				$healthChanged = $this->dogChangesComparatorController->compareSaveDogHealth($currentDogHealth, $health);

				$currentBreeder = $this->userRepository->getBreederByDog($formData['ID']);
				$breederChanged = $this->dogChangesComparatorController->compareSaveBreeder($currentBreeder, $breeders);

				$currentOwners = $this->userRepository->findDogOwnersAsEntities($formData['ID']);	// najde současné majitele
				$ownerChanged = $this->dogChangesComparatorController->compareSaveOwners($currentOwners, $owners);
				if ($dogChanged || $healthChanged || $breederChanged || $ownerChanged) {
					$linkToDogView = "http://" . $this->getHttpRequest()->getUrl()->getHost() . $this->presenter->link("FeItem1velord2:view", $currentDogEntity->getID());
					$this->dogChangesComparatorController->sendInfoEmail($linkToDogView);
				}
				$this->flashMessage(AWAITING_CHANGES_SENT_TO_APPROVAL, "alert-success");
			} else {	// pořizování nebo přímá editace pokud jsem jeden z adminů
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
				unset($formData['BonitaceSoubory']);

				$newDogEntity->hydrate($formData);
				// pokud nemá povolenou přímou editaci tak nový pes je prvně třeba schválit
				if (($isDirectEditAllowed == false) && ($newDogEntity->getID() == null)) {
					$newDogEntity->setStav(DogStateEnum::INACTIVE);
				}
				$this->dogRepository->save($newDogEntity, $pics, $health, $breeders, $owners, $files);
				// pokud nemám povovlenou přímou editaci/pořizování, tak po založení psa se stavem ke schválení musím udělat zápis a poslat mail
				if ($isDirectEditAllowed == false) {
					$linkToDogView = $this->getHttpRequest()->getUrl()->getBaseUrl() . $this->presenter->link("FeItem1velord2:view", $newDogEntity->getID());
					$this->dogChangesComparatorController->newDogCreated($newDogEntity, $linkToDogView);
					$this->flashMessage(AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL, "alert-success");
				} else {	// pokud jsem jeden z adminů a založil jsem psa jen vypíši hlášku
					$this->flashMessage(DOG_FORM_ADDED, "alert-success");
				}
			}
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
	 * @param DogEntity $currentDogEntity
	 * @param array $supportedPicFormats
	 * @param array $pics
	 * @throws \Exception
	 */
	private function directPicsUpload(DogEntity $currentDogEntity, array $supportedPicFormats, array $pics) {
		/** @var FileUpload $file */
		foreach($pics as $file) {
			if ($file != null) {
				$fileController = new FileController();
				if ($fileController->upload($file, $supportedPicFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
					$message = sprintf("Nelze nahrát soubor '%s'.", $file->getName());
					throw new \Exception($message);
					break;
				}
				$dogPic = new DogPicEntity();
				$dogPic->setCesta($fileController->getPathDb());
				$dogPic->setPID($currentDogEntity->getID());
				$this->dogRepository->saveDogPic($dogPic);
			}
		}
	}

	/**
	 * @param DogEntity $currentDogEntity
	 * @param array $supportedFileFormats
	 * @param array $files
	 * @throws \Exception
	 */
	private function directFilesUpload(DogEntity $currentDogEntity, array $supportedFileFormats, array $files) {
		/** @var FileUpload $file */
		foreach($files as $file) {
			if ($file != null) {
				$fileController = new FileController();
				if ($fileController->upload($file, $supportedFileFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
					$message = sprintf("Nelze nahrát soubor '%s'.", $file->getName());
					throw new \Exception($message);
					break;
				}
				$dogFile = new DogFileEntity();
				$dogFile->setCesta($fileController->getPathDb());
				$dogFile->setTyp(DogFileEnum::BONITACNI_POSUDEK);
				$dogFile->setPID($currentDogEntity->getID());
				$this->dogRepository->saveDogFile($dogFile);
			}
		}
	}

	/**
	 * @param int $id
	public function actionDelete($id) {
		$owners = $this->userRepository->findDogOwners($id);	// pokud nejsem majitelem, nemůžu ho mazat
		if ($this->getUser()->isLoggedIn() == false || (in_array($this->getUser()->getId(), $owners) == false)) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_FORM_NOT_TRUE_OWNER, "alert-danger");
			$this->redirect("default");
		}

		if ($this->dogRepository->delete($id)) {
			$this->flashMessage(DOG_TABLE_DOG_DELETED, "alert-success");
		} else {
			$this->flashMessage(DOG_TABLE_DOG_DELETED_FAILED, "alert-danger");
		}
		$this->redirect("default");
	}
	 */
}
