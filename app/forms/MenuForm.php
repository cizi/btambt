<?php

namespace App\Forms;

use App\Model\MenuRepository;
use Nette;
use Nette\Application\UI\Form;

class MenuForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/**
	 * @param FormFactory $factory
	 */
	public function __construct(FormFactory $factory) {
		$this->factory = $factory;
	}

	/**
	 * @param array $languages
	 * @param int $level
	 * @return Form
	 */
	public function create(array $languages, $level = 1) {
		$counter = 1;
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		foreach($languages as $lang) {
			$container = $form->addContainer($lang);

			$container->addHidden("id");

			$container->addText("lang")
				->setAttribute("class", "form-control menuItem")
				->setAttribute("tabindex", "-1")
				->setAttribute("readonly", "readonly")
				->setValue($lang);

			$rewriteKey = "rewrite_" . $lang;
			$container->addText("title", MENU_SETTINGS_ITEM_NAME)
				->setAttribute("class", "form-control menuItem tinym_required_field")
				->setAttribute("validation", MENU_SETTINGS_ITEM_NAME_REQ)
				->setAttribute("onchange", 'generateURL(this)')
				->setAttribute("onkeyup", 'generateURL(this)')
				->setAttribute("validation-for", $rewriteKey)
				->setAttribute("tabindex", $counter + 1);

			$container->addText("link", MENU_SETTINGS_ITEM_LINK)
				->setAttribute("class", "form-control menuItem")
				->setAttribute("readonly", "readonly")
				->setAttribute("id", $rewriteKey)
				->setAttribute("onchange", "linkChanged()")
				->setAttribute("tabindex", $counter + 2);

			$container->addText("alt", MENU_SETTINGS_ITEM_SEO)
				->setAttribute("class", "form-control menuItem")
				->setAttribute("tabindex", $counter + 3);

			$counter += 3;
		}

		$form->addHidden("level")
			->setValue($level);

		$form->addHidden("submenu")
			->setValue(0);	// there is ID od menu record which will be a top menu for this subitem 0 means top menu item

		$form->addSubmit("confirm", USER_EDIT_SAVE_BTN_LABEL)
			->setAttribute("class","btn btn-primary menuItem alignRight")
			->setAttribute("tabindex", $counter+1);

		return $form;
	}
}