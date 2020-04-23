<?php

namespace App\Forms;

use Nette\Forms\Form;
use Nette;

class UserChangePasswordForm {

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
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);
		$tabIndex = 1;

		$form->addPassword("passwordCurrent", USER_EDIT_CURRENT_PASSWORD)
			->setAttribute("type","password")
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_CURRENT_PASSWORD)
			->setAttribute("validation", USER_EDIT_CURRENT_PASSWORD_REQ)
			->setAttribute("tabindex", $tabIndex++);

		$form->addPassword("password", USER_EDIT_PASS_LABEL)
			->setAttribute("type","password")
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_PASS_LABEL)
			->setAttribute("validation", USER_EDIT_PASS_REQ)
			->setAttribute("tabindex", $tabIndex++);

		$form->addPassword("passwordConfirm", USER_EDIT_PASS_AGAIN_LABEL)
			->setAttribute("type","password")
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_PASS_AGAIN_LABEL)
			->setAttribute("validation", USER_EDIT_PASS_AGAIN_REQ)
			->setAttribute("tabindex", $tabIndex++);

		$form->addSubmit("confirm", USER_EDIT_SAVE_BTN_LABEL)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", $tabIndex++);

		return $form;
	}
}