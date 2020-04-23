<?php

namespace App\AdminModule\Presenters;

use App\Forms\ShowDogForm;
use App\Forms\ShowForm;
use App\Forms\ShowRefereeForm;
use App\Model\DogRepository;
use App\Model\Entity\RefereeEntity;
use App\Model\Entity\ShowDogEntity;
use App\Model\Entity\ShowEntity;
use App\Model\Entity\ShowRefereeEntity;
use App\Model\EnumerationRepository;
use App\Model\RefereeRepository;
use App\Model\ShowDogRepository;
use App\Model\ShowRefereeRepository;
use App\Model\ShowRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;

class ShowPresenter extends SignPresenter {

	/** @var ShowRepository  */
	private $showRepository;

	/** @var EnumerationRepository  */
	private  $enumerationRepository;

	/** @var  RefereeRepository */
	private $refereeRepository;

	/** @var ShowForm */
	private $showForm;

	/** @var  ShowDogForm */
	private $showDogForm;

	/** @var ShowRefereeForm  */
	private $showRefereeForm;

	/** @var  ShowDogRepository */
	private $showDogRepository;

	/** @var  ShowRefereeRepository */
	private $showRefereeRepository;

	/** @var DogRepository  */
	private $dogRepository;

	/**
	 * @param ShowRepository $showRepository
	 * @param EnumerationRepository $enumerationRepository
	 * @param RefereeRepository $refereeRepository
	 * @param ShowForm $showForm
	 * @param ShowDogForm $showDogForm
	 * @param ShowRefereeForm $showRefereeForm
	 * @param ShowDogRepository $showDogRepository
	 * @param ShowRefereeRepository $showRefereeRepository
	 * @param DogRepository $dogRepository
	 */
	public function __construct(
		ShowRepository $showRepository,
		EnumerationRepository $enumerationRepository,
		RefereeRepository $refereeRepository,
		ShowForm $showForm,
		ShowDogForm $showDogForm,
		ShowRefereeForm $showRefereeForm,
		ShowDogRepository $showDogRepository,
		ShowRefereeRepository $showRefereeRepository,
		DogRepository $dogRepository
	) {
		$this->showRepository = $showRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->refereeRepository = $refereeRepository;
		$this->showForm = $showForm;
		$this->showDogForm = $showDogForm;
		$this->showRefereeForm = $showRefereeForm;
		$this->showDogRepository = $showDogRepository;
		$this->showRefereeRepository = $showRefereeRepository;
		$this->dogRepository = $dogRepository;
	}

	public function startup() {
		parent::startup();
		$this->template->lang = $this->langRepository->getCurrentLang($this->session);
		$this->template->enumRepo= $this->enumerationRepository;
		$this->template->shows = $this->showRepository->findShows();
		$this->template->refereeRepository = $this->refereeRepository;
		$this->template->dogRepository = $this->dogRepository;
	}

	public function actionDefault() {

	}

	/**
	 * Ozna�� v�stavu jakko ukon�enou/neukon�enou
	 */
	public function handleDoneSwitch() {
		$data = $this->request->getParameters();
		$idShow = $data['idShow'];
		$switchTo = (!empty($data['to']) && $data['to'] == "false" ? false : true);

		if ($switchTo) {
			$this->showRepository->setShowDone($idShow);
		} else {
			$this->showRepository->setShowUndone($idShow);
		}

		$this->terminate();
	}

	/**
	 * @param int $id
	 */
	public function actionDetail($id) {
		$this->template->show = $this->showRepository->getShow($id);
		$this->template->referees = $this->showRefereeRepository->findRefereeByShow($id);
		$this->template->dogs = $this->showDogRepository->findDogsByShow($id);
	}

	/**
	 * @param int $id
	 * @param int $vID
	 */
	public function actionDeleteShowReferee($id, $vID) {
		if ($this->showRefereeRepository->delete($id)) {
			$this->flashMessage(REFEREE_DELETED, "alert-success");
		} else {
			$this->flashMessage(REFEREE_SAVED_FAILED, "alert-danger");
		}
		$this->redirect("detail", $vID);
	}

	/**
	 * @param int $id
	 * @param int $vID
	 */
	public function actionDeleteShowDog($id, $vID) {
		if ($this->showDogRepository->delete($id)) {
			$this->flashMessage(SHOW_DOG_DELETED, "alert-success");
		} else {
			$this->flashMessage(SHOW_DOG_SAVED_FAILED, "alert-danger");
		}
		$this->redirect("detail", $vID);
	}

	/**
	 * @param int $id vystavy
	 */
	public function actionEditShowReferee($id) {
		$this['showRefereeForm']['vID']->setDefaultValue($id);
	}

	/**
	 * @param int $id vystavy
	 */
	public function actionEditShowDog($id) {
		$this['showDogForm']['vID']->setDefaultValue($id);
	}

	public function createComponentShowRefereeForm() {
		$form = $this->showRefereeForm->create($this->link("detail"), $this->langRepository->getCurrentLang($this->session));
		$form->onSubmit[] = [$this, 'saveShowReferee'];

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-md-6';
		$renderer->wrappers['label']['container'] = 'div class="col-md-4 control-label margin5"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function saveShowReferee(Form $form) {
		$arrayValues = $form->getHttpData();
		$refereesToSave = [];
		try {
			if (ShowRefereeEntity::NOT_SELECTED != $arrayValues['rID']) {
				if (isset($arrayValues['Plemeno']) && isset($arrayValues['Trida'])) {
					foreach ($arrayValues['Plemeno'] as $key => $value) {
						foreach ($arrayValues['Trida'] as $keyTrida => $valueTrida) {
							$ref = new ShowRefereeEntity();
							$ref->setPlemeno($key);
							$ref->setRID($arrayValues['rID']);
							$ref->setTrida($keyTrida);
							$ref->setVID($arrayValues['vID']);
							$refereesToSave[] = $ref;
						}
					}
					$this->showRefereeRepository->saveReferees($refereesToSave);
					$this->flashMessage(REFEREE_SAVED, "alert-success");
					$this->redirect("detail", $arrayValues['vID']);
				}
			}
			$this->flashMessage(REFEREE_EMPTY_SAVE, "alert-danger");
			$this->redirect("detail", $arrayValues['vID']);
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				$form->addError(REFEREE_SAVED_FAILED);
				$this->flashMessage(REFEREE_SAVED_FAILED, "alert-danger");
			}
		}
	}

	public function createComponentShowDogForm() {
		$form = $this->showDogForm->create($this->link("detail"), $this->langRepository->getCurrentLang($this->session));
		$form->onSubmit[] = [$this, 'saveShowDog'];

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-md-6';
		$renderer->wrappers['label']['container'] = 'div class="col-md-4 control-label margin5"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function saveShowDog(Form $form) {
		$arrayValues = $form->getHttpData();
		$dogsToSave = [];
		try {
			if (DogRepository::NOT_SELECTED != $arrayValues['pID']) {
				if (isset($arrayValues['Titul'])) {
					foreach ($arrayValues['Titul'] as $key => $value) {
						$showDogEntity = new ShowDogEntity();
						$showDogEntity->setPID($arrayValues['pID']);
						$showDogEntity->setVID($arrayValues['vID']);
						$showDogEntity->setTrida($arrayValues['Trida']);
						$showDogEntity->setOceneni($arrayValues['Oceneni']);
						$showDogEntity->setPoradi($arrayValues['Poradi']);
						$showDogEntity->setTitulyDodatky($arrayValues['TitulyDodatky']);
						$showDogEntity->setTitul($key);
						$dogsToSave[] = $showDogEntity;
					}
				} else {
					$showDogEntity = new ShowDogEntity();
					$showDogEntity->setPID($arrayValues['pID']);
					$showDogEntity->setVID($arrayValues['vID']);
					$showDogEntity->setTrida($arrayValues['Trida']);
					$showDogEntity->setOceneni($arrayValues['Oceneni']);
					$showDogEntity->setPoradi($arrayValues['Poradi']);
					$showDogEntity->setTitulyDodatky($arrayValues['TitulyDodatky']);
					$showDogEntity->setTitul(null);
					$dogsToSave[] = $showDogEntity;
				}

				$this->showDogRepository->saveDogs($dogsToSave);
				$this->flashMessage(SHOW_DOG_SAVED, "alert-success");
				$this->redirect("detail", $arrayValues['vID']);
			}
			$this->flashMessage(DOG_EMPTY_SAVE, "alert-danger");
			$this->redirect("detail", $arrayValues['vID']);
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				$form->addError(REFEREE_SAVED_FAILED);
				$this->flashMessage(REFEREE_SAVED_FAILED, "alert-danger");
			}
		}
	}

	/**
	 * @param int $id
	 */
	public function actionDelete($id) {
		if ($this->showRepository->delete($id)) {
			$this->flashMessage(SHOW_DELETED, "alert-success");
		} else {
			$this->flashMessage(SHOW_DELETED_FAILED, "alert-danger");
		}
		$this->redirect('default');
	}

	public function createComponentEditForm() {
		$form = $this->showForm->create($this->link("default"), $this->langRepository->getCurrentLang($this->session));
		$form->onSuccess[] = [$this, 'saveShow'];

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function saveShow(Form $form) {
		$showEntity = new ShowEntity();
		try {
			$array = $form->getHttpData();
			$array["Rozhodci"] = implode(RefereeEntity::REFEREE_SHOW_DELIMITER, $array["Rozhodci"]);
			$showEntity->hydrate($array);
			$this->showRepository->save($showEntity);
			$this->flashMessage(SHOW_FORM_NEW_ADDED, "alert-success");
			$this->redirect("default");
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				$form->addError(SHOW_FORM_NEW_FAILED);
				$this->flashMessage(SHOW_FORM_NEW_FAILED, "alert-danger");
			}
		}
	}

	/**
	 * @param int $id
	 */
	public function actionEdit($id) {
		$showEntity = $this->showRepository->getShow($id);
		$this->template->show = $showEntity;
		$this->template->lang = $this->langRepository->getCurrentLang($this->session);

		if ($showEntity) {
			$this['editForm']->addHidden('ID', $showEntity->getID());
			$array = $showEntity->extract();
			$array["Rozhodci"] = explode(RefereeEntity::REFEREE_SHOW_DELIMITER, $array["Rozhodci"]);
			$this['editForm']->setDefaults($array);
			if ($showEntity->getDatum() != null) {
				$this['editForm']['Datum']->setDefaultValue($showEntity->getDatum()->format(ShowEntity::MASKA_DATA));
			}
		}
	}

}