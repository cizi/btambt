<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;

class SignForm {

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

		$form->addPassword('password', ADMIN_LOGIN_PASS)
			->setAttribute("placeholder", ADMIN_LOGIN_PASS_PLACEHOLDER)
			->setAttribute("type", "password")
			->setAttribute("id", "inputPassword")
			->setAttribute("class", "form-control")
			->setAttribute("required", "required")
			->setRequired(ADMIN_LOGIN_PASS_REQ);

		$form->addSelect('lang', ADMIN_LOGIN_LANG)
			->setAttribute("id", "inputLang")
			->setAttribute("class", "form-control");

		$form->addCheckbox('remember', ADMIN_LOGIN_REMEMBER_ME);

		$form->addSubmit('send', ADMIN_LOGIN_LOGIN)
			->setAttribute("class", "btn btn-lg btn-primary btn-block");

		return $form;
	}
}
