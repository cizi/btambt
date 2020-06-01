<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette;

class CounterForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/**
	 * @param FormFactory $factory
	 * @param LangRepository $langRepository
	 */
	public function __construct(FormFactory $factory) {
		$this->factory = $factory;
	}

	/**
	 * @param Nette\Application\UI\Presenter $presenter
	 * @param string $webCurrentLanguage
	 * @return Nette\Application\UI\Form
	 */
	public function create(Nette\Application\UI\Presenter $presenter) {
		$form = $this->factory->create();

		$form->addText(WebconfigRepository::KEY_COUNTER_COVERAGE_BT, COUNTER_COVERAGE_BT_COUNTER)
			->setAttribute("class", "form-control")
            ->setAttribute("tabindex", "1")
            ->setRequired()
	        ->addRule(Form::INTEGER);

		$form->addText(WebconfigRepository::KEY_COUNTER_COVERAGE_MBT, COUNTER_COVERAGE_MBT_COUNTER)
			->setAttribute("class", "form-control")
            ->setAttribute("tabindex", "2")
            ->setRequired()
            ->addRule(Form::INTEGER);
            
        $form->addText(WebconfigRepository::KEY_COUNTER_APPLICAIOTN_FORM_BT, COUNTER_LITTER_BT_COUNTER)
			->setAttribute("class", "form-control")
            ->setAttribute("tabindex", "3")
            ->setRequired()
	        ->addRule(Form::INTEGER);

		$form->addText(WebconfigRepository::KEY_COUNTER_APPLICAIOTN_FORM_MBT, COUNTER_LITTER_MBT_COUNTER)
			->setAttribute("class", "form-control")
            ->setAttribute("tabindex", "4")
            ->setRequired()
	        ->addRule(Form::INTEGER);

		$form->addSubmit("confirm", USER_EDIT_SAVE_BTN_LABEL)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", "3");

		return $form;
	}

}