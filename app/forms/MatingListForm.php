<?php

namespace App\Forms;

use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\LangRepository;
use Nette;
use Nette\Application\UI\Form;
use App\Controller\FileController;

class MatingListForm {

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
	 * @param EnumerationRepository $enumerationRepository
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

		$clubs = $this->enumerationRepository->findEnumItemsForSelect($currentLang, 18);
		$form->addSelect("cID", MATING_FORM_CLUB, $clubs)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter++);

        $females = $this->dogRepository->findFemaleDogsForSelectHtml(true, true, true);
        $form->addSelect("fID", MATING_FORM_MID, $females)
            ->setAttribute("class", "form-control")
            ->setAttribute("tabindex", $counter++);

		$males = $this->dogRepository->findMaleDogsForSelectHtml(true);
		$form->addSelect("pID1", MATING_FORM_FID, $males)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter++);

        $form->addSelect("pID2", MATING_FORM_FID, $males)
			->setAttribute("class", "form-control")
            ->setAttribute("tabindex", $counter++);
            
        $form->addSelect("pID3", MATING_FORM_FID, $males)
			->setAttribute("class", "form-control")
            ->setAttribute("tabindex", $counter++);

        $form->addText("Poznamka", MATING_FORM_NOTE_ADMIN)
            ->setAttribute("class", "form-control")
            ->setAttribute("tabindex", $counter++);

        $form->addMultiUpload("attachemets", CONTACT_FORM_ATTACHMENT)
            ->setAttribute("class", "form-control")
            ->setAttribute("tabindex", $counter++);

        $form->addCheckbox("express", " " . COVERAGE_EXPRESS)
            ->setAttribute("tabindex", $counter++);
            
		$form->addSubmit("save", MATING_FORM_SAVE1)
			->setAttribute("class", "btn btn-primary margin10")
            ->setAttribute("tabindex", $counter++);

		return $form;
	}
}