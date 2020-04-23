<?php

namespace App\Forms;

use App\Model\DogRepository;
use Nette;
use Nette\Application\UI\Form;

class KinshipVerificationForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var DogRepository */
	private $dogRepository;

	/**
	 * @param FormFactory $factory
	 * @param DogRepository $dogRepository
	 */
	public function __construct(FormFactory $factory, DogRepository $dogRepository) {
		$this->factory = $factory;
		$this->dogRepository = $dogRepository;
	}

	/**
	 * @param array $languages
	 * @param int $level
	 * @return Form
	 */
	public function create() {
		$counter = 1;
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$males = $this->dogRepository->findMaleDogsForSelect(true);
		$form->addSelect("pID", MATING_FORM_FID, $males)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 2);

		$females = $this->dogRepository->findFemaleDogsForSelect(true);
		$form->addSelect("fID", MATING_FORM_MID, $females)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 3);

		$form->addSubmit("save", MATING_FORM_SAVE)
			->setAttribute("class", "btn btn-primary margin10")
			->setAttribute("tabindex", $counter + 4);

		return $form;
	}
}