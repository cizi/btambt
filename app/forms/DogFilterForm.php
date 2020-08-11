<?php

namespace App\Forms;

use App\Enum\OrderingEnum;
use App\Enum\StateEnum;
use App\Model\DogRepository;
use App\Model\EnumerationRepository;
use App\Model\UserRepository;
use Nette\Application\UI\Form;
use App\Enum\UserRoleEnum;

class DogFilterForm {

	/** @const pro speciální filtry */
	const DOG_FILTER_PROB_DKK = "DOG_FILTER_PROB_DKK";
	const DOG_FILTER_PROB_DLK = "DOG_FILTER_PROB_DLK";
	const DOG_FILTER_HEALTH = "DOG_FILTER_HEALTH";
	const DOG_FILTER_HEALTH_TEXT = "DOG_FILTER_HEALTH_TEXT";
	const DOG_FILTER_LAND = "DOG_FILTER_LAND";
	const DOG_FILTER_BREEDER = "DOG_FILTER_BREEDER";
	const DOG_FILTER_EXAM = "Zkousky";
	const DOG_FILTER_BIRTDATE = "DatNarozeni";
	const DOG_FILTER_ORDER_NUMBER = "CisloZapisuOrder";
	const DOG_FILTER_LAST_14_DAYS = "Poslednich14Dnu";

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var UserRepository */
	private $userRepository;

	/** @var  DogRepository */
	private $dogRepository;

	/**
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 * @param UserRepository $userRepository
	 * @param DogRepository $dogRepository
	 */
	public function __construct(
		FormFactory $factory,
		EnumerationRepository $enumerationRepository,
		UserRepository $userRepository,
		DogRepository $dogRepository
	) {
		$this->factory = $factory;
		$this->enumerationRepository = $enumerationRepository;
		$this->userRepository = $userRepository;
		$this->dogRepository = $dogRepository;
	}

	/**
	 * @return Form
	 */
	public function create($langCurrent, $user) {
		$form = $this->factory->create();
		$form->addGroup(DOG_TABLE_FILTER_LABEL)	;
		//$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$form->addText("Jmeno", DOG_TABLE_HEADER_NAME)
			->setAttribute("class", "form-control");

		$plemena = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::PLEMENO);
		$form->addSelect("Plemeno", DOG_TABLE_HEADER_BREED, $plemena)
			->setAttribute("class", "form-control");

		$orderingEnum = new OrderingEnum();
		$ordering = $orderingEnum->translatedForSelect(true);
		$form->addSelect(self::DOG_FILTER_ORDER_NUMBER, DOG_TABLE_HEADER_WRITE_NUMBER, $ordering)
            ->setAttribute("class", "form-control");
            
        if ($user->getRoles()[0] >= UserRoleEnum::USER_EDITOR) {
            $form->addText("Cip", DOG_FORM_NO_OF_CHIP)
                ->setAttribute("class", "form-control");
        }

		$barvy = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::BARVA);
		$form->addSelect("Barva", DOG_TABLE_HEADER_COLOR, $barvy)
			->setAttribute("class", "form-control");

		$pohlavi = $this->enumerationRepository->findEnumItemsForSelectWithEmpty($langCurrent, EnumerationRepository::POHLAVI);
		$form->addSelect("Pohlavi", DOG_TABLE_HEADER_SEX, $pohlavi)
			->setAttribute("class", "form-control");

		$years = $this->dogRepository->findBirtYearsForSelect();
		$form->addSelect(self::DOG_FILTER_BIRTDATE, DOG_TABLE_HEADER_BIRT, $years)
			//->setAttribute("id", "DatNarozeni")
			->setAttribute("class", "form-control");

		$chovnost = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::CHOVNOST);
		$form->addSelect("Chovnost", DOG_TABLE_HEADER_BREEDING, $chovnost)
			->setAttribute("class", "form-control");

		/* $dkk = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, 15);
		$form->addSelect(self::DOG_FILTER_PROB_DKK, DOG_TABLE_HEADER_PROB_DKK, $dkk)
			->setAttribute("class", "form-control");

		$dlk = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, 16);
		$form->addSelect(self::DOG_FILTER_PROB_DLK, DOG_TABLE_HEADER_PROB_DLK, $dlk)
			->setAttribute("class", "form-control"); */

		$zdravi = $this->enumerationRepository->findEnumItemsForSelectWithEmpty($langCurrent, 14);
		$form->addSelect(self::DOG_FILTER_HEALTH, DOG_TABLE_HEADER_HEALTH, $zdravi)
			->setAttribute("class", "form-control");

		$form->addText(self::DOG_FILTER_HEALTH_TEXT, DOG_TABLE_HEADER_HEALTH_TEXT)
			->setAttribute("class", "form-control");

        $states = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::ZEME);
		$form->addSelect(self::DOG_FILTER_LAND, DOG_TABLE_HEADER_LAND, $states)
			->setAttribute("class", "form-control");

        if ($user->getRoles()[0] >= UserRoleEnum::USER_EDITOR) {
            $chovatele = $this->userRepository->findBreedersForSelect();
            $form->addSelect(self::DOG_FILTER_BREEDER, DOG_TABLE_HEADER_BREEDER, $chovatele)
                ->setAttribute("class", "form-control");
        }

        $exams = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, EnumerationRepository::ZKOUSKY);
		$form->addMultiSelect(self::DOG_FILTER_EXAM, DOG_TABLE_HEADER_EXAM, $exams)
			->setAttribute("class", "form-control chosen-select");

        // ChovnyKomentar je CZDR
		$form->addSelect("ChovnyKomentar", DOG_FORM_BREEDING_COM, [0 => "-", "CZDR I." => "CZDR I.", "CZDR II." => "CZDR II."])
            ->setAttribute("class", "form-control");

		$form->addCheckbox(self::DOG_FILTER_LAST_14_DAYS, DOG_TABLE_LAST_14_DAYS)
			->setAttribute("class", "margin10");

        $form->addSubmit("filter", DOG_TABLE_BTN_FILTER)
			->setAttribute("class","btn btn-info margin10");

        $form->addSubmit("clearFilter", DOG_TABLE_BTN_CLEAR_FILTER)
			->setAttribute("class","btn btn-danger margin10");

		return $form;
	}

}