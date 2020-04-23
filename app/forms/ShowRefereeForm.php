<?php

namespace App\Forms;


use App\Model\Entity\ShowRefereeEntity;
use App\Model\EnumerationRepository;
use App\Model\RefereeRepository;
use Nette\Forms\Form;

class ShowRefereeForm {

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository */
	private $enumRepository;

	/** @var  RefereeRepository */
	private $refereeRepository;

	/**
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 * @param RefereeRepository $refereeRepository
	 */
	public function __construct(
		FormFactory $factory,
		EnumerationRepository $enumerationRepository,
		RefereeRepository $refereeRepository
	) {
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
		$form->addHidden("vID");

		$referees = $this->refereeRepository->findRefereesForSelect();
		$form->addGroup(SHOW_REFEREE_FORM_REFEREE);
		$form->addSelect("rID", SHOW_REFEREE_FORM_REFEREE, $referees)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", SHOW_TABLE_REFEREE)
			->setAttribute("tabindex", $index++);

		$classes = $this->enumRepository->findEnumItemsForSelect($lang, 20);
		$form->addGroup(SHOW_REFEREE_FORM_CLASS);
		$classContainer = $form->addContainer("Trida");
		foreach ($classes as $key => $value) {
			$classContainer->addCheckbox($key, $value)
				->setAttribute("tabindex", $index++);
		}

		$plemeno = $this->enumRepository->findEnumItemsForSelect($lang, 7);
		$form->addGroup(SHOW_REFEREE_FORM_BREED);
		$breedContainer = $form->addContainer("Plemeno");
		foreach ($plemeno as $key => $value) {
			if ($value != ShowRefereeEntity::NOT_SELECTED) {
				$breedContainer->addCheckbox($key, $value)
					->setAttribute("tabindex", $index++)
					->setAttribute("class", "showRefereeBreed");
			}
		}

		$form->addButton("back", VET_EDIT_BACK)
			->setAttribute("class", "btn margin10")
			->setAttribute("onclick", "location.assign('" . $linkBack . "')")
			->setAttribute("tabindex", $index++);

		$form->addSubmit("confirm", VET_EDIT_SAVE)
			->setAttribute("class", "btn btn-primary margin10")
			->setAttribute("tabindex", $index++);

		return $form;
	}

}