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
use Dibi\Exception;
use Nette\Application\AbortException;
use Nette\Forms\Form;
use Nette\Utils\ArrayHash;
use Mpdf\Mpdf as mPDF;
use Dibi\DateTime;
use App\Model\Entity\CoverageApplicationAttachementEntity;
use App\Controller\FileController;

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

	public function actionDefault() {
		if ($this->getUser()->isLoggedIn() == false) { // pokud nejsen přihlášen nemám tady co dělat
			$this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
			$this->redirect("Homepage:Default");
		}
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
                        $maleOwnersToInput1 .= $maleOwners[$i]->getFullName() . (($i+1) != count($maleOwners) ? ", " : "");
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
                        $maleOwnersToInput2 .= $maleOwners[$i]->getFullName() . (($i+1) != count($maleOwners) ? ", " : "");
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
                        $maleOwnersToInput3 .= $maleOwners[$i]->getFullName() . (($i+1) != count($maleOwners) ? ", " : "");
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
                    $femaleOwnersToInput .= $femaleOwners[$i]->getFullName() . (($i+1) != count($femaleOwners) ? ", " : "");
                    $femaleStation = $femaleOwners[$i]->getStation();
                    // $maleOwnersTelToInput .= $maleOwners[$i]->getPhone() . (($i+1) != count($maleOwners) ? ", " : "");
                }
    
                $latteParams["cisloKrycihoListu"] = ""; // TODO
    
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
                $pdf->Output(__DIR__ . "/../../../www/upload/" . MATING_FORM_CLUB . "_" . $timestamp . ".pdf");
    
                $attachs = [];
                foreach($values["attachemets"] as $file) {
                    if ($file->name != "") {
                        $fileController = new FileController();
                        if ($fileController->upload($file, $supportedFileFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
                            $error = true;
                            break;
                        }
                        $cea = new CoverageApplicationAttachementEntity();
                        $cea->setCesta($fileController->getPathDb());
                        $attachs[] = $cea; 
                    }
                }
                $ce = new CoverageApplicationEntity();
                $ce->setMID($fID);
                $ce->setOID1(empty($pID1) ? null : $pID1);
                $ce->setOID2(empty($pID2) ? null : $pID2);
                $ce->setOID3(empty($pID3) ? null : $pID3);
                $ce->setDatumVytvoreni(new Datetime());
                $ce->setPlemeno($cID);  // tohle je ve skutečnosti název klubu (číselník 18)
                $ce->setUID($this->getUser()->getId());
                $ce->setExpresni(isset($values["express"]) ? 1 : 0);
                $this->coverageApplicationRepository->save($ce, $attachs);
                    

                // TODO - poslat emailem
                
                $this->flashMessage(COVERAGE_SAVED_OK, "alert-success");
                $this->redirect("Default");
            } catch (AbortException $e) {
                throw $e;
            } catch (\Exception $e) {
                dump($e); die;
            }
        }
	}
}