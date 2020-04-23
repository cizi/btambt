<?php

namespace App\AdminModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Forms\ContactSettingForm;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class ContactPresenter extends SignPresenter {

	/** @consts depends on language */
	private $LANG_DEPENDS = [WebconfigRepository::KEY_CONTACT_FORM_TITLE, WebconfigRepository::KEY_CONTACT_FORM_CONTENT];

	/** @var ContactSettingForm */
	private $contactSettingForm;

	/**
	 * @param WebconfigRepository $webconfigRepository
	 * @param ContactSettingForm $contactSettingForm
	 * @param LangRepository $langRepository
	 */
	public function __construct(WebconfigRepository $webconfigRepository, ContactSettingForm $contactSettingForm, LangRepository $langRepository) {
		$this->contactSettingForm = $contactSettingForm;
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

	public function actionDefault() {
		$webCurrentLang =  $this->langRepository->getCurrentLang($this->session);

		$defaults = $this->webconfigRepository->load($webCurrentLang);
		$defaultsCommon = $this->webconfigRepository->load(WebconfigRepository::KEY_LANG_FOR_COMMON);
		foreach ($defaultsCommon as $key => $value) {
			$defaults[$key] = $value;
		}
		$defaults[WebconfigRepository::KEY_WEB_MUTATION] = $webCurrentLang;
		$this['contactSettingForm']->setDefaults($defaults);
	}

	/**
	 * @param string $id language code (shortcut)
	 */
	public function actionLangChange($id) {
		$this->langRepository->switchToLanguage($this->session, $id);
		$this->redirect("default");
	}

	/**
	 * @return Form
	 */
	public function createComponentContactSettingForm() {
		$form = $this->contactSettingForm->create($this->presenter);
		$form->onSuccess[] = [$this, 'saveForm'];

		return $form;
	}

	/**
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function saveForm(Form $form, $values) {
		$lang = $values[WebconfigRepository::KEY_WEB_MUTATION];
		unset($values[WebconfigRepository::KEY_WEB_MUTATION]); // no more needed
		foreach ($values as $key => $value) {
			if (in_array($key, $this->LANG_DEPENDS)) {
				$this->webconfigRepository->save($key, $value, $lang);
				unset($values[$key]);
			}
		}

		foreach ($values as $key => $value) {
			$this->webconfigRepository->save($key, $value, WebconfigRepository::KEY_LANG_FOR_COMMON);
		}
		$this->flashMessage(CONTACT_FORM_SETTING_COMPLETE_SAVE, "alert-success");
		$this->redirect('default');
	}
}