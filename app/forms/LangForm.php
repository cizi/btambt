<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\BlockRepository;
use App\Model\LangRepository;
use Nette;
use Nette\Application\UI\Form;

class LangForm {

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

		// --- general settings
		$widthSelect = new WebWidthEnum();
		$defaultValue = $widthSelect->arrayKeyValue();
		end($defaultValue);
		$form->addSelect(LangRepository::KEY_LANG_WIDTH, LANG_WIDTH, $widthSelect->arrayKeyValue())
			->setAttribute("class", "form-control menuItem")
			->setAttribute("tabindex", $i++)
			->setDefaultValue(key($defaultValue));

		$form->addText(LangRepository::KEY_LANG_BG_COLOR, LANG_BG_COLOR)
			->setAttribute("id", "langBackgroundColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		$form->addText(LangRepository::KEY_LANG_FONT_COLOR, LANG_FONT_COLOR)
			->setAttribute("id", "langFontColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		// -- written langs
		$form->addSubmit("confirm", LANG_CONFIRM)
			->setAttribute("class","btn btn-primary menuItem alignRight")
			->setAttribute("tabindex", $i+2);

		return $form;
	}

}