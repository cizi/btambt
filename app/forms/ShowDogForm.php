<?php

namespace App\Forms;


use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\RefereeRepository;
use Nette\Forms\Form;

class ShowDogForm {

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository  */
	private $enumRepository;

	/** @var DogRepository  */
	private	$dogRepository;

	/**
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 * @param RefereeRepository $refereeRepository
	 */
	public function __construct(FormFactory $factory, EnumerationRepository $enumerationRepository, DogRepository $dogRepository) {
		$this->factory = $factory;
		$this->enumRepository = $enumerationRepository;
		$this->dogRepository = $dogRepository;
	}

	/**
	 * @return Form
	 */
	public function create($linkBack, $lang) {
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$index = 0;
		$form->addHidden("vID");

		$form->addGroup(SHOW_DOG_FORM_DOG);
		$dogs = $this->dogRepository->findDogsForSelect();
		$form->addSelect("pID", SHOW_DOG_FORM_DOG, $dogs)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", SHOW_TABLE_REFEREE)
			->setAttribute("tabindex", $index + 1);

		$form->addGroup(SHOW_DOG_FORM_CLASS);
		$classes = $this->enumRepository->findEnumItemsForSelect($lang, 20);
		$form->addSelect("Trida", SHOW_DOG_FORM_CLASS, $classes)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", SHOW_TABLE_DATE)
			->setAttribute("tabindex", $index + 2);

		$form->addGroup(SHOW_DOG_FORM_REPUTATION);
		$oceneni = $this->enumRepository->findEnumItemsForSelect($lang, 21);
		$form->addSelect("Oceneni", SHOW_DOG_FORM_REPUTATION, $oceneni)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $index + 3);

		$form->addGroup(SHOW_DOG_FORM_DOG_ORDER);
		$poradi = $this->enumRepository->findEnumItemsForSelectWithoutEmpty($lang, 22);
		$form->addSelect("Poradi", SHOW_DOG_FORM_DOG_ORDER, $poradi)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $index + 4);

		$form->addGroup(SHOW_DOG_FORM_DOG_TITLES);
		$tituly = $this->enumRepository->findEnumItemsForSelect($lang, 23);
		$titlesContainer = $form->addContainer("Titul");
		foreach ($tituly as $key => $value) {
			$titlesContainer->addCheckbox($key, $value)
				->setAttribute("tabindex", $index++);
		}

		$form->addText("TitulyDodatky", SHOW_DOG_FORM_DOG_TITLES_ADDON)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $index++);

		$form->addButton("back", VET_EDIT_BACK)
			->setAttribute("class","btn margin10")
			->setAttribute("onclick", "location.assign('". $linkBack ."')")
			->setAttribute("tabindex", $index++);

		$form->addSubmit("confirm", VET_EDIT_SAVE)
			->setAttribute("class","btn btn-primary margin10")
			->setAttribute("tabindex", $index++);

		return $form;
	}

}