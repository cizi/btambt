<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\BlockRepository;
use App\Model\LangRepository;
use Nette;
use Nette\Application\UI\Form;

class LangItemForm {

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
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);
		$i = 1;

		// -- new lang setting
		$form->addUpload(LangRepository::KEY_LANG_ITEM_FLAG, LANG_ITEM_FLAG)
			->setAttribute("class", "form-control menuItem")
			->setAttribute("tabindex", $i++);

		$form->addText(LangRepository::KEY_LANG_ITEM_DESC, LANG_ITEM_DESC)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++);

		$form->addText(LangRepository::KEY_LANG_ITEM_SHORT, LANG_ITEM_SHORT)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++);

		$form->addSubmit("confirm", LANG_CONFIRM)
			->setAttribute("class","btn btn-primary menuItem alignRight")
			->setAttribute("tabindex", $i+2);

		return $form;
	}

}