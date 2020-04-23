<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette;
use Nette\Application\UI\Form;

class FooterForm {

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
	 * @param Nette\Application\UI\Presenter $presenter
	 * @return Form
	 */
	public function create(Nette\Application\UI\Presenter $presenter) {
		$form = $this->factory->create();
		$i = 0;

		$link = new Nette\Application\UI\Link($presenter, "Footer:LangChange", []);
		$form->addSelect(WebconfigRepository::KEY_WEB_MUTATION, WEBCONFIG_WEBMUTATION, $this->langRepository->findLanguages())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++)
			->setAttribute("id", "languageSwitcher")
			->setAttribute("onchange", "langChangeRedir('". $link . "')");

		$form->addCheckbox(WebconfigRepository::KEY_SHOW_FOOTER)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", $i++);

		$widthSelect = new WebWidthEnum();
		$defaultValue = $widthSelect->arrayKeyValue();
		end($defaultValue);
		$form->addSelect(WebconfigRepository::KEY_FOOTER_WIDTH, FOOTER_WIDTH, $widthSelect->arrayKeyValue())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "2")
			->setDefaultValue(key($defaultValue));

		$form->addText(WebconfigRepository::KEY_FOOTER_BACKGROUND_COLOR, CONTACT_FORM_SETTING_COLOR)
			->setAttribute("id", "footerBackgroundColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		$form->addText(WebconfigRepository::KEY_FOOTER_COLOR, CONTACT_FORM_SETTING_COLOR)
			->setAttribute("id", "footerColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		$form->addCheckbox(WebconfigRepository::KEY_SHOW_CONTACT_FORM_IN_FOOTER)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", $i++);

		$form->addTextArea(WebconfigRepository::KEY_FOOTER_CONTENT, FOOTER_CONTENT)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", FOOTER_CONTENT)
			->setAttribute("id", "mceFooterContent")
			->setAttribute("tabindex", $i++);

		$form->addMultiUpload(WebconfigRepository::KEY_FOOTER_FILES)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++);

		$form->addSubmit("confirm", FOOTER_BUTTON_SAVE)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", $i++);

		return $form;
	}

}