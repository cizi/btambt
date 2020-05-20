<?php

namespace App\FrontendModule\Presenters;

use App\Controller\EmailController;
use App\Enum\LitterApplicationStateEnum;
use App\Enum\StateEnum;
use App\Enum\UserRoleEnum;
use App\Forms\LitterApplicationDetailForm;
use App\Forms\LitterApplicationForm;
use App\Model\DogRepository;
use App\Model\Entity\DogEntity;
use App\Model\Entity\LitterApplicationEntity;
use App\Model\EnumerationRepository;
use App\Model\LitterApplicationRepository;
use App\Model\UserRepository;
use App\Model\WebconfigRepository;
use Dibi\DateTime;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use App\Forms\MatingListDetailForm;

class FeItem1velord9Presenter extends FrontendPresenter {

	/** @var  LitterApplicationForm */
	private $litterApplicationForm;

	/** @var  DogRepository */
	private $dogRepository;

	/** @var  LitterApplicationDetailForm */
	private $litterApplicationDetailForm;

	/** @var  EnumerationRepository */
	private $enumerationRepository;

	/** @var LitterApplicationRepository */
	private $litterApplicationRepository;

	/** @var UserRepository */
    private $userRepository;
    
    /** @var  MatingListDetailForm */
	private $matingListDetailForm;

	/**
	 * FeItem2velord17Presenter constructor.
	 * @param LitterApplicationForm $litterApplicationForm
	 * @param DogRepository $dogRepository
	 * @param LitterApplicationDetailForm $litterApplicationDetailForm
	 * @param EnumerationRepository $enumerationRepository
	 * @param LitterApplicationRepository $litterApplicationRepository
	 * @param UserRepository $userRepository
	 */
	public function __construct(
		LitterApplicationForm $litterApplicationForm,
		DogRepository $dogRepository,
		LitterApplicationDetailForm $litterApplicationDetailForm,
		EnumerationRepository $enumerationRepository,
		LitterApplicationRepository $litterApplicationRepository,
        UserRepository $userRepository,
        MatingListDetailForm $matingListDetailForm
	) {
		$this->litterApplicationForm = $litterApplicationForm;
		$this->dogRepository = $dogRepository;
		$this->litterApplicationDetailForm = $litterApplicationDetailForm;
		$this->enumerationRepository = $enumerationRepository;
		$this->litterApplicationRepository = $litterApplicationRepository;
        $this->userRepository = $userRepository;
        $this->matingListDetailForm = $matingListDetailForm;
	}

	public function startup() {
		parent::startup();
		$this->template->amIAdmin = ($this->getUser()->isLoggedIn() && $this->getUser()->getRoles()[0] == UserRoleEnum::USER_ROLE_ADMINISTRATOR);
	}

	public function actionDefault($id) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
		// pokud mám ID jde o editaci
		$litterApplication = $this->litterApplicationRepository->getLitterApplication($id);
		if (!empty($litterApplication)) {
			$data = $litterApplication->getDataDecoded();
			if (isset($this['litterApplicationForm']['pID']->items[$data['oID']]))  {
				$this['litterApplicationForm']['pID']->setDefaultValue($data['oID']);
			} else {
				$this->flashMessage(sprintf(LITTER_APPLICATION_MID_OID_FAILED_TITLE, $data['oID']), "alert-danger");
			}
			if (isset($this['litterApplicationForm']['fID']->items[$data['mID']]))  {
				$this['litterApplicationForm']['fID']->setDefaultValue($data['mID']);
			} else {
				$this->flashMessage(sprintf(LITTER_APPLICATION_MID_OID_FAILED_TITLE, $data['mID']), "alert-danger");
			}
			$this['litterApplicationForm']->addHidden('ID')->setValue($id);	// ID záznamu vrhu
		}
	}

	public function createComponentLitterApplicationForm() {
		$form = $this->litterApplicationForm->create($this->langRepository->getCurrentLang($this->session));
		$form->onSubmit[] = [$this, 'verifyLitterApplication'];

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

	public function createComponentLitterApplicationDetailForm() {
		$form = $this->litterApplicationDetailForm->create($this->langRepository->getCurrentLang($this->session), $this->link("default"));
		$form->onSubmit[] = [$this, 'submitLitterApplicationDetail'];

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function verifyLitterApplication(Form $form) {
        $values = $form->getHttpData();
        
		if (!empty($values['pID']) && !empty($values['fID']) && !empty($values['cID'])) {
            if (isset($values['save'])) {   // II. Přihláška k zápisu vrhu
                $this->redirect("mating", [$values['cID'], $values['pID'], $values['fID']]);
            }
            
            if (isset($values['save2'])) { // Hlášení vrhu
                $idForEdit = (isset($values['ID']) ? $values['ID'] : null);
                $this->redirect("details", [$values['cID'], $values['pID'], $values['fID'], $idForEdit]);
            }
        }      
        $this->redirect(':Frontend:Homepage:default');
	}

	/**
	 * @param Form $form
	 */
	public function submitLitterApplicationDetail(Form $form) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
		try {
			$array = $form->getHttpData();
			$litterApplicationEntity = new LitterApplicationEntity();
			$litterApplicationEntity->hydrate($array);

			$latteParams = $array;
			$latteParams['basePath'] = $this->getHttpRequest()->getUrl()->getBaseUrl();
			$latteParams['puppiesLines'] = LitterApplicationDetailForm::NUMBER_OF_LINES;
			$latteParams['enumRepository'] = $this->enumerationRepository;
			$latteParams['currentLang'] = $this->langRepository->getCurrentLang($this->session);

			$latte = new \Latte\Engine();
			$latte->setTempDirectory(__DIR__ . '/../../../temp/cache');
			$template = $latte->renderToString(__DIR__ . '/../templates/FeItem2velord17/pdf.latte', $latteParams);

			$data = base64_encode(gzdeflate(serialize($_POST)));
			$litterApplicationEntity->setData($data);
			$formular = base64_encode(gzdeflate($template));
			$litterApplicationEntity->setFormular($formular);
			$litterApplicationEntity->setDatum(new DateTime());
			$litterApplicationEntity->setDatumNarozeni(new DateTime($array["datumnarozeni"]));	// srovnání indexu DB vs formulář
			if (empty($array['ID'])) {
				$litterApplicationEntity->setZavedeno(LitterApplicationStateEnum::INSERT);
			}
			if ($litterApplicationEntity->getPlemeno() == 0) {
				$litterApplicationEntity->setPlemeno(null);
			}
			//dump($litterApplicationEntity); die;
			$this->litterApplicationRepository->save($litterApplicationEntity);

			// email pro admina/y
			$userEntity = $this->userRepository->getUser($this->user->getId());
			$emailTo = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
			$body = sprintf(LITTER_APPLICATION_CREATE_BODY, $litterApplicationEntity->getFormularDecoded());
			EmailController::SendPlainEmail($userEntity->getEmail(), $emailTo, LITTER_APPLICATION_CREATE_SUBJECT, $body);

			$this->flashMessage(LITTER_APPLICATION_SAVED, "alert-success");
			$this->redirect("litterFinalization", $litterApplicationEntity->getID());
		} catch (AbortException $e) {
			throw $e;
		} catch (\Exception $e) {
			// dump($e); die;
			$this->flashMessage(LITTER_APPLICATION_SAVE_FAILED, "alert-danger");
		}
	}

	/**
	 * @param int $cID
	 * @param int $pID
	 * @param int $fID
	 * @param int $id
	 * @throws AbortException
	 */
	public function actionDetails($cID, $pID, $fID, $id = null) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
		if (!empty($id)) {	// pokud máme ID přihlášky vrhu, načteme jeji její data do fromu
			$litterApplication = $this->litterApplicationRepository->getLitterApplication($id);
			if (!empty($litterApplication)) {
				$data = $litterApplication->getDataDecoded();
				$this['litterApplicationDetailForm']->setDefaults($data);
				$this['litterApplicationDetailForm']->addHidden('ID')->setValue($id);	// DB
				$this['litterApplicationDetailForm']->addHidden('Zavedeno')->setValue($litterApplication->getZavedeno());	// DB
			}
		}

		// a pak je začneme následně přepisovat
		$title = $this->enumerationRepository->findEnumItemByOrder($this->langRepository->getCurrentLang($this->session), $cID);
		// nastavíme hidny
		$this['litterApplicationDetailForm']['cID']->setDefaultValue($cID);
		$this['litterApplicationDetailForm']['title']->setDefaultValue($title);

		$this['litterApplicationDetailForm']['oID']->setDefaultValue($pID);
		$this['litterApplicationDetailForm']['mID']->setDefaultValue($fID);
		$clubName = $this->enumerationRepository->findEnumItemByOrder($this->langRepository->getCurrentLang($this->session), $cID);
		$this['litterApplicationDetailForm']['Klub']->setDefaultValue($clubName);

		$femaleOwners = $this->userRepository->findDogOwnersAsEntities($fID);
		$appBreeder = $this->userRepository->getUser($this->getUser()->getId());
		$femaleOwnsId = "";
		if (!empty($femaleOwners)) {
			$appBreeder = $this->userRepository->getUser($femaleOwners[0]->getUID());
			if (count($femaleOwners) == 1) {
				$femaleOwnsId = $femaleOwners[0]->getUID();
			} else {
				foreach ($femaleOwners as $owner) {
					$femaleOwnsId .= $owner->getUID() . ",";
				}
			}
		}
		$this['litterApplicationDetailForm']['MajitelFeny']->setDefaultValue($femaleOwnsId);
		if ($appBreeder != null) {	 // z přihlášky vrhu je chovatelem majitel feny a pokud nebyl nalezen tak ten kdo to hlásí
			$breederName = trim($appBreeder->getTitleBefore() . " " . $appBreeder->getName() . " " . $appBreeder->getSurname());
			$stateEnum = new StateEnum();
			$breederState = $stateEnum->getValueByKey($appBreeder->getState());
			$breederAddress = $appBreeder->getStreet() . " " . $appBreeder->getCity() . " " . $breederState . ", " . $appBreeder->getEmail();
			$this['litterApplicationDetailForm']['chs']->setDefaultValue($appBreeder->getStation());
			$this['litterApplicationDetailForm']['chovatel']->setDefaultValue($breederName . "; " . $appBreeder->getStation() . "; " . $breederAddress);
		}
		/*$femaleBreeder = $this->userRepository->getBreederByDogAsUser($fID);
		if ($femaleBreeder != null) {
			$this['litterApplicationDetailForm']['chs']->setDefaultValue($femaleBreeder->getStation());
		}*/

		$pes = $this->dogRepository->getDog($pID);
		$name = trim($pes->getTitulyPredJmenem() . " " . $pes->getJmeno() . " " . $pes->getTitulyZaJmenem());
		$this['litterApplicationDetailForm']['otec']->setDefaultValue($name);
		$this['litterApplicationDetailForm']['otecBarva']->setDefaultValue($pes->getBarva());
		$this['litterApplicationDetailForm']['otecSrst']->setDefaultValue($pes->getSrst());
		$this['litterApplicationDetailForm']['otecBon']->setDefaultValue($pes->getBonitace());
		// $this['litterApplicationDetailForm']['otecHeight']->setDefaultValue($pes->getVyska());
		$this['litterApplicationDetailForm']['otecPP']->setDefaultValue($pes->getCisloZapisu());
		if ($pes->getDatNarozeni() != null) {
			$this['litterApplicationDetailForm']['otecDN']->setDefaultValue($pes->getDatNarozeni()->format(DogEntity::MASKA_DATA));
		}

		$fena = $this->dogRepository->getDog($fID);
		$name = trim($fena->getTitulyPredJmenem() . " " . $fena->getJmeno() . " " . $fena->getTitulyZaJmenem());
		$this['litterApplicationDetailForm']['matka']->setDefaultValue($name);
		$this['litterApplicationDetailForm']['matkaBarva']->setDefaultValue($fena->getBarva());
		$this['litterApplicationDetailForm']['matkaSrst']->setDefaultValue($fena->getSrst());
		$this['litterApplicationDetailForm']['matkaBon']->setDefaultValue($fena->getBonitace());
		// $this['litterApplicationDetailForm']['matkaHeight']->setDefaultValue($fena->getVyska());
		$this['litterApplicationDetailForm']['matkaPP']->setDefaultValue($fena->getCisloZapisu());
		if ($fena->getDatNarozeni() != null) {
			$this['litterApplicationDetailForm']['matkaDN']->setDefaultValue($fena->getDatNarozeni()->format(DogEntity::MASKA_DATA));
		}
		if ($fena->getPlemeno() != null) {
			$this['litterApplicationDetailForm']['Plemeno']->setDefaultValue($fena->getPlemeno());
		}

		$this->template->puppiesLines = LitterApplicationDetailForm::NUMBER_OF_LINES;
		$this->template->title = $title;
		$this->template->cID = $cID;
    }
    
    public function createComponentMatingListDetailForm() {
		$form = $this->matingListDetailForm->create($this->langRepository->getCurrentLang($this->session), $this->link("default"));
		$form->onSubmit[] = [$this, 'submitMatingListDetail'];

		return $form;
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
            $latteParams["dateFormat"] = DogEntity::MASKA_DATA_ZOBRAZENI;
			$latteParams['basePath'] = $this->getHttpRequest()->getUrl()->getBaseUrl();
			$latteParams['title'] = $this->enumerationRepository->findEnumItemByOrder($currentLang, $form->getValues()['cID']);

			$template = $latte->renderToString(__DIR__ . '/../templates/FeItem1velord9/matingPdf.latte', $latteParams);

			$pdf = new \Joseki\Application\Responses\PdfResponse($template);
			$pdf->documentTitle = MATING_FORM_CLUB . "_" . date("Y-m-d_His");
			$this->sendResponse($pdf);
		} catch (AbortException $e) {
			throw $e;
		} catch (\Exception $e) {
			// dump($e); die; 
		}
	}

	/**
	 * @param int $id
	 */
	public function actionLitterFinalization($id) {
		$litterApplication = $this->litterApplicationRepository->getLitterApplication($id);
		if ($litterApplication != null) {
			$this->template->id = $id;
		} else {
			$message = sprintf(LITTER_APPLICATION_DOES_NOT_EXIST, $id);
			$this->flashMessage($message, "alert-danger");
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
        $this['matingListDetailForm']['Plemeno']->setDefaultValue(($cID == 157 ? 17 : 18));

		$maleOwnersToInput = "";
		$maleOwners = $this->userRepository->findDogOwnersAsUser($pes->getID());
		for($i=0; $i<count($maleOwners); $i++) {
			$maleOwnersToInput .= $maleOwners[$i]->getFullName() . (($i+1) != count($maleOwners) ? ", " : "");
		}
		// $this['matingListDetailForm']['MajitelPsa']->setDefaultValue($maleOwnersToInput);

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