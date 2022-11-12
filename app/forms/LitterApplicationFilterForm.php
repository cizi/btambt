<?php

namespace App\Forms;

use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\LitterApplicationRepository;
use Nette;
use Nette\Application\UI\Form;

class LitterApplicationFilterForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var  LitterApplicationRepository */
	private $litterApplicationRepository;

	/**
	 * LitterApplicationFilterForm constructor.
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 * @param LitterApplicationRepository $litterApplicationRepository
	 */
	public function __construct(FormFactory $factory, EnumerationRepository $enumerationRepository, LitterApplicationRepository $litterApplicationRepository) {
		$this->factory = $factory;
		$this->enumerationRepository = $enumerationRepository;
		$this->litterApplicationRepository = $litterApplicationRepository;
	}

	/**
	 * @param string $currentLang
	 * @return Form
	 */
	public function create($currentLang, $isAdmin = false) {
		$counter = 1;
		$form = $this->factory->create();

		$breeds = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::PLEMENO);
		$form->addSelect("Plemeno", DOG_FORM_BREED, $breeds)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 1);

		$form->addText("DatumNarozeni", DOG_FORM_BIRT)
			->setType('date')
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 2);

		$chss = $this->litterApplicationRepository->findChsInApplications();
		$form->addSelect("chs", USER_EDIT_STATION_LABEL, $chss)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter + 3);

		if ($isAdmin) {
			$rewritten = [1 => LITTER_APPLICATION_SAVE_UNREWRITTEN, 2 => LITTER_APPLICATION_SAVE_REWRITTEN, 3 =>LITTER_APPLICATION_SAVE_ALLREWRITTEN];
			$form->addSelect("Zavedeno", LITTER_APPLICATION_SAVE_REWRITTEN, $rewritten)
				->setAttribute("class", "form-control")
				->setDefaultValue(3)
				->setAttribute("tabindex", $counter + 4);
		}

		$form->addSubmit("filter", DOG_TABLE_BTN_FILTER)
			->setAttribute("class", "btn btn-primary margin5")
			->setAttribute("tabindex", $counter + 5);

		return $form;
	}
}