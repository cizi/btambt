<?php

namespace App\FrontendModule\Presenters;

use App\Enum\StateEnum;
use App\Forms\PuppyForm;
use App\Model\DogRepository;
use App\Model\Entity\DogEntity;
use App\Model\Entity\PuppyEntity;
use App\Model\EnumerationRepository;
use App\Model\PuppyRepository;
use App\Model\UserRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;

class FeItem1velord6Presenter extends FrontendPresenter	{

	/** @var DogRepository */
	private $dogRepository;

	/** @var PuppyRepository  */
	private $puppyRepository;

	/** @var PuppyForm  */
	private $puppyForm;

	/** @var EnumerationRepository */
	private $enumRepository;

	/** @var UserRepository */
	private $userRepository;

	/**
	 * FeItem1velord6Presenter constructor.
	 * @param DogRepository $dogRepository
	 * @param PuppyRepository $puppyRepository
	 * @param PuppyForm $puppyForm
	 * @param EnumerationRepository $enumerationRepository
	 * @param UserRepository $userRepository
	 */
	public function __construct(
		DogRepository $dogRepository,
		PuppyRepository $puppyRepository,
		PuppyForm $puppyForm,
		EnumerationRepository $enumerationRepository,
		UserRepository $userRepository
	) {
		$this->dogRepository = $dogRepository;
		$this->puppyRepository = $puppyRepository;
		$this->puppyForm = $puppyForm;
		$this->enumRepository = $enumerationRepository;
		$this->userRepository = $userRepository;
	}

	public function actionDefault() {
		$this->template->currentLang = $this->template->lang = $this->langRepository->getCurrentLang($this->session);
		$this->template->puppies = $this->puppyRepository->findPuppies();
		$this->template->dogRepo = $this->dogRepository;
		$this->template->enumRepo = $this->enumRepository;
		$this->template->userRepo = $this->userRepository;
		$this->template->loggedUserId = $this->getUser()->getId();
		$this->template->isUserLoggedIn = $this->getUser()->isLoggedIn();
		$this->template->stateEnum = new StateEnum();
	}

	/**
	 * @param int $id
	 */
	public function actionEdit($id) {
		if ($id != null) {
			$puppyEntity = $this->puppyRepository->getPuppy($id);
			if ($puppyEntity != null) {
				if ($puppyEntity->getUID() == $this->getUser()->getId()) {
					$this['puppyForm']->setDefaults($puppyEntity->extract());
					if ($puppyEntity->getTermin() != null) {
						$this['puppyForm']['Termin']->setDefaultValue($puppyEntity->getTermin()->format(DogEntity::MASKA_DATA));
					}
				} else {
					$this->redirect("default");
				}
			}
		}
		if ($this->getUser()->isLoggedIn() == false) {
			$this->redirect("default");
		}
	}

	public function createComponentPuppyForm() {
		$form = $this->puppyForm->create($this->langRepository->getCurrentLang($this->session));
		$form->onSubmit[] = [$this, 'submitPuppyForm'];

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
	 * @throws AbortException
	 */
	public function submitPuppyForm(Form $form) {
		try {
			$data = $form->getHttpData();
			$puppyEntity = new PuppyEntity();
			$puppyEntity->hydrate($data);
			$puppyEntity->setUID($this->getUser()->getId());
			$female = $this->dogRepository->getDog($puppyEntity->getMID());
			$puppyEntity->setPlemeno($female->getPlemeno());
			$this->puppyRepository->savePuppy($puppyEntity);

			$this->flashMessage(PUPPY_ADD_OK, "alert-success");
			$this->redirect("default");
		} catch (AbortException $e) {
			throw $e;
		} catch (\Exception $ex) {
			$this->flashMessage(PUPPY_ADD_FAILED, "alert-danger");
		}
	}

	/**
	 * @param int $id
	 */
	public function actionDelete($id) {
		$puppyEntity = $this->puppyRepository->getPuppy($id);
		if ($puppyEntity->getUID() == $this->getUser()->getId()) {
			$this->puppyRepository->deletePuppy($id);
		}

		$this->redirect("default");
	}

}