<?php

namespace App\Forms;

use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use Nette;
use Nette\Application\UI\Form;

class PuppyForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var DogRepository */
	private $dogRepository;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/**
	 * @param FormFactory $factory
	 * @param DogRepository $dogRepository
	 */
	public function __construct(FormFactory $factory, DogRepository $dogRepository, EnumerationRepository $enumerationRepository) {
		$this->factory = $factory;
		$this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
	}

	/**
	 * @param array $languages
	 * @param int $level
	 * @return Form
	 */
	public function create($currentLang) {
		$counter = 1;
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$form->addHidden("ID");
		$form->addHidden("uID");
		$form->addHidden("Plemeno");

		$males = $this->dogRepository->findMaleDogsForSelect(false);

		$form->addSelect("oID", MATING_FORM_FID, $males)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 1);

		$females = $this->dogRepository->findFemaleDogsForSelect(false);
		$form->addSelect("mID", MATING_FORM_MID, $females)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 2);

		$form->addText("Termin", PUPPY_TABLE_TERM)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 3);

		$form->addTextArea("Podrobnosti", PUPPY_TABLE_DETAILS, 20, 10)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 4);

		$form->addSubmit("save", USER_EDIT_SAVE_BTN_LABEL)
			->setAttribute("class", "btn btn-primary margin10")
			->setAttribute("tabindex", $counter + 6);

		return $form;
	}
}