<?php

namespace App\Forms;

use App\Model\DogRepository;
use App\Model\Entity\EnumerationItemEntity;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;
use App\Model\VetRepository;
use Nette\Application\UI\Form;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use App\Controller\FileController;

class DogForm {

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var VetRepository */
	private $vetRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var DogRepository */
	private $dogRepository;

	/**
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 * @param VetRepository $vetRepository
	 * @param UserRepository $userRepository
	 * @param DogRepository
	 */
	public function __construct(
		FormFactory $factory,
		EnumerationRepository $enumerationRepository,
		VetRepository $vetRepository,
		UserRepository $userRepository,
		DogRepository $dogRepository
	) {
		$this->factory = $factory;
		$this->enumerationRepository = $enumerationRepository;
		$this->vetRepository = $vetRepository;
		$this->userRepository = $userRepository;
		$this->dogRepository = $dogRepository;
	}

	/**
	 * @return Form
	 */
	public function create($langCurrent, $linkBack) {
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		/* $form->addText("TitulyPredJmenem", DOG_FORM_NAME_PREFIX)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_NAME_PREFIX); */

		$form->addText("Jmeno", DOG_FORM_NAME)
			->setAttribute("class", "form-control tinym_required_field")
			->setAttribute("validation", USER_EDIT_SURNAME_LABEL_VALIDATION)
			->setAttribute("placeholder", DOG_FORM_NAME);

        $zeme = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::ZEME);
        $form->addSelect("Zeme", Strings::firstUpper(DOG_TABLE_HEADER_LAND), $zeme)
            ->setAttribute("class", "form-control");    
            
		/* $form->addText("TitulyZaJmenem", DOG_FORM_NAME_SUFFIX)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_NAME_SUFFIX); */

		$plemeno = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::PLEMENO);
		$form->addSelect("Plemeno", DOG_FORM_BREED, $plemeno)
			->setAttribute("class", "form-control");

		$srst = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::SRST);
		$form->addSelect("Srst", DOG_FORM_FUR, $srst)
			->setAttribute("class", "form-control");

		$barvy = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::BARVA);
		$form->addSelect("Barva", "", $barvy)
			->setAttribute("class", "form-control");

		/* $form->addText("BarvaKomentar", DOG_FORM_FUR_COM)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_FUR_COM); */

		$pohlavi = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::POHLAVI);
		$form->addSelect("Pohlavi", DOG_FORM_SEX, $pohlavi)
			->setAttribute("class", "form-control");

		$form->addText("DatNarozeni", DOG_FORM_BIRT)
			->setType('date')
			->setAttribute("class", "form-control")
			->setAttribute("id", "DatNarozeni");

		 $form->addText("Vyska", DOG_FORM_HEIGHT)
			->setAttribute("class", "form-control")
			->addRule(Form::PATTERN, DOG_FORM_HEIGHT_NUMBER, '\d*(?:\.\d+)?')
            ->setAttribute("placeholder", DOG_FORM_HEIGHT)
            ->setRequired(FALSE);

		 /* $form->addText("Vaha", DOG_FORM_WEIGHT)
					->setAttribute("class", "form-control")
					->addRule(Form::PATTERN, DOG_FORM_WEIGHT_NUMBER,  '\d*(?:\.\d+)?')
					->setAttribute("placeholder", DOG_FORM_WEIGHT); */

		$form->addText("DatUmrti", DOG_FORM_DEAD)
			->setType('date')
			->setAttribute("class", "form-control")
			->setAttribute("id", "DatUmrti");

		/* $form->addText("UmrtiKomentar", DOG_FORM_DEAD_COM)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_DEAD_COM); */

		$form->addText("CisloZapisu", DOG_FORM_NO_OF_REC)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_NO_OF_REC);

		$form->addText("PCisloZapisu", DOG_FORM_NO_OF_REC2)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_NO_OF_REC2);

		$form->addText("Tetovani", DOG_FORM_NO_OF_TATTOO)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_NO_OF_TATTOO);

		$form->addText("Cip", DOG_FORM_NO_OF_CHIP)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_NO_OF_CHIP);

		$form->addText("Bonitace", DOG_FORM_BON)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_BON);

        $form->addMultiUpload("BonitaceSoubory", DOG_FORM_PIC_UPLOAD_FILE)
			->setAttribute("class", "form-control");

		$chovnost = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::CHOVNOST);
		$form->addSelect("Chovnost", DOG_FORM_BREEDING, $chovnost)
			->setAttribute("class", "form-control");

        // využito jako "CZDR"
		$form->addText("ChovnyKomentar", DOG_FORM_BREEDING_COM)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_BREEDING_COM);

        // využito jako "Poznámka administrátora"
		$form->addTextArea("ZdravotniKomentar", DOG_FORM_HEALTH_COM, null, 7)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_HEALTH_COM);

		/* $varlata = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::VARLATA);
		$form->addSelect("Varlata", DOG_FORM_BOLOCKS, $varlata)
			->setAttribute("class", "form-control hidden");

		$skus = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::SKUS);
		$form->addSelect("Skus", DOG_FORM_CHEW, $skus)
			->setAttribute("class", "form-control"); */

		/* $form->addText("ZubyKomentar", DOG_FORM_TEETH_COM)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", DOG_FORM_TEETH_COM); */

		$vets = $this->vetRepository->findVetsForSelect();
		$zdravi = $this->enumerationRepository->findEnumItems($langCurrent, 14);
		$form->addButton("healthHelper", DOG_FORM_HEALTH)->setAttribute("id","healthHelper")->setAttribute("class", "form-control btn btn-info");
		$dogHealthContainer = $form->addContainer("dogHealth");
		/** @var EnumerationItemEntity $enumEntity */
		foreach ($zdravi as $enumEntity) {
            $container = $dogHealthContainer->addContainer($enumEntity->getOrder());
			$container->addText("caption", null)->setAttribute("class", "form-control")->setAttribute("readonly", "readonly")->setDefaultValue($enumEntity->getItem());
			$container->addText("Vysledek", DOG_FORM_HEALTH_SUMMARY)->setAttribute("class", "form-control")->setAttribute("placeholder", DOG_FORM_HEALTH_SUMMARY);
			$container->addText("Komentar", DOG_FORM_HEALTH_COMMENT)->setAttribute("class", "form-control")->setAttribute("placeholder", DOG_FORM_HEALTH_COMMENT);
			$container->addText("Datum", DOG_FORM_HEALTH_DATE)->setType('date')->setAttribute("class", "healthDatePicker form-control");
			$container->addSelect("Veterinar", DOG_FORM_HEALTH_VET, $vets)->setAttribute("class", "form-control");
		}

		$chovatele = $this->userRepository->findBreedersForSelect();
		$breederContainer = $form->addContainer("breeder");
		$breederContainer->addSelect("uID", DOG_FORM_BREEDER, $chovatele)->setAttribute("class", "form-control");

		$vlastnici = $this->userRepository->findOwnersForSelect();
		$ownerContainer = $form->addContainer("owners");
		$ownerContainer->addMultiSelect("uID", DOG_FORM_OWNERS, $vlastnici)
			->setAttribute("class", "form-control chosen-select");

		$males = $this->dogRepository->findMaleDogsForSelect();
		$form->addSelect("oID", DOG_FORM_MALE, $males)
			->setAttribute("class", "form-control");

		$females = $this->dogRepository->findFemaleDogsForSelect();
		$form->addSelect("mID", DOG_FORM_FEMALE, $females)
			->setAttribute("class", "form-control");

		/* $form->addTextArea("KontrolaVrhu", DOG_FORM_LITTER_CHECK)
			->setAttribute("class", "form-control"); */

		$form->addTextArea("TitulyKomentar", DOG_FORM_TITLES, null, 7)
			->setAttribute("class", "form-control");

		$form->addTextArea("Posudek", DOG_FORM_BON_TEXT, null, 7)
			->setAttribute("class", "form-control");

		$form->addTextArea("Oceneni", DOG_FORM_SHOWS_NEXT_TEXT, null, 7)
			->setAttribute("class", "form-control");

        /* původní pole zkoušky přímo z tabulky appdata_pes
		$form->addTextArea("Zkousky", DOG_FORM_SHOWS_EXAMS, null, 7)
            ->setAttribute("class", "form-control"); 
        */
        
        $exams = $this->enumerationRepository->findEnumItemsForSelectWithEmpty($langCurrent, EnumerationRepository::ZKOUSKY);
        $form->addMultiSelect("Zkousky", DOG_FORM_SHOWS_EXAMS, $exams)
            ->setAttribute("class", "form-control chosen-select");

        /* $form->addTextArea("ZkouskySlozene", DOG_FORM_SHOWS_EXAMS_NEXT, null, 7)
            ->setAttribute("class", "form-control");

		$form->addTextArea("Zavody", DOG_FORM_SHOWS_RACES, null, 7)
			->setAttribute("class", "form-control"); */

		$form->addTextArea("Komentar",DOG_FORM_SHOWS_NOTE, null, 7)
            ->setAttribute("class", "form-control");
            
        /* $form->addCheckbox("SkrytPotomky", " " . DOG_FORM_HIDE_DESCENDANTS);

        $form->addCheckbox("SkrytSourozence", " " . DOG_FORM_HIDE_SIBLINGS); */

        $form->addCheckbox("SkrytCelouKartu", " " . DOG_FORM_HIDE_DOG_CARD);

        $form->addCheckbox("SkrytSvod", " " . DOG_FORM_HIDE_DOG_LEAD);

		$form->addMultiUpload("pics", DOG_FORM_PIC_UPLOAD)
			->setAttribute("class", "form-control");

		$form->addButton("back", VET_EDIT_BACK)
			->setAttribute("class", "btn margin10")
			->setAttribute("onclick", "location.assign('" . $linkBack . "')");

		$form->addSubmit("saveDog", VET_EDIT_SAVE)
			->setAttribute("class", "btn btn-primary margin10");

		return $form;
	}
}