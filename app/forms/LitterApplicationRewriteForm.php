<?php

namespace App\Forms;

use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;
use Nette;
use Nette\Application\UI\Form;

class LitterApplicationRewriteForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var DogRepository */
	private $dogRepository;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var UserRepository */
	private $userRepository;

	/**
	 * LitterApplicationRewriteForm constructor.
	 * @param FormFactory $factory
	 * @param DogRepository $dogRepository
	 * @param EnumerationRepository $enumerationRepository
	 * @param UserRepository $userRepository
	 */
	public function __construct(FormFactory $factory, DogRepository $dogRepository, EnumerationRepository $enumerationRepository, UserRepository $userRepository) {
		$this->factory = $factory;
		$this->dogRepository = $dogRepository;
		$this->enumerationRepository = $enumerationRepository;
		$this->userRepository = $userRepository;
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

		$barvy = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::BARVA);
		$srst = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::SRST);
		$pohlavi = $this->enumerationRepository->findEnumItemsForSelect($currentLang, EnumerationRepository::POHLAVI);

		$form->addHidden("Plemeno");
		$form->addHidden("mID");
		$form->addHidden("oID");
		$form->addHidden("ID");
		$form->addHidden("DatNarozeni");

		for ($i=1; $i <= LitterApplicationDetailForm::NUMBER_OF_LINES; $i++) {
			$container = $form->addContainer($i);

			$container->addText("CisloZapisu", DOG_FORM_NO_OF_REC)
				->setAttribute("class", "form-control");	// nemám z přihlášky

			$container->addText("Tetovani", DOG_FORM_NO_OF_TATTOO)
				->setAttribute("class", "form-control");	// nemám z přihlášky

			$container->addText("Cip", DOG_FORM_NO_OF_CHIP)
				->setAttribute("class", "form-control");

			$container->addText("Jmeno", DOG_FORM_NAME)
				->setAttribute("class", "form-control");

			/* $container->addTextArea("KontrolaVrhu", DOG_FORM_LITTER_CHECK)
				->setAttribute("class", "form-control");  */

			$container->addSelect("PohlaviSel", DOG_FORM_SEX, $pohlavi)
				->setAttribute("class", "form-control")
				->setDisabled();
			$container->addHidden("Pohlavi");

			/* $container->addSelect("SrstSel", LITTER_APPLICATION_REWRITE_PUPPIES_FUR, $srst)
				->setAttribute("class", "form-control")
				->setDisabled(); */
			$container->addHidden("Srst");

			$container->addSelect("BarvaSel", DOG_FORM_FUR_COLOUR, $barvy)
				->setAttribute("class", "form-control dogRewriteDelimiter")
				->setDisabled();
			$container->addHidden("Barva");
		}

		$chovatele = $this->userRepository->findBreedersForSelect();
		$breederContainer = $form->addContainer("breeder");
		$breederContainer->addSelect("uID", DOG_FORM_BREEDER, $chovatele)->setAttribute("class", "form-control");

		$form->addSubmit("save", LITTER_APPLICATION_REWRITE_PUPPIES)
			->setAttribute("class", "btn btn-primary margin10");

		return $form;
	}
}