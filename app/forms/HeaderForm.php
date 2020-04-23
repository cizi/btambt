<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette;
use Nette\Application\UI\Form;

class HeaderForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var LangRepository */
	private $langRepository;

	/**
	 * @param FormFactory $factory
	 */
	public function __construct(FormFactory $factory, LangRepository $langRepository) {
		$this->factory = $factory;
		$this->langRepository = $langRepository;
	}

	/**
	 * @return Form
	 */
	public function create(Nette\Application\UI\Presenter $presenter) {
		$form = $this->factory->create();
		$i=0;

		$link = new Nette\Application\UI\Link($presenter, "Header:LangChange", []);
		$form->addSelect(WebconfigRepository::KEY_WEB_MUTATION, WEBCONFIG_WEBMUTATION, $this->langRepository->findLanguages())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++)
			->setAttribute("id", "languageSwitcher")
			->setAttribute("onchange", "langChangeRedir('". $link . "')");

		$form->addCheckbox(WebconfigRepository::KEY_SHOW_HEADER)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", $i++);

		$form->addText(WebconfigRepository::KEY_HEADER_HEIGHT, HEADER_HEIGHT)
			->setType("number")
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++);

		$widthSelect = new WebWidthEnum();
		$defaultValue = $widthSelect->arrayKeyValue();
		end($defaultValue);
		$form->addSelect(WebconfigRepository::KEY_HEADER_WIDTH, HEADER_WIDTH, $widthSelect->arrayKeyValue())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++)
			->setDefaultValue(key($defaultValue));

		$form->addText(WebconfigRepository::KEY_HEADER_BACKGROUND_COLOR, HEADER_SETTING_COLOR)
			->setAttribute("id", "headerBackgroundColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		$form->addText(WebconfigRepository::KEY_HEADER_COLOR, HEADER_SETTING_COLOR)
			->setAttribute("id", "headerColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		$form->addTextArea(WebconfigRepository::KEY_HEADER_CONTENT, HEADER_CONTENT)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", HEADER_CONTENT)
			->setAttribute("id", "mceFooterContent")
			->setAttribute("tabindex", $i++);

		$form->addMultiUpload(WebconfigRepository::KEY_HEADER_FILES)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++);

		$form->addSubmit("confirm", HEADER_BUTTON_SAVE)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", "8");

		return $form;
	}

}