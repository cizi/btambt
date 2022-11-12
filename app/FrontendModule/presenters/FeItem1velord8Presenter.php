<?php

namespace App\FrontendModule\Presenters;

use App\Forms\MatingListForm;
use App\Model\DogRepository;
use App\Model\WebconfigRepository;
use App\Model\CoverageApplicationRepository;
use App\Model\Entity\DogEntity;
use App\Model\Entity\CoverageApplicationEntity;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use Nette\FileNotFoundException;
use Mpdf\Mpdf as mPDF;
use Dibi\DateTime;
use App\Model\Entity\CoverageApplicationAttachementEntity;
use App\Controller\FileController;
use App\Controller\EmailController;

class FeItem1velord8Presenter extends FrontendPresenter {

	/** @var  MatingListForm */
	private $matingListForm;

	/** @var  DogRepository */
	private $dogRepository;

	/** @var  EnumerationRepository */
	private $enumerationRepository;

	/** @var UserRepository */
    private $userRepository;
    
    /** @var WebconfigRepository */
    protected $webconfigRepository;

    /** @var CoverageApplicationRepository */
    private $coverageApplicationRepository;

	public function __construct(
		MatingListForm $matingListForm,
		DogRepository $dogRepository,
		EnumerationRepository $enumerationRepository,
        UserRepository $userRepository,
        WebconfigRepository $webconfigRepository,
        CoverageApplicationRepository $coverageApplicationRepository
	) {
		$this->matingListForm = $matingListForm;
		$this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
        $this->userRepository = $userRepository;
        $this->webconfigRepository = $webconfigRepository;
        $this->coverageApplicationRepository = $coverageApplicationRepository;
	} 

	public function actionDefault($id) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsem přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
        }

        $this->template->maxFileSizeInfo = sprintf(MATING_MAX_FILE_SIZE, FileController::MAX_FILE_SIZE);
        if (!empty($id)) {
            $cea = $this->coverageApplicationRepository->getCoverageApplication($id);
            if (!empty($cea)) {
                $this['matingListForm']['cID']->setValue($cea->getPlemeno());
                $this['matingListForm']['fID']->setValue($cea->getMID());
                $this['matingListForm']['pID1']->setValue($cea->getOID1());
                $this['matingListForm']['pID2']->setValue($cea->getOID2());
                $this['matingListForm']['pID3']->setValue($cea->getOID3());
                $this['matingListForm']['Poznamka']->setValue($cea->getPoznamka());
                if ($cea->isExpresni()) {
                    $this['matingListForm']['express']->setDefaultValue("checked");
                }

                $this['matingListForm']->addText("DatumVytvoreniFormat", DOG_FORM_HEALTH_DATE)->setAttribute("class", "form-control")->setAttribute("readonly", "readonly")->setValue($cea->getDatumVytvoreni()->format('j.n.Y'));
                $this['matingListForm']->addText("CisloKL", COUNTER_LITTER_NO)->setAttribute("class", "form-control")->setValue($cea->getCisloKL());
                unset($this['matingListForm']['save']);
                $this['matingListForm']->addHidden("upd", "1");
                $this['matingListForm']->addHidden("DatumVytvoreni", $cea->getDatumVytvoreni());
                $this['matingListForm']->addHidden("recID", $cea->getID());
                $this['matingListForm']->addHidden("uID", $cea->getUID());
                $this['matingListForm']->addSubmit("update", USER_EDIT_SAVE_BTN_LABEL)->setAttribute("class", "btn btn-primary margin10");
            } 
        } else {
            unset($this['matingListForm']['Poznamka']);
        }
        $this->template->id = $id;
	}

	public function createComponentMatingListForm() {
		$form = $this->matingListForm->create($this->langRepository->getCurrentLang($this->session));
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

	/**
	 * Potvrzení formuláře krycího listu rozhodne co se bude dít
	 * @param Form $form
	 */
	public function submitMatingList(Form $form) {
        if ($this->getUser()->isLoggedIn() == false) { // pokud nejsem přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
        }

		$values = $form->getHttpData();
		if (!empty($values['cID']) && !empty($values['fID'])) {
            $supportedFileFormats = ["jpg", "png", "doc", "pdf", "bmp", "docx", "xls", "xlsx"];
            $cID = $values['cID'];
            $fID = $values['fID'];
            $pID1 = $values['pID1'];
            $pID2 = $values['pID2'];
            $pID3 = $values['pID3'];
            $poznamka = $values['Poznamka'] ?? null;

            try {
                $lang = $this->langRepository->getCurrentLang($this->session);
    
                $latteParams = [];
                $latteParams["title"] = $this->enumerationRepository->findEnumItemByOrder($lang, $cID);
                $latteParams["dnes"] = date(DogEntity::MASKA_DATA_ZOBRAZENI);
    
                $maleOwnersToInput1 = "";
                $pes1 = $this->dogRepository->getDog($pID1);
                if ($pes1 != null) {
                    $latteParams["pes1CeleJmeno"] = $pes1->getCeleJmeno();
                    $latteParams["pes1Barva"] = (empty($pes1->getBarva()) ? "" : $this->enumerationRepository->findEnumItemByOrder($lang, $pes1->getBarva()));
                    $latteParams["pes1Nar"] = ($pes1->getDatNarozeni() != null ? $pes1->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                    $latteParams["pes1Cz"] = (!empty($pes1->getCisloZapisu()) ? $pes1->getCisloZapisu() : "");
                    
                    $maleOwners = $this->userRepository->findDogOwnersAsUser($pes1->getID());
                    for($i=0; $i<count($maleOwners); $i++) {
                        $maleOwnersToInput1 .= $maleOwners[$i]->getFullName() . ", " . $maleOwners[$i]->getFullAddress() . (($i+1) != count($maleOwners) ? "; " : "");
                        // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                    }
                } else {
                    $latteParams["pes1CeleJmeno"] = $latteParams["pes1Barva"] = $latteParams["pes1Nar"] = $latteParams["pes1Cz"] = "";
                }            
                $latteParams["pes1Majitele"] = $maleOwnersToInput1;
    
    
                $maleOwnersToInput2 = "";
                $pes2 = $this->dogRepository->getDog($pID2);
                if ($pes2 != null) {
                    $latteParams["pes2CeleJmeno"] = $pes2->getCeleJmeno();
                    $latteParams["pes2Barva"] = (empty($pes2->getBarva()) ? "" : $this->enumerationRepository->findEnumItemByOrder($lang, $pes2->getBarva()));
                    $latteParams["pes2Nar"] = ($pes2->getDatNarozeni() != null ? $pes2->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                    $latteParams["pes2Cz"] = (!empty($pes2->getCisloZapisu()) ? $pes2->getCisloZapisu() : "");
                
                    $maleOwners = $this->userRepository->findDogOwnersAsUser($pes2->getID());
                    for($i=0; $i<count($maleOwners); $i++) {
                        $maleOwnersToInput2 .= $maleOwners[$i]->getFullName() . ", " . $maleOwners[$i]->getFullAddress() . (($i+1) != count($maleOwners) ? "; " : "");
                        // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                    }
                } else {
                    $latteParams["pes2CeleJmeno"] = $latteParams["pes2Barva"] = $latteParams["pes2Nar"] = $latteParams["pes2Cz"] = "";
                }
                $latteParams["pes2Majitele"] = $maleOwnersToInput2;
    
                $maleOwnersToInput3 = "";
                $pes3 = $this->dogRepository->getDog($pID3);
                if ($pes3 != null) {
                    $latteParams["pes3CeleJmeno"] = $pes3->getCeleJmeno();
                    $latteParams["pes3Barva"] = (empty($pes3->getBarva()) ? "" : $this->enumerationRepository->findEnumItemByOrder($lang, $pes3->getBarva()));
                    $latteParams["pes3Nar"] = ($pes3->getDatNarozeni() != null ? $pes3->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                    $latteParams["pes3Cz"] = (!empty($pes3->getCisloZapisu()) ? $pes3->getCisloZapisu() : "");
                    // $maleOwnersTelToInput = "";
                    $maleOwners = $this->userRepository->findDogOwnersAsUser($pes3->getID());
                     for($i=0; $i<count($maleOwners); $i++) {
                         $maleOwnersToInput3 .= $maleOwners[$i]->getFullName() . ", " . $maleOwners[$i]->getFullAddress() . (($i+1) != count($maleOwners) ? "; " : "");
                        // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                     }
                } else {
                    $latteParams["pes3CeleJmeno"] = $latteParams["pes3Barva"] = $latteParams["pes3Nar"] = $latteParams["pes3Cz"] = "";
                }
                $latteParams["pes3Majitele"] = $maleOwnersToInput3;
    
                $latteParams["fena"] = $fena = $this->dogRepository->getDog($fID);
                $latteParams["fenaBarva"] = $this->enumerationRepository->findEnumItemByOrder($lang, $fena->getBarva());
                $latteParams["fenaNar"] = ($fena->getDatNarozeni() != null ? $fena->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                //$latteParams["fenaCz"] = (!empty($fena->getCisloZapisu()) ? $fena->getCisloZapisu() : "");
                        
    
                $femaleOwnersToInput = "";
                $femaleStation = "";
                // $maleOwnersTelToInput = "";
                $femaleOwners = $this->userRepository->findDogOwnersAsUser($fena->getID());
                for($i=0; $i<count($femaleOwners); $i++) {
                    $femaleOwnersToInput .= $femaleOwners[$i]->getFullName() . ", " . $femaleOwners[$i]->getFullAddress() . (($i+1) != count($femaleOwners) ? "; " : "");
                    $femaleStation .= $femaleOwners[$i]->getStation() . (($i+1) != count($femaleOwners) ? "; " : "");
                    // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                }
    
                $latteParams["cisloKrycihoListu"] = "";
    
                $latteParams["fenaMajitele"] = $femaleOwnersToInput;
                $latteParams["fenaStanice"] = $femaleStation;
                $latteParams["cID"] = $cID;
                $latteParams['basePath'] = $this->getHttpRequest()->getUrl()->getBaseUrl();
    
                $latte = new \Latte\Engine();
                $latte->setTempDirectory(__DIR__ . '/../../../temp/cache');
                $template = $latte->renderToString(__DIR__ . '/../templates/FeItem1velord8/matingBtMbtPdf.latte', $latteParams);
    
                $pdf = new mPDF();
                $pdf->ignore_invalid_utf8 = true;
                $pdf->WriteHTML($template);
    
                $timestamp = date("Y-m-d_His");
                $pdfOutput = __DIR__ . "/../../../www/upload/" . MATING_FORM_CLUB . "_" . $timestamp . ".pdf";
                $pdf->Output($pdfOutput);
    
                $attachs = [];
                $mailAttachs = [$pdfOutput];
                foreach($values["attachemets"] as $file) {
                    if (isset($file->name) && ($file->name != "")) {
                        $fileController = new FileController();
                        if ($fileController->isInAllowedSize($file)) {
                            if ($fileController->upload($file, $supportedFileFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
                                $error = true;
                                break;
                            }
                            $cea = new CoverageApplicationAttachementEntity();
                            $cea->setCesta($fileController->getPathDb());
                            $attachs[] = $cea; 
                            $mailAttachs[] = $fileController->getPath();
                        } else {
                            $this->flashMessage(sprintf(MATING_MAX_FILE_SIZE_EXCEEDED, $file->name), "alert-danger");
                        }
                    }
                }

                if (isset($values["express"]) && (count($attachs) == 0)) {
                    throw new FileNotFoundException(COVERAGE_EXPRESS_NO_FILE);
                }

                $ce = new CoverageApplicationEntity();
                $ce->setMID($fID);
                $ce->setOID1(empty($pID1) ? null : $pID1);
                $ce->setOID2(empty($pID2) ? null : $pID2);
                $ce->setOID3(empty($pID3) ? null : $pID3);
                $ce->setPoznamka(empty($poznamka) ? null : $poznamka);
                $ce->setPlemeno($cID);  // tohle je ve skutečnosti název klubu (číselník 18)
                $ce->setExpresni(isset($values["express"]) ? 1 : 0);
                if (isset($values["upd"])) {
                    $ce->setID($values["recID"]);
                    $ce->setCisloKL($values["CisloKL"]);
                    $ce->setDatumVytvoreni($values["DatumVytvoreni"]);
                    $ce->setUID($values["uID"]);
                } else {
                    $ce->setDatumVytvoreni(new Datetime());
                    $ce->setUID($this->getUser()->getId());
                }
                $this->coverageApplicationRepository->save($ce, $attachs);

                if (isset($values["upd"])) {   // jen aktualizace
                    $this->flashMessage(VET_ADDED, "alert-success");
                    $this->redirect("Default", $ce->getID());
                } else {    // zaslání PDF emailem poradci chovu
                    $emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
                    $emailTo = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_BREEDER_CONSULTANT_EMAIL, WebconfigRepository::KEY_LANG_FOR_COMMON);
                    $emailBody = sprintf(COVERAGE_MAIL_BODY, $ce->getID());
                    EmailController::SendPlainEmail($emailFrom, $emailTo, COVERAGE_MAIL_SUBJECT, $emailBody, $mailAttachs);

                    $currentUser = $this->userRepository->getUser($this->getUser()->getId());
                    if (!empty(trim($currentUser->getEmail()))) {
                        EmailController::SendPlainEmail($emailTo, trim($currentUser->getEmail()), COVERAGE_MAIL_SUBJECT, COVERAGE_SAVED_OK);
                    }

                    $this->flashMessage(COVERAGE_SAVED_OK, "alert-success");
                }               
            } catch (AbortException $e) {
                throw $e;
            } catch (FileNotFoundException $e) {
                $this->flashMessage($e->getMessage(), "alert-danger");
            } catch (\Exception $e) {
//                dump($e); die;
                $this->flashMessage(BLOCK_SETTINGS_ITEM_SAVED_FAILED, "alert-danger");
            }
            $this->redirect("Default");
        }
    }
    
    /**
	 * @param int $id
	 * @param int $state
	 */
    public function actionPreview($id, $state) {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsem přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
        }

        $cea = $this->coverageApplicationRepository->getLitterApplication($id);
        if ($cea) {
            try {
                $lang = $this->langRepository->getCurrentLang($this->session);

                $latteParams = [];
                $latteParams["title"] = $this->enumerationRepository->findEnumItemByOrder($lang, $cea->getPlemeno());
                $latteParams["rok"] = date("Y");

                if ($cea->getDatumVytvoreni() != null) {
                    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $cea->getDatumVytvoreni());
                    $latteParams["dnes"] = $dt->format('j.n.Y');
                } else {
                    $latteParams["dnes"] = "";
                }

                $maleOwnersToInput1 = "";
                $pes1 = $this->dogRepository->getDog($cea->getOID1());
                if ($pes1 != null) {
                    $latteParams["pes1CeleJmeno"] = $pes1->getCeleJmeno();
                    $latteParams["pes1Barva"] = (empty($pes1->getBarva()) ? "" : $this->enumerationRepository->findEnumItemByOrder($lang, $pes1->getBarva()));
                    $latteParams["pes1Nar"] = ($pes1->getDatNarozeni() != null ? $pes1->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                    $latteParams["pes1Cz"] = (!empty($pes1->getCisloZapisu()) ? $pes1->getCisloZapisu() : "");
                    
                    $maleOwners = $this->userRepository->findDogOwnersAsUser($cea->getOID1());
                    for($i=0; $i<count($maleOwners); $i++) {
                        $maleOwnersToInput1 .= $maleOwners[$i]->getFullName() . ", " . $maleOwners[$i]->getFullAddress() . (($i+1) != count($maleOwners) ? "; " : "");
                        // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                    }
                } else {
                    $latteParams["pes1CeleJmeno"] = $latteParams["pes1Barva"] = $latteParams["pes1Nar"] = $latteParams["pes1Cz"] = "";
                }            
                $latteParams["pes1Majitele"] = $maleOwnersToInput1;

                $maleOwnersToInput2 = "";
                $pes2 = $this->dogRepository->getDog($cea->getOID2());
                if ($pes2 != null) {
                    $latteParams["pes2CeleJmeno"] = $pes2->getCeleJmeno();
                    $latteParams["pes2Barva"] = (empty($pes2->getBarva()) ? "" : $this->enumerationRepository->findEnumItemByOrder($lang, $pes2->getBarva()));
                    $latteParams["pes2Nar"] = ($pes2->getDatNarozeni() != null ? $pes2->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                    $latteParams["pes2Cz"] = (!empty($pes2->getCisloZapisu()) ? $pes2->getCisloZapisu() : "");
                
                    $maleOwners = $this->userRepository->findDogOwnersAsUser($cea->getOID2());
                    for($i=0; $i<count($maleOwners); $i++) {
                        $maleOwnersToInput2 .= $maleOwners[$i]->getFullName() . ", " . $maleOwners[$i]->getFullAddress() . (($i+1) != count($maleOwners) ? "; " : "");
                        // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                    }
                } else {
                    $latteParams["pes2CeleJmeno"] = $latteParams["pes2Barva"] = $latteParams["pes2Nar"] = $latteParams["pes2Cz"] = "";
                }
                $latteParams["pes2Majitele"] = $maleOwnersToInput2;

                $maleOwnersToInput3 = "";
                $pes3 = $this->dogRepository->getDog($cea->getOID3());
                if (!empty($cea->getOID3())) {
                    $latteParams["pes3CeleJmeno"] = $pes3->getCeleJmeno();
                    $latteParams["pes3Barva"] = (empty($pes3->getBarva()) ? "" : $this->enumerationRepository->findEnumItemByOrder($lang, $pes3->getBarva()));
                    $latteParams["pes3Nar"] = ($pes3->getDatNarozeni() != null ? $pes3->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                    $latteParams["pes3Cz"] = (!empty($pes3->getCisloZapisu()) ? $pes3->getCisloZapisu() : "");

                    // $maleOwnersTelToInput = "";
                    $maleOwners = $this->userRepository->findDogOwnersAsUser($pes3->getID());
                    for($i=0; $i<count($maleOwners); $i++) {
                        $maleOwnersToInput3 .= $maleOwners[$i]->getFullName() . (($i+1) != count($maleOwners) ? ", " : "");
                        // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                    }
                } else {
                    $latteParams["pes3CeleJmeno"] = $latteParams["pes3Barva"] = $latteParams["pes3Nar"] = $latteParams["pes3Cz"] = "";
                }
                $latteParams["pes3Majitele"] = $maleOwnersToInput3;

                $latteParams["fena"] = $fena = $this->dogRepository->getDog($cea->getMID());
                $latteParams["fenaBarva"] = $this->enumerationRepository->findEnumItemByOrder($lang, $fena->getBarva());
                $latteParams["fenaNar"] = ($fena->getDatNarozeni() != null ? $fena->getDatNarozeni()->format(DogEntity::MASKA_DATA_ZOBRAZENI) : "");
                //$latteParams["fenaCz"] = (!empty($fena->getCisloZapisu()) ? $fena->getCisloZapisu() : "");
                        

                $femaleOwnersToInput = "";
                $femaleStation = "";
                // $maleOwnersTelToInput = "";
                $femaleOwners = $this->userRepository->findDogOwnersAsUser($cea->getMID());
                for($i=0; $i<count($femaleOwners); $i++) {
                    $femaleOwnersToInput .= $femaleOwners[$i]->getFullName() . ", " . $femaleOwners[$i]->getFullAddress() . (($i+1) != count($femaleOwners) ? "; " : "");
                    $femaleStation .= $femaleOwners[$i]->getStation() . (($i+1) != count($femaleOwners) ? "; " : "");
                    // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                }
                $latteParams["cisloKrycihoListu"] = $cea->getCisloKL();

                $latteParams["fenaMajitele"] = $femaleOwnersToInput;
                $latteParams["fenaStanice"] = $femaleStation;
                $latteParams["cID"] = $cea->getPlemeno();
                $latteParams['basePath'] = $this->getHttpRequest()->getUrl()->getBaseUrl();

                $latte = new \Latte\Engine();
                $latte->setTempDirectory(__DIR__ . '/../../../temp/cache');
                $template = $latte->renderToString(__DIR__ . '/../templates/FeItem1velord8/matingBtMbtPdf.latte', $latteParams);
                if ($state == "print") {
                    $pdf = new \Joseki\Application\Responses\PdfResponse($template);
                    $pdf->documentTitle = MATING_FORM_CLUB . "_" . date("Y-m-d_His");
                    $this->sendResponse($pdf);
                } else {
                    $pdf = new mPDF();
                    $pdf->ignore_invalid_utf8 = true;
                    $pdf->WriteHTML($template);
        
                    $timestamp = date("Y-m-d_His");
                    $pdfOutput = __DIR__ . "/../../../www/upload/" . MATING_FORM_CLUB . "_" . $timestamp . ".pdf";
                    $pdf->Output($pdfOutput);

                    $emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
                    $ceaUser = $this->userRepository->getUser($cea->getUID());
                    $emailTo = $ceaUser->getEmail();
                    EmailController::SendPlainEmail($emailFrom, $emailTo, COVERAGE_MAIL_SUBJECT_UPDATE, COVERAGE_MAIL_BODY_TO_USER, [$pdfOutput]);

                    $cea->setOdeslano(new DateTime());
                    $this->coverageApplicationRepository->save($cea, []);

                    $this->flashMessage(COVERAGE_MAIL_USER_SUCCESS, "alert-success");
                    $this->redirect(":Admin:Coverage:Default");
                }
            } catch (AbortException $e) {
                throw $e;
            } catch (\Exception $e) {
//                 dump($e); die;
            }
        } else {
            $this->flashMessage(COVERAGE_NOT_EXISTS, "alert-danger");
			$this->redirect("Homepage:Default");
        }
    }
}
