<?php

namespace App\AdminModule\Presenters;

use App\Controller\FileController;
use App\Enum\UserRoleEnum;
use App\Forms\WebconfigForm;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

class WebconfigPresenter extends SignPresenter {

	/** @consts depends on language */
	private $LANG_DEPENDS = [WebconfigRepository::KEY_WEB_TITLE, WebconfigRepository::KEY_WEB_GOOGLE_ANALYTICS, WebconfigRepository::KEY_WEB_KEYWORDS];

	/** @var WebconfigForm */
	private $configForm;

	/** @var string */
	private $webCurrentLang;

	/**
	 * @param WebconfigForm $webconfigForm
	 */
	public function __construct(WebconfigForm $webconfigForm) {
		$this->configForm = $webconfigForm;
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
		$this->webCurrentLang =  $this->langRepository->getCurrentLang($this->session);

		$defaults = $this->webconfigRepository->load($this->webCurrentLang);
		$defaults[WebconfigRepository::KEY_WEB_MUTATION] = $this->webCurrentLang;

		$defaultsCommon = $this->webconfigRepository->load(WebconfigRepository::KEY_LANG_FOR_COMMON);
		foreach ($defaultsCommon as $key => $value) {
			$defaults[$key] = $value;
		}

		$xmlSiteMapPath = $this->getHttpRequest()->getUrl()->getBaseUrl()."sitemap.xml";
		if (file_exists(SITEMAP_PATH."sitemap.xml")) {
			$this->template->sitemapfiles = [$xmlSiteMapPath];
		} else {
			$this->template->sitemapfiles = [];
		}
		$this['configForm']->setDefaults($defaults);
	}

	/**
	 * @return Form
	 */
	public function createComponentConfigForm() {
		$form = $this->configForm->create($this->presenter, $this->webCurrentLang);
		$form->onSuccess[] = [$this, 'saveValue'];

		return $form;
	}

	/**
	 * @param string $id language code (shortcut)
	 */
	public function actionLangChange($id) {
		$this->langRepository->switchToLanguage($this->session, $id);
		$this->redirect("default");
	}

	/**
	 * @param $form
	 * @param $values
	 */
	public function saveValue($form, $values) {
		$lang = $values[WebconfigRepository::KEY_WEB_MUTATION];
		unset($values[WebconfigRepository::KEY_WEB_MUTATION]); // no more needed
		unset($values[WebconfigRepository::KEY_WEB_SETTING_SITEMAP_BUTTON]);	// we don't saving this value
		foreach ($values as $key => $value) {
			if (in_array($key, $this->LANG_DEPENDS)) {
				$this->webconfigRepository->save($key, $value, $lang);
				unset($values[$key]);
			}
		}

		$lang = WebconfigRepository::KEY_LANG_FOR_COMMON;	// its going on common parameters, no language need
		$supportedFileFormats = ["ico"];
		$fileError = false;
		foreach ($values as $key => $value) {
			if ($key == WebconfigRepository::KEY_FAVICON) {
				/** @var FileUpload $file */
				$file = $value;
				if ($file->name != "") {
					$fileController = new FileController();
					if ($fileController->upload($file, $supportedFileFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
						$fileError = true;
						break;
					}
					$this->webconfigRepository->save($key, $fileController->getPathDb(), $lang);
				}
			} else {
				$this->webconfigRepository->save($key, $value, $lang);
			}
		}
		if ($fileError) {
			$flashMessage = sprintf(UNSUPPORTED_UPLOAD_FORMAT, implode(",", $supportedFileFormats));
			$this->flashMessage($flashMessage, "alert-danger");
		} else {
			$this->flashMessage(WEBCONFIG_WEB_SAVE_SUCCESS, "alert-success");
		}
		$this->redirect("default");
	}

}