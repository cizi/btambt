<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\BlockRepository;
use App\Model\LangRepository;
use App\Model\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use App\Model\EnumerationRepository;
use App\Model\CoverageApplicationRepository;

class CoverageFilterForm  {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var LangRepository */
    private $langRepository;
    
    /** @var EnumerationRepository */
    private $enumerationRepository;

    /** @var CoverageApplicationRepository */
    private $coverageApplicationRepository;

    /** UserRepository */
    private $userRepository;

	/**
	 * @param FormFactory $factory
	 * @param LangRepository $langRepository
     * @param EnumerationRepository $enumerationRepository
     * @param CoverageApplicationRepository coverageApplicationRepository
     * @param UserRepository userRepository
	 */
	public function __construct(FormFactory $factory, LangRepository $langRepository, EnumerationRepository $enumerationRepository, CoverageApplicationRepository $coverageApplicationRepository, UserRepository $userRepository) {
		$this->factory = $factory;
        $this->langRepository = $langRepository;
        $this->enumerationRepository = $enumerationRepository;
        $this->coverageApplicationRepository = $coverageApplicationRepository;
        $this->userRepository = $userRepository;
	}

	/**
	 * @return Form
	 */
	public function create($currentLang) {
		$form = $this->factory->create();

        $kls = $this->coverageApplicationRepository->findCoverageApplications();
        $uzivatele = [0 => EnumerationRepository::NOT_SELECTED];
        foreach ($kls as $kl) {
            $uz = $this->userRepository->getUser($kl->getUID());
            $uzivatele[$kl->getUID()] = $uz->getFullName();
        }

        $females = $this->coverageApplicationRepository->findCoverageFemalesForSelect();
        $form->addSelect("mID", MATING_FORM_MID, $females)
			->setAttribute("class", "form-control");

		$form->addSelect("uID", USER_OWNER, $uzivatele)
			->setAttribute("class", "form-control");
            
        $roky = $this->coverageApplicationRepository->findCoverageYearsForSelect();
        $form->addSelect("Datum", SHOW_FRONTEND_YEAR, $roky)
			->setAttribute("class", "form-control");

        $plemeno = $this->enumerationRepository->findEnumItemsForSelectWithEmpty($currentLang, 18);
        $form->addSelect("Plemeno", USER_EDIT_BREED_LABEL, $plemeno)
            ->setAttribute("class", "form-control");

		$form->addSubmit("confirm", DOG_TABLE_BTN_FILTER)
			->setAttribute("class","btn btn-primary margin5");

		return $form;
	}

}