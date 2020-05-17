<?php

namespace App\Forms;

use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use Nette;
use Nette\Application\UI\Form;

class LitterApplicationForm {

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

        $clubs = $this->enumerationRepository->findEnumItemsForSelect($currentLang, 24);
		$form->addSelect("cID", LITTER_APPLICATION, $clubs)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter++);

        $females = $this->dogRepository->findFemaleDogsForSelect(true);
		$form->addSelect("fID", MATING_FORM_MID, $females)
			->setAttribute("class", "form-control")
            ->setAttribute("tabindex", $counter++);
            
		$males = $this->dogRepository->findMaleDogsForSelect(true);
		$form->addSelect("pID", MATING_FORM_FID, $males)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $counter++);

		$form->addSubmit("save", MATING_FORM_SAVE)
			->setAttribute("class", "btn btn-primary margin10")
            ->setAttribute("tabindex", $counter++);

                        
        $form->addSubmit("save2", MATING_FORM_SAVE2)
            ->setAttribute("class", "btn btn-primary margin10")
            ->setAttribute("tabindex", $counter++);
           
		return $form;
	}
}