<?php

namespace App\Forms;

use App\Model\EnumerationRepository;
use Nette;
use Nette\Application\UI\Form;

class LitterApplicationDetailForm {

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
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$form->addHidden('oID');	// DB
		$form->addHidden('mID');	// DB
		$form->addHidden('Klub');	// DB
		$form->addHidden('MajitelFeny');	// DB
		$form->addHidden('cID');
        $form->addHidden('title');
        $form->addHidden('basePath');
        $form->addHidden('number');

		$barvy = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::BARVA);
		$barvyBezPrazdne = $this->enumerationRepository->findEnumItemsForSelectIgnoreEmpty($currentLang, EnumerationRepository::BARVA);
		$srst = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::SRST);
		$srstBezPrazdne = $this->enumerationRepository->findEnumItemsForSelectIgnoreEmpty($currentLang, EnumerationRepository::SRST);
		$plemeno = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::PLEMENO);
		$pohlavi = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::POHLAVI);

        $form->addText("No", COUNTER_LITTER_NO_FROM_COVERAGE)
            ->setAttribute("class", "tinym_required_field")
            ->setAttribute("validation", COUNTER_LITTER_NO_FROM_COVERAGE_REQ)
            ->setAttribute("placeholder", COUNTER_LITTER_NO_FROM_COVERAGE);

		$form->addSelect("Plemeno", DOG_FORM_BREED, $plemeno);
		$form->addText("chs", LITTER_APPLICATION_DETAIL_STATION_TITLE, 80);

		// OTEC
		$form->addText("otec", DOG_TABLE_HEADER_FATHER, 40);
		$form->addText("otecDN", DOG_TABLE_HEADER_BIRT, 40);
		$form->addTextArea("otecV", LITTER_APPLICATION_DETAIL_DOG_TITLES, 60, 3);

		$form->addText("otecPP", LITTER_APPLICATION_DETAIL_CARD_NO, 20);
		$form->addSelect("otecBarva", DOG_TABLE_HEADER_COLOR, $barvy);
		$form->addSelect("otecSrst", LITTER_APPLICATION_DETAIL_FUR_TYPE, $srst);
		$form->addText("otecBon", LITTER_APPLICATION_DETAIL_BONITATION, 20);
		$form->addText("otecCisZap", LITTER_APPLICATION_DETAIL_BONITATION, 20);
		// $form->addText("otecHeight", DOG_TABLE_HEADER_HEIGHT ,4);

		// matka
		$form->addText("matka", DOG_TABLE_HEADER_MOTHER, 40);
		$form->addText("matkaDN", DOG_TABLE_HEADER_BIRT, 40);
		$form->addTextArea("matkaV", LITTER_APPLICATION_DETAIL_DOG_TITLES, 60, 3);

		$form->addText("matkaPP", LITTER_APPLICATION_DETAIL_CARD_NO, 20);
		$form->addSelect("matkaBarva", DOG_TABLE_HEADER_COLOR, $barvy);
		$form->addSelect("matkaSrst", LITTER_APPLICATION_DETAIL_FUR_TYPE, $srst);
        $form->addText("matkaBon", LITTER_APPLICATION_DETAIL_BONITATION, 20);
        $form->addText("matkaCisZap", LITTER_APPLICATION_DETAIL_BONITATION, 20);
		// $form->addText("matkaHeight", DOG_TABLE_HEADER_HEIGHT ,4);

		$form->addText("chovatel", LITTER_APPLICATION_DETAIL_BREEDER_ADDRESS, 80);
		$form->addText("datumkryti", MATING_FORM_DATE, 15)
			->setAttribute("class", "tinym_required_field")
			->setAttribute("validation", LITTER_APPLICATION_DETAIL_LITTER_DATE_REQ);
		$form->addText("datumnarozeni", LITTER_APPLICATION_DETAIL_PUPPIES_BIRTHDAY, 15);

		$form->addText("porozenoPsu", LITTER_APPLICATION_DETAIL_PUPPIES_MALES, 2);
		$form->addText("porozenoFen", LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES, 2);
		$form->addText("porozenoNez", LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW, 2)->setAttribute("title", LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW);
		$form->addText("mrtvychPsu", LITTER_APPLICATION_DETAIL_PUPPIES_MALES, 2);
		$form->addText("mrtvychFen", LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES, 2);
		$form->addText("mrtvychNez", LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW, 2)->setAttribute("title", LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW);
		$form->addText("usmrcenoPsu", LITTER_APPLICATION_DETAIL_PUPPIES_MALES, 2);
		$form->addText("usmrcenoFen", LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES, 2);
		$form->addText("usmrcenoNez", LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW, 2)->setAttribute("title", LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW);
		$form->addText("kzapisuPsu", LITTER_APPLICATION_DETAIL_PUPPIES_MALES, 2);
		$form->addText("kzapisuFen", LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES, 2);
		$form->addText("kojnePsu", LITTER_APPLICATION_DETAIL_PUPPIES_MALES, 2);
		$form->addText("kojneFen", LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES, 2);
		$form->addText("zahynuloPsu", LITTER_APPLICATION_DETAIL_PUPPIES_MALES, 2);
		$form->addText("zahynuloFen", LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES, 2);

		for ($i=1; $i <= self::NUMBER_OF_LINES; $i++) {
			$form->addText("mikrocip" . $i, "", 10);
			$form->addText("jmeno" . $i, "", 20);
			$form->addSelect("pohlavi" . $i, "", $pohlavi);
			$form->addSelect("srst" . $i, "", $srstBezPrazdne);
			$form->addSelect("barva" . $i, "", $barvyBezPrazdne);
		}

		$form->addCheckbox("kryci_list", LITTER_APPLICATION_DETAIL_INC_MATING_LIST);
		$form->addCheckbox("pp_psa", LITTER_APPLICATION_DETAIL_INC_MATING_MALE_PREREG);
		$form->addCheckbox("pp_feny", LITTER_APPLICATION_DETAIL_INC_MATING_FEMALE_PREREG);
		$form->addCheckbox("poplatky", LITTER_APPLICATION_DETAIL_INC_MATING_FEES);
		$form->addCheckbox("fotokopiechs", LITTER_APPLICATION_DETAIL_INC_MATING_PHOTO);
		$form->addCheckbox("fotokopietitulu", LITTER_APPLICATION_DETAIL_INC_MATING_TITLES);

		$form->addText("misto", LITTER_APPLICATION_DETAIL_IN, 50);
		$form->addTextArea("kontrolaVrhu", LITTER_APPLICATION_DETAIL_CONTROL)->setAttribute("class", "prihlaska_full");

		$form->addButton("back", MATING_FORM_OVERAGAIN)
			->setAttribute("class", "btn margin10")
			->setAttribute("onclick", "location.assign('" . $linkBack . "')");

		$form->addSubmit("generate", MATING_FORM_SAVE2)
			->setAttribute("class", "btn btn-primary margin10");

		return $form;
	}
}