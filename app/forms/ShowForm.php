<?php

namespace App\Forms;

use App\Model\EnumerationRepository;
use App\Model\RefereeRepository;
use Nette\Forms\Form;

class ShowForm {

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository  */
	private $enumRepository;

	/** @var  RefereeRepository */
	private $refereeRepository;

	/**
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 * @param RefereeRepository $refereeRepository
	 */
	public function __construct(FormFactory $factory, EnumerationRepository $enumerationRepository, RefereeRepository $refereeRepository) {
		$this->factory = $factory;
		$this->enumRepository = $enumerationRepository;
		$this->refereeRepository = $refereeRepository;
	}

	/**
	 * @return Form
	 */
	public function create($linkBack, $lang) {
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$index = 0;
		$showTypes = $this->enumRepository->findEnumItemsForSelect($lang, 19);
		$form->addSelect("Typ", SHOW_TABLE_TYPE, $showTypes)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", SHOW_TABLE_TYPE)
			->setAttribute("tabindex", $index + 1);

		$form->addText("Datum", SHOW_TABLE_DATE)
			->setAttribute("class", "form-control tinym_required_field")
			->setAttribute("validation", SHOW_TABLE_DATE_VALIDATION)
			->setAttribute("placeholder", SHOW_TABLE_DATE)
			->setAttribute("tabindex", $index + 2);

		$form->addText("Nazev", SHOW_TABLE_NAME)
			->setAttribute("class", "form-control tinym_required_field")
			->setAttribute("validation", SHOW_TABLE_NAME_VALIDATION)
			->setAttribute("placeholder", SHOW_TABLE_NAME)
			->setAttribute("tabindex", $index + 3);

		$form->addText("Misto", SHOW_TABLE_PLACE)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", SHOW_TABLE_PLACE)
			->setAttribute("tabindex", $index + 4);

		$form->addCheckbox("Hotovo")
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setAttribute("tabindex", $index + 5);

		$referees = $this->refereeRepository->findRefereesForSelect();
		$form->addMultiSelect("Rozhodci", SHOW_TABLE_REFEREE, $referees)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", SHOW_TABLE_REFEREE)
			->setAttribute("tabindex", $index + 6);

		$form->addButton("back", VET_EDIT_BACK)
			->setAttribute("class","btn margin10")
			->setAttribute("onclick", "location.assign('". $linkBack ."')")
			->setAttribute("tabindex", $index + 7);

		$form->addSubmit("confirm", VET_EDIT_SAVE)
			->setAttribute("class","btn btn-primary margin10")
			->setAttribute("tabindex", $index + 8);

		return $form;
	}
}