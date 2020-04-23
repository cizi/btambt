<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;

class PasswordResetForm {

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
		$form->addText('login', ADMIN_LOGIN_EMAIL)
			->setAttribute("placeholder", ADMIN_LOGIN_EMAIL_PLACEHOLDER)
			->setAttribute("type", "email")
			->setAttribute("id", "inputEmail")
			->setAttribute("class", "form-control")
			->setAttribute("required", "required")
			->setAttribute("autofocus", "autofocus")
			->setRequired(ADMIN_LOGIN_EMAIL_REQ);

		$form->addSubmit('send', ADMIN_LOGIN_RESET_PASSWORD)
			->setAttribute("class", "btn btn-lg btn-primary btn-block");

		return $form;
	}
}
