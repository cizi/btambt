<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\BlockRepository;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette;

class WebconfigForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var LangRepository */
	private $langRepository;

	/** @var BlockRepository */
	private $blockRepository;

	/**
	 * @param FormFactory $factory
	 * @param LangRepository $langRepository
	 * @param BlockRepository $blockRepository
	 */
	public function __construct(FormFactory $factory, LangRepository $langRepository, BlockRepository $blockRepository) {
		$this->factory = $factory;
		$this->langRepository = $langRepository;
		$this->blockRepository = $blockRepository;
	}

	/**
	 * @param Nette\Application\UI\Presenter $presenter
	 * @param string $webCurrentLanguage
	 * @return Nette\Application\UI\Form
	 */
	public function create(Nette\Application\UI\Presenter $presenter, $webCurrentLanguage) {
		$form = $this->factory->create();

		$link = new Nette\Application\UI\Link($presenter, "Webconfig:LangChange", []);
		$form->addSelect(WebconfigRepository::KEY_WEB_MUTATION, WEBCONFIG_WEBMUTATION, $this->langRepository->findLanguages())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "1")
			->setAttribute("id", "languageSwitcher")
			->setAttribute("onchange", "langChangeRedir('". $link . "')");

		$form->addText(WebconfigRepository::KEY_WEB_TITLE, WEBCONFIG_WEB_NAME)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", WEBCONFIG_WEB_NAME)
			->setAttribute("tabindex", "2");

		$form->addText(WebconfigRepository::KEY_WEB_KEYWORDS, WEBCONFIG_WEB_KEYWORDS)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", WEBCONFIG_WEB_KEYWORDS)
			->setAttribute("tabindex", "3");

		$form->addTextArea(WebconfigRepository::KEY_WEB_GOOGLE_ANALYTICS, WEBCONFIG_WEB_GOOGLE_ANALYTICS, null, 8)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "4");

		$widthSelect = new WebWidthEnum();
		$defaultValue = $widthSelect->arrayKeyValue();
		end($defaultValue);
		$form->addSelect(WebconfigRepository::KEY_WEB_WIDTH, WEBCONFIG_WEB_WIDTH, $widthSelect->arrayKeyValue())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "5")
			->setDefaultValue(key($defaultValue));

		$form->addUpload(WebconfigRepository::KEY_FAVICON, WEBCONFIG_WEB_FAVICON)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "6");

		$form->addText(WebconfigRepository::KEY_BODY_BACKGROUND_COLOR, WEBCONFIG_WEB_BACKGROUND_COLOR)
			->setAttribute("id", "minicolorsPickerWebBg")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", "7");

		$form->addCheckbox(WebconfigRepository::KEY_WEB_SHOW_MENU)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", "8");

		$form->addCheckbox(WebconfigRepository::KEY_WEB_SHOW_HOME)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", "9");

		$form->addSelect(WebconfigRepository::KEY_WEB_HOME_BLOCK, WEBCONFIG_SETTINGS_SHOW_BLOCK, $this->blockRepository->findBlockListAsKeyValue($webCurrentLanguage))
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "10");

		$form->addText(WebconfigRepository::KEY_WEB_MENU_BG, WEBCONFIG_WEB_MENU_BACKGROUND_COLOR)
			->setAttribute("id", "minicolorsPickerMenuBg")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", "11");

		$form->addText(WebconfigRepository::KEY_WEB_MENU_LINK_COLOR, WEBCONFIG_WEB_MENU_LINK_COLOR)
			->setAttribute("id", "minicolorsPickerMenuLink")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", "12");

		$link = new Nette\Application\UI\Link($presenter, "WebPublicUtils:GenerateSiteMap", []);
		$form->addButton(WebconfigRepository::KEY_WEB_SETTING_SITEMAP_BUTTON, USER_EDIT_SITEMAP_BTN_LABEL)
			->setAttribute("class","btn")
			->setAttribute("tabindex", "13")
			->setAttribute("onclick", "window.location.href='" . $link . "'");

		$form->addSubmit("confirm", USER_EDIT_SAVE_BTN_LABEL)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", "14");

		return $form;
	}

}