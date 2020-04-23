<?php

namespace App\FrontendModule\Presenters;

use App\Forms\CoverageMatingListDetailForm;
use App\Forms\MatingListDetailForm;
use App\Forms\MatingListForm;
use App\Model\DogRepository;
use App\Model\Entity\DogEntity;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;
use Dibi\Exception;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use Nette\Utils\ArrayHash;

class FeItem2velord16Presenter extends FrontendPresenter {

	/** @var  MatingListForm */
	private $matingListForm;

	/** @var  DogRepository */
	private $dogRepository;

	/** @var  MatingListDetailForm */
	private $matingListDetailForm;

	/** @var  EnumerationRepository */
	private $enumerationRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var CoverageMatingListDetailForm */
	private $coverageMatingListDetailForm;

	public function __construct(
		MatingListForm $matingListForm,
		DogRepository $dogRepository,
		MatingListDetailForm $matingListDetailForm,
		EnumerationRepository $enumerationRepository,
		UserRepository $userRepository,
		CoverageMatingListDetailForm $coverageMatingListDetailForm
	) {
		$this->matingListForm = $matingListForm;
		$this->dogRepository = $dogRepository;
		$this->matingListDetailForm = $matingListDetailForm;
		$this->enumerationRepository = $enumerationRepository;
		$this->userRepository = $userRepository;
		$this->coverageMatingListDetailForm = $coverageMatingListDetailForm;
	}

	public function actionDefault() {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
	}

	public function createComponentMatingListForm() {
		$form = $this->matingListForm->create($this->langRepository->getCurrentLang($this->session));

		$form["save"]->caption = MATING_FORM_SAVE1;
		$form->addSubmit("save2", MATING_FORM_SAVE2)
			->setAttribute("class", "btn btn-primary margin10");

		$form->onSubmit[] = [$this, 'submitMatingList'];

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

	public function createComponentMatingListDetailForm() {
		$form = $this->matingListDetailForm->create($this->langRepository->getCurrentLang($this->session), $this->link("default"));
		$form->onSubmit[] = [$this, 'submitMatingListDetail'];

		return $form;
	}

	/**
	 * Potvrzení formuláře krycího listu rozhodne co se bude dít
	 * @param Form $form
	 */
	public function submitMatingList(Form $form) {
		$values = $form->getHttpData();
		if (!empty($values['cID']) && !empty($values['pID']) && !empty($values['fID'])) {
			if (isset($values['save'])) {	// I. hlášení o krytí
				$this->redirect("coverage", [$values['cID'], $values['pID'], $values['fID']]);
			}
			if (isset($values['save2'])) { // II. hlášení o vrhu
				$this->redirect("mating", [$values['cID'], $values['pID'], $values['fID']]);
			}
		}
		$this->redirect(':Frontend:Homepage:default');
	}

	/**
	 * @param Form $form
	 */
	public function submitMatingListDetail(Form $form) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
		try {
			$currentLang = $this->langRepository->getCurrentLang($this->session);
			$latte = new \Latte\Engine();
			$latte->setTempDirectory(__DIR__ . '/../../../temp/cache');

			$latteParams = [];
			foreach ($form->getValues() as $inputName => $value) {
				if ($value instanceof ArrayHash) {
					foreach ($value as $dogInputName => $dogValue) {
						if ($dogInputName == 'Barva') {
							$latteParams[$inputName . $dogInputName] = $this->enumerationRepository->findEnumItemByOrder($currentLang,
								$dogValue);
						} else {
							$latteParams[$inputName . $dogInputName] = $dogValue;
						}
					}
				} else {
					if ($inputName == 'Plemeno') {
						$latteParams[$inputName] = $this->enumerationRepository->findEnumItemByOrder($currentLang, $value);
					} else {
						$latteParams[$inputName] = $value;
					}
				}
			}
			$latteParams['basePath'] = $this->getHttpRequest()->getUrl()->getBaseUrl();
			$latteParams['title'] = $this->enumerationRepository->findEnumItemByOrder($currentLang,
				$form->getValues()['cID']);

			$template = $latte->renderToString(__DIR__ . '/../templates/FeItem2velord16/matingPdf.latte', $latteParams);

			$pdf = new \Joseki\Application\Responses\PdfResponse($template);
			$pdf->documentTitle = MATING_FORM_CLUB . "_" . date("Y-m-d_His");
			$this->sendResponse($pdf);
		} catch (AbortException $e) {
			throw $e;
		} catch (\Exception $e) {
			dump($e); die;
		}
	}

	/**
	 * Formulař prvního kroku krycíholistu
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentCoverageMatingListDetailForm() {
		$form = $this->coverageMatingListDetailForm->create($this->langRepository->getCurrentLang($this->session), $this->link("default"));
		$form->onSubmit[] = [$this, 'submitCoverageMatingListDetail'];

		return $form;
	}

	/**
	 * @param int $cID
	 * @param int $pID
	 * @param int $fID
	 */
	public function actionCoverage($cID, $pID, $fID) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
		$pes = $this->dogRepository->getDog($pID);
		$this['coverageMatingListDetailForm']['cID']->setDefaultValue($cID);
		$this['coverageMatingListDetailForm']['pID']->setDefaults($pes->extract());
		$this['coverageMatingListDetailForm']['pID']['Jmeno']->setDefaultValue(trim($pes->getTitulyPredJmenem() . " " . $pes->getJmeno() . " " . $pes->getTitulyZaJmenem()));
		if ($pes->getDatNarozeni() != null) {
			$this['coverageMatingListDetailForm']['pID']['DatNarozeni']->setDefaultValue($pes->getDatNarozeni()->format(DogEntity::MASKA_DATA));
		}

		$maleOwnersToInput = "";
		$maleOwnersTelToInput = "";
		$maleOwners = $this->userRepository->findDogOwnersAsUser($pes->getID());
		for($i=0; $i<count($maleOwners); $i++) {
			$maleOwnersToInput .= $maleOwners[$i]->getFullName() . (($i+1) != count($maleOwners) ? ", " : "");
			$maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
		}
		$this['coverageMatingListDetailForm']['MajitelPsa']->setDefaultValue($maleOwnersToInput);
		$this['coverageMatingListDetailForm']['MajitelPsaTel']->setDefaultValue($maleOwnersTelToInput);

		$fena = $this->dogRepository->getDog($fID);
		$this['coverageMatingListDetailForm']['fID']->setDefaults($fena->extract());
		$this['coverageMatingListDetailForm']['fID']['Jmeno']->setDefaultValue(trim($fena->getTitulyPredJmenem() . " " . $fena->getJmeno() . " " . $fena->getTitulyZaJmenem()));
		if ($fena->getDatNarozeni() != null) {
			$this['coverageMatingListDetailForm']['fID']['DatNarozeni']->setDefaultValue($fena->getDatNarozeni()->format(DogEntity::MASKA_DATA));
		}

		$femaleOwnersToInput = "";
		$femaleOwnersTelToInput = "";
		$femaleOwners = $this->userRepository->findDogOwnersAsUser($fena->getID());
		for($i=0; $i<count($femaleOwners); $i++) {
			$femaleOwnersToInput .= $femaleOwners[$i]->getFullName() . (($i+1) != count($femaleOwners) ? ", " : "");
			$femaleOwnersTelToInput .= $femaleOwners[$i]->getPhone() . (($i+1) != count($femaleOwners) ? ", " : "");
		}
		$this['coverageMatingListDetailForm']['MajitelFeny']->setDefaultValue($femaleOwnersToInput);
		$this['coverageMatingListDetailForm']['MajitelFenyTel']->setDefaultValue($femaleOwnersTelToInput);

		$this->template->title = $this->enumerationRepository->findEnumItemByOrder($this->langRepository->getCurrentLang($this->session), $cID);
		$this->template->cID = $cID;
	}

	/**
	 * @param Form $form
	 */
	public function submitCoverageMatingListDetail(Form $form) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
		try {
			$currentLang = $this->langRepository->getCurrentLang($this->session);
			$latte = new \Latte\Engine();
			$latte->setTempDirectory(__DIR__ . '/../../../temp/cache');

			$latteParams = [];
			foreach ($form->getValues() as $inputName => $value) {
				if ($value instanceof ArrayHash) {
					foreach ($value as $dogInputName => $dogValue) {
						if ($dogInputName == 'Barva') {
							$latteParams[$inputName . $dogInputName] = $this->enumerationRepository->findEnumItemByOrder($currentLang,
								$dogValue);
						} else {
							$latteParams[$inputName . $dogInputName] = $dogValue;
						}
					}
				} else {
					if ($inputName == 'Plemeno') {
						$latteParams[$inputName] = $this->enumerationRepository->findEnumItemByOrder($currentLang, $value);
					} else {
						$latteParams[$inputName] = $value;
					}
				}
			}

			$latteParams['basePath'] = $this->getHttpRequest()->getUrl()->getBaseUrl();
			$latteParams['title'] = $this->enumerationRepository->findEnumItemByOrder($currentLang, $form->getValues()['cID']);

			$template = $latte->renderToString(__DIR__ . '/../templates/FeItem2velord16/coveragePdf.latte', $latteParams);

			$pdf = new \Joseki\Application\Responses\PdfResponse($template);
			$pdf->documentTitle = MATING_FORM_CLUB . "_I_" . date("Y-m-d_His");
			$this->sendResponse($pdf);
		} catch (AbortException $e) {
			throw $e;
		} catch (\Exception $e) {
			// dump($e); die;
		}
	}

	/**
	 * @param int $cID
	 * @param int $pID
	 * @param int $fID
	 */
	public function actionMating($cID, $pID, $fID) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
		$pes = $this->dogRepository->getDog($pID);
		$this['matingListDetailForm']['cID']->setDefaultValue($cID);
		$this['matingListDetailForm']['pID']->setDefaults($pes->extract());
		$this['matingListDetailForm']['pID']['Jmeno']->setDefaultValue(trim($pes->getTitulyPredJmenem() . " " . $pes->getJmeno() . " " . $pes->getTitulyZaJmenem()));
		if ($pes->getDatNarozeni() != null) {
			$this['matingListDetailForm']['pID']['DatNarozeni']->setDefaultValue($pes->getDatNarozeni()->format(DogEntity::MASKA_DATA));
		}

		$maleOwnersToInput = "";
		$maleOwners = $this->userRepository->findDogOwnersAsUser($pes->getID());
		for($i=0; $i<count($maleOwners); $i++) {
			$maleOwnersToInput .= $maleOwners[$i]->getFullName() . (($i+1) != count($maleOwners) ? ", " : "");
		}
		$this['matingListDetailForm']['MajitelPsa']->setDefaultValue($maleOwnersToInput);

		$fena = $this->dogRepository->getDog($fID);
		$this['matingListDetailForm']['fID']->setDefaults($fena->extract());
		$this['matingListDetailForm']['fID']['Jmeno']->setDefaultValue(trim($fena->getTitulyPredJmenem() . " " . $fena->getJmeno() . " " . $fena->getTitulyZaJmenem()));
		if ($fena->getDatNarozeni() != null) {
			$this['matingListDetailForm']['fID']['DatNarozeni']->setDefaultValue($fena->getDatNarozeni()->format(DogEntity::MASKA_DATA));
		}

		$femaleOwnersToInput = "";
		$femaleOwnersTelToInput = "";
		$femaleOwners = $this->userRepository->findDogOwnersAsUser($fena->getID());
		for($i=0; $i<count($femaleOwners); $i++) {
			$femaleOwnersToInput .= $femaleOwners[$i]->getFullName() . (($i+1) != count($femaleOwners) ? ", " : "");
			$femaleOwnersTelToInput .= $femaleOwners[$i]->getPhone() . (($i+1) != count($femaleOwners) ? ", " : "");
		}
		$this['matingListDetailForm']['MajitelFeny']->setDefaultValue($femaleOwnersToInput);
		$this['matingListDetailForm']['MajitelFenyTel']->setDefaultValue($femaleOwnersTelToInput);

		$this->template->title = $this->enumerationRepository->findEnumItemByOrder($this->langRepository->getCurrentLang($this->session), $cID);
		$this->template->cID = $cID;
	}
}