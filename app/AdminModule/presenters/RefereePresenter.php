<?php

namespace App\AdminModule\Presenters;

use App\Forms\RefereeForm;
use App\Model\Entity\RefereeEntity;
use App\Model\RefereeRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;

class RefereePresenter extends SignPresenter {

	/**
	 * @var RefereeRepository
	 */
	private $refereeRepository;

	/**
	 * @var RefereeForm
	 */
	private $refereeForm;

	public function __construct(RefereeRepository $refereeRepository, RefereeForm $refereeForm) {
		$this->refereeRepository = $refereeRepository;
		$this->refereeForm = $refereeForm;
	}

	public function actionDefault($id) {
		$this->template->referees = $this->refereeRepository->findReferees();
	}

	public function actionDelete($id) {
		if (!empty($id)) {
			$this->refereeRepository->delete($id);
		}
		$this->redirect("default");
	}

	public function actionEdit($id) {
		if ($id == null) {
			$this->template->referee = null;
		} else {
			$referee = $this->refereeRepository->getReferee($id);
			$this->template->referee = $referee;
			if ($referee) {
				$this['editForm']->setDefaults($referee->extract());
				$this['editForm']->addHidden('ID', $referee->getID());
			}
		}
	}

	public function createComponentEditForm() {
		$form = $this->refereeForm->create($this->link("default"));
		$form->onSuccess[] = [$this, 'saveVet'];

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function saveVet(Form $form) {
		$refereeEntity = new RefereeEntity();
		try {
			$refereeEntity->hydrate($form->getHttpData());
			$this->refereeRepository->saveReferee($refereeEntity);
			$this->flashMessage(REFEREE_ADDED, "alert-success");
			$this->redirect("default");
		} catch (\Exception $e) {
			if ($e instanceof AbortException) {
				throw $e;
			} else {
				$form->addError(REFEREE_ADDED_FAILED);
				$this->flashMessage(REFEREE_ADDED_FAILED, "alert-danger");
			}
		}
	}
}