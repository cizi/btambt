<?php

namespace App\FrontendModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Forms\LitterApplicationFilterForm;
use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\LitterApplicationRepository;
use Nette\Forms\Form;

class FeItem1velord4Presenter extends FrontendPresenter {

	/** @persistent */
	public $filter;

	/** @var LitterApplicationRepository */
	private $litterApplicationRepository;

	/** @var EnumerationRepository */
	private $enumRepository;

	/** @var DogRepository */
	private $dogRepository;

	/** @var  LitterApplicationFilterForm */
	private $litterApplicationFilterForm;

	/**
	 * FeItem1velord4Presenter constructor.
	 * @param LitterApplicationRepository $litterApplicationRepository
	 * @param EnumerationRepository $enumerationRepository
	 * @param DogRepository $dogRepository
	 * @param LitterApplicationFilterForm $litterApplicationFilterForm
	 */
	public function __construct(
		LitterApplicationRepository $litterApplicationRepository,
		EnumerationRepository $enumerationRepository,
		DogRepository $dogRepository,
		LitterApplicationFilterForm $litterApplicationFilterForm
	) {
		$this->litterApplicationRepository = $litterApplicationRepository;
		$this->enumRepository = $enumerationRepository;
		$this->dogRepository = $dogRepository;
		$this->litterApplicationFilterForm = $litterApplicationFilterForm;
	}

	public function startup() {
		parent::startup();
		$this->template->amIAdmin = ($this->getUser()->isLoggedIn() && $this->getUser()->getRoles()[0] == UserRoleEnum::USER_ROLE_ADMINISTRATOR);
	}

	public function actionDefault() {
		$filter = $this->decodeFilterFromQuery();
		$this['litterApplicationFilterForm']->setDefaults($filter);

		$applications = $this->litterApplicationRepository->findLitterApplications($filter);
		$this->template->currentLang = $this->langRepository->getCurrentLang($this->session);
		$this->template->enumRepo = $this->enumRepository;
		$this->template->dogRepo = $this->dogRepository;
		$this->template->applications = $applications;

		$formData = [];
		foreach($applications as $application) {
			$data = $application->getDataDecoded();
			$formData[$application->getID()]['males'] = (isset($data['porozenoPsu']) ? $data['porozenoPsu'] : "-");
			$formData[$application->getID()]['females'] = (isset($data['porozenoFen']) ? $data['porozenoFen'] : "-");
			$formData[$application->getID()]['birthMales'] = ($data['kzapisuPsu'] !="" && ($data['porozenoPsu'] != $data['kzapisuPsu']) ? " (" . $data['kzapisuPsu'] . ")" : "");
			$formData[$application->getID()]['birthFemales'] = ($data['kzapisuFen'] !="" && ($data['porozenoFen'] != $data['kzapisuFen']) ? " (" . $data['kzapisuFen'] . ")" : "");
			$formData[$application->getID()]['chs'] = (isset($data['chs']) ? $data['chs'] : "");
			//$formData[$application->getID()]['birth'] = (isset($data['datumnarozeni']) ? \DateTime::createFromFormat("j.n.Y", $data['datumnarozeni']) : null);
			$formData[$application->getID()]['birth'] = ($application->getDatumNarozeni() != null ? $application->getDatumNarozeni() : null);
		}
		$this->template->formData = $formData;
	}

	public function createComponentLitterApplicationFilterForm() {
		$form = $this->litterApplicationFilterForm->create($this->langRepository->getCurrentLang($this->session));
		$form->onSubmit[] = [$this, 'litterApplicationFilterSubmit'];

		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=col-md-3';
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
		foreach ($form->getHttpData() as $key => $value) {
			if ($value != "") {
				$filter .= $key . "=" . $value . "&";
			}
		}
		$this->filter = $filter;
		$this->redirect("default");
	}
}