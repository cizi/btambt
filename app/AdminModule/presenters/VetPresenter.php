<?php

namespace App\AdminModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Forms\VetForm;
use App\Model\Entity\VetEntity;
use App\Model\VetRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;

class VetPresenter extends SignPresenter {

	/**
	 * @var VetRepository
	 */
	private $vetRepository;

	/**
	 * @var VetForm
	 */
	private $vetForm;

	public function __construct(VetRepository $vetRepository, VetForm $vetForm) {
		$this->vetRepository = $vetRepository;
		$this->vetForm = $vetForm;
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

	public function actionDefault($id) {
		$this->template->vets = $this->vetRepository->findVets();
	}

	public function actionDelete($id) {
		if (!empty($id)) {
			$this->vetRepository->delete($id);
		}
		$this->redirect("default");
	}

	public function actionEdit($id) {
		if ($id == null) {
			$this->template->vet = null;
		} else {
			$vet = $this->vetRepository->getVet($id);
			$this->template->vet = $vet;
			$this['editForm']->setDefaults($vet->extract());
			if ($vet) {
				$this['editForm']->addHidden('ID', $vet->getID());
			}
		}
	}

	public function createComponentEditForm() {
		$form = $this->vetForm->create($this->link("default"));
		$form->onSuccess[] = [$this, 'saveVet'];

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function saveVet(Form $form) {
		$vetEntity = new VetEntity();
		try {
			$vetEntity->hydrate($form->getHttpData());
			$this->vetRepository->saveVet($vetEntity);
			$this->flashMessage(VET_ADDED, "alert-success");
			$this->redirect("default");
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				$form->addError(VET_ADD_FAILED);
				$this->flashMessage(VET_ADD_FAILED, "alert-danger");
			}
		}
	}
}