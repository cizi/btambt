<?php

namespace App\AdminModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Forms\LangForm;
use App\Forms\LangItemForm;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;

class LangPresenter extends SignPresenter {

	/** @var LangForm $langForm */
	private $langForm;

	/** @var LangItemForm */
	private $langItemForm;

	public function __construct(LangForm $langForm, LangItemForm $langItemForm) {
		$this->langForm = $langForm;
		$this->langItemForm = $langItemForm;
	}

	/**
	 * Pokud nejsem admin tak tady nemám co dělat
	 */
	public function startup() {
		parent::startup();
		if (($this->getUser()->getRoles()[0] == UserRoleEnum::USER_EDITOR)) {
			$this->redirect("Referee:Default");
		}
	}

	public function renderDefault() {
		$defaults = $this->webconfigRepository->load(WebconfigRepository::KEY_LANG_FOR_COMMON);
		/*foreach ($defaultsCommon as $key => $value) {
			$defaults[$key] = $value;
		} */
		$this['langForm']->setDefaults($defaults);

		$this->template->langFlagKey = LangRepository::KEY_LANG_ITEM_FLAG;
		$this->template->langMutations = $this->langRepository->findLanguagesWithFlags();
	}

	public function createComponentLangForm() {
		$form = $this->langForm->create();
		$form->onSuccess[] = [$this, 'saveLangCommon'];

		return $form;
	}

	public function saveLangCommon($form, $values) {
		foreach ($values as $key => $value) {
			if ($value != "") {
				$this->webconfigRepository->save($key, $value, WebconfigRepository::KEY_LANG_FOR_COMMON);
			}
		}
	}

	public function createComponentLangItemForm() {
		$form = $this->langItemForm->create();
		$form->onSuccess[] = [$this, 'saveLangItem'];

		return $form;
	}

	public function saveLangItem() {

	}

	public function actionDelete($id) {
		$this->redirect('default');
	}
}