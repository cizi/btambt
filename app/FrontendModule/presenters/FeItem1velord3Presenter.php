<?php

namespace App\FrontendModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Forms\KinshipVerificationForm;
use App\Model\DogRepository;
use Nette\Forms\Form;

class FeItem1velord3Presenter extends FrontendPresenter {

	/** @var  KinshipVerificationForm */
	private $kinshipVerificationForm;

	/** @var  DogRepository */
	private $dogRepository;

	public function __construct(
		KinshipVerificationForm $kinshipVerificationForm,
		DogRepository $dogRepository
	) {
		$this->kinshipVerificationForm = $kinshipVerificationForm;
		$this->dogRepository = $dogRepository;
	}

	public function actionDefault() {

	}

	public function createComponentKinshipVerificationForm() {
		$form = $this->kinshipVerificationForm->create();
		$form->onSuccess[] = [$this, 'verifyKinship'];

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-md-6';
		$renderer->wrappers['label']['container'] = 'div class="col-md-3 control-label"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=help-block';
		$form->getElementPrototype()->class('form-horizontal');

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function verifyKinship(Form $form) {
		$arrayValues = $form->getHttpData();
		$pID = $arrayValues['pID'];
		$fID = $arrayValues['fID'];
		$lang = $this->langRepository->getCurrentLang($this->session);
		$amIAdmin = ($this->getUser()->isLoggedIn() && $this->getUser()->getRoles()[0] == UserRoleEnum::USER_ROLE_ADMINISTRATOR);

		$this->template->male = $this->dogRepository->getDog($pID);
		$this->template->female = $this->dogRepository->getDog($fID);
		$deepMark = true;
		$this->template->coef = $this->dogRepository->genealogRelationship($pID, $fID);
		$this->template->malePedigree = $this->dogRepository->genealogDeepPedigree($pID, 5, $lang, $this->presenter, $amIAdmin, $deepMark);
		$this->template->femalePedigree = $this->dogRepository->genealogDeepPedigree($fID, 5, $lang, $this->presenter, $amIAdmin, $deepMark);
	}

}