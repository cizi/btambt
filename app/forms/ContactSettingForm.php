<?php

namespace App\Forms;

use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette;
use Nette\Application\UI\Form;

class ContactSettingForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var LangRepository  */
	private  $langRepository;

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
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);
		$i = 0;

		$link = new Nette\Application\UI\Link($presenter, "Contact:LangChange", []);
		$form->addSelect(WebconfigRepository::KEY_WEB_MUTATION, WEBCONFIG_WEBMUTATION, $this->langRepository->findLanguages())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++)
			->setAttribute("id", "languageSwitcher")
			->setAttribute("onchange", "langChangeRedir('". $link . "')");

		$form->addText(WebconfigRepository::KEY_CONTACT_FORM_TITLE, CONTACT_FORM_SETTING_BACKEND_TITLE)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++)
			->setAttribute("placeholder", CONTACT_FORM_SETTING_BACKEND_TITLE);

		$form->addText(WebconfigRepository::KEY_CONTACT_FORM_CONTENT, CONTACT_FORM_SETTING_BACKEND_CONTENT)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", $i++)
			->setAttribute("id", "mceContactContent")
			->setAttribute("placeholder", CONTACT_FORM_SETTING_BACKEND_CONTENT);

		$form->addText(WebconfigRepository::KEY_CONTACT_FORM_BACKGROUND_COLOR, CONTACT_FORM_SETTING_BACKGROUND_COLOR)
			->setAttribute("id", "contactFormBackgroundColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		$form->addText(WebconfigRepository::KEY_CONTACT_FORM_COLOR, CONTACT_FORM_SETTING_COLOR)
			->setAttribute("id", "contactFormColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", $i++);

		$form->addText(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, CONTACT_FORM_SETTING_RECIPIENT)
			->setAttribute("id", "contactFormRecipient")
			->setAttribute("class", "form-control tinym_required_field")
			->setAttribute("validation", CONTACT_FORM_SETTING_RECIPIENT_VALIDATION)
			->setAttribute("tabindex", $i++);

		$form->addCheckbox(WebconfigRepository::KEY_CONTACT_FORM_ATTACHMENT)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", $i++);

		$form->addSubmit("confirm", CONTACT_FORM_SETTING_SAVE)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", $i++);

		return $form;
	}
}