<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\BlockRepository;
use App\Model\LangRepository;
use App\Model\UserRepository;
use Nette;
use Nette\Application\UI\Form;

class UserFilterForm  {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var LangRepository */
	private $langRepository;

	/**
	 * @param FormFactory $factory
	 * @param LangRepository $langRepository
	 */
	public function __construct(FormFactory $factory, LangRepository $langRepository) {
		$this->factory = $factory;
		$this->langRepository = $langRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();

		$form->addHidden(UserRepository::USER_CURRENT_PAGE);

		$form->addText(UserRepository::USER_SEARCH_FIELD, USER_SEARCH_FIELD	)
			->setAttribute("class", "form-control userSearchFiled")
			->setAttribute("tabindex", "1");

		$form->addSubmit("confirm", USER_SEARCH_BUTTON)
			->setAttribute("class","btn btn-primary marginMinus10")
			->setAttribute("tabindex", "2");

		return $form;
	}

}