<?php

namespace App\Forms;

use App\Model\EnumerationRepository;
use Nette;
use Nette\Application\UI\Form;

class MatingListDetailForm {

    use Nette\SmartObject;

    /** @const pocet radek formulare o štěňatech */
	const NUMBER_OF_LINES = 10;

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/**
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 */
	public function __construct(FormFactory $factory, EnumerationRepository $enumerationRepository) {
		$this->factory = $factory;
		$this->enumerationRepository = $enumerationRepository;
	}

	/**
	 * @param array $languages
	 * @param int $level
	 * @return Form
	 */
	public function create($currentLang, $linkBack) {
		$counter = 1;
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

        $plemeno = $this->enumerationRepository->findEnumItemsForSelect($currentLang, 7);
        $pohlavi = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::POHLAVI);
        $barvyBezPrazdne = $this->enumerationRepository->findEnumItemsForSelectIgnoreEmpty($currentLang, EnumerationRepository::BARVA);

		$form->addHidden('cID');

		$form->addSelect("Plemeno", DOG_FORM_BREED, $plemeno)
			->setAttribute("tabindex", $counter);

		$form->addText("ChovStaniceVrh", LITTER_APPLICATION_BREEDING)
			->setAttribute("placeholder", LITTER_APPLICATION_BREEDING)
			->setAttribute("tabindex", $counter++);

		$femaleContainer = $form->addContainer('fID');
		$femaleContainer->addText("Jmeno", DOG_FORM_NAME_FEMALE)
			->setAttribute("placeholder", DOG_FORM_NAME)
			->setAttribute("tabindex", $counter++);

		$femaleContainer->addText("CisloZapisu", LITTER_APPLICATION_RECORD_NUM_FORM)
			->setAttribute("placeholder", LITTER_APPLICATION_RECORD_NUM)
			->setAttribute("tabindex", $counter++);

		$femaleContainer->addText("DatNarozeni", MATING_LITTER_DOG_DATE)
			->setAttribute("placeholder", DOG_FORM_BIRT)
			->setAttribute("tabindex", $counter++);

		/* $femaleContainer->addText("Bonitace", MATING_FORM_BON_CODE)
			->setAttribute("placeholder", MATING_FORM_BON_CODE)
			->setAttribute("tabindex", $counter++); */

		// --------------------------------------------------------

		$maleContainer = $form->addContainer('pID');
		$maleContainer->addText("Jmeno", LITTER_APPLICATION_MALE_NAME)
			->setAttribute("placeholder", LITTER_APPLICATION_MALE_NAME)
			->setAttribute("tabindex", $counter++);

		$maleContainer->addText("CisloZapisu", DOG_FORM_NO_OF_REC)
			->setAttribute("placeholder", DOG_FORM_NO_OF_REC)
			->setAttribute("tabindex", $counter++);

		$maleContainer->addText("DatNarozeni", MATING_LITTER_DOG_DATE)
			->setAttribute("placeholder", DOG_FORM_BIRT)
			->setAttribute("tabindex", $counter++);

		/* $maleContainer->addText("Bonitace", MATING_FORM_BON_CODE)
			->setAttribute("placeholder", MATING_FORM_BON_CODE)
			->setAttribute("tabindex", $counter++); */

		// ------------------------------------------------------------

		$form->addText("MistoKryti", LITTER_APPLICATION_PLACE)
			->setAttribute("placeholder", LITTER_APPLICATION_PLACE)
			->setAttribute("tabindex", $counter++);

		$form->addText("DatumKryti", MATING_FORM_PLACE_DETAIL_DAY)
			->setAttribute("placeholder", MATING_FORM_PLACE_DETAIL_DAY)
			->setAttribute("tabindex", $counter++);

		$form->addText("DatumKrytiOpakovane", MATING_FORM_PLACE_DETAIL_DAY_REPEAT)
			->setAttribute("placeholder", MATING_FORM_PLACE_DETAIL_DAY_REPEAT)
			->setAttribute("tabindex", $counter++);

		$form->addText("PredpokladDatum", MATING_FORM_ESTIMATE_DATE)
			->setAttribute("placeholder", MATING_FORM_ESTIMATE_DATE)
			->setAttribute("tabindex", $counter++);

		/* $form->addText("DatumPorodu", LITTER_APPLICATION_BIRTH)
			->setAttribute("placeholder", LITTER_APPLICATION_BIRTH)
			->setAttribute("tabindex", $counter++); */

		$form->addText("PocetStenat", LITTER_APPLICATION_DOG_LIVE)
			->setAttribute("placeholder", LITTER_APPLICATION_DOG_LIVE)
			->setAttribute("tabindex", $counter++);

		$form->addText("PocetStenatZTohoPsi", LITTER_APPLICATION_DOG_LIVE_MALE)
			->setAttribute("placeholder", LITTER_APPLICATION_DOG_LIVE_MALE)
			->setAttribute("tabindex", $counter++);

		$form->addText("PocetStenatZTohoFeny", LITTER_APPLICATION_DOG_LIVE_FEMALE)
			->setAttribute("placeholder", LITTER_APPLICATION_DOG_LIVE_FEMALE)
			->setAttribute("tabindex", $counter++);

		$form->addText("PocetStenatMrtvych", LITTER_APPLICATION_DOG_DEATH)
			->setAttribute("placeholder", LITTER_APPLICATION_DOG_DEATH)
			->setAttribute("tabindex", $counter++);

		$form->addText("PocetStenatPozn", LITTER_APPLICATION_PUPPIES_DETAILS, 80)
			->setAttribute("placeholder", LITTER_APPLICATION_PUPPIES_DETAILS)
			->setAttribute("tabindex", $counter++);

		$form->addCheckbox("UvedeniNaWebu")
			->setAttribute("placeholder", MATING_FORM_RULES)
            ->setAttribute("tabindex", $counter++);
            
        for ($i=1; $i <= self::NUMBER_OF_LINES; $i++) {
            $form->addSelect("pohlavi" . $i, "", $pohlavi);
            $form->addText("jmeno" . $i, "", 20);
            $form->addSelect("barva" . $i, "", $barvyBezPrazdne);
        }

		// ------------------------------------------------------------

		$form->addTextArea("MajitelFeny", MATING_FORM_FEMALE_OWNER, 70, 10)
			->setAttribute("placeholder", MATING_FORM_FEMALE_OWNER)
			->setAttribute("tabindex", $counter++);

		$form->addText("MajitelFenyTel", USER_EDIT_PHONE_LABEL)
			->setAttribute("placeholder", USER_EDIT_PHONE_LABEL)
			->setAttribute("tabindex", $counter++);

		/* $form->addTextArea("MajitelPsa", LITTER_APPLICATION_OWNWER_MALE, 70, 10)
			->setAttribute("placeholder", LITTER_APPLICATION_OWNWER_MALE)
			->setAttribute("tabindex", $counter++); */

		$form->addText("Datum", MATING_FORM_DATE_SHORT)
			->setAttribute("placeholder", MATING_FORM_DATE_SHORT)
			->setAttribute("tabindex", $counter++);

		$form->addButton("back", MATING_FORM_OVERAGAIN)
			->setAttribute("class", "btn btn-default margin10")
			->setAttribute("onclick", "location.assign('" . $linkBack . "')")
			->setAttribute("tabindex", $counter++);

		$form->addSubmit("generate", MATING_FORM_GENERATE)
			->setAttribute("class", "btn btn-primary margin10")
			->setAttribute("tabindex", $counter++);

		return $form;
	}
}