<?php

namespace App\FrontendModule\Presenters;

use App\AdminModule\Presenters\BlockContentPresenter;
use App\Controller\EmailController;
use App\Controller\FileController;
use App\Controller\MenuController;
use App\Enum\WebWidthEnum;
use App\Forms\ContactForm;
use App\Forms\PasswordResetForm;
use App\Model\BlockRepository;
use App\Model\LangRepository;
use App\Model\MenuRepository;
use App\Model\SliderPicRepository;
use App\Model\SliderSettingRepository;
use App\Model\UserRepository;
use App\Model\WebconfigRepository;
use Dibi\Exception;
use Nette;
use Nette\Application\UI\Presenter;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter {

	/** @const odd�lova� levelu po�ad� v presenterech */
	const LEVEL_ORDER_DELIMITER = "velord";

	/** @ const prefix pro v�echny presentery */
	const PRESENTER_PREFIX = "FeItem";

	/** @var WebconfigRepository */
	protected $webconfigRepository;

	/** @var SliderSettingRepository */
	private $sliderSettingRepository;

	/** var SliderPicRepository */
	private $sliderPicRepository;

	/** @var ContactForm */
	private $contactForm;

	/** @var MenuController */
	private $menuController;

	/** @var MenuRepository */
	protected $menuRepository;

	/** @var FileController */
	private $fileController;

	/** @var BlockRepository */
	protected $blockRepository;

	/** @var LangRepository */
	protected $langRepository;

	/** @var PasswordResetForm */
	private $passwordResetForm;

	/** @var UserRepository */
	private $userRepository;

	/**
	 * @param LangRepository $langRepository
	 */
	public function injectBaseSettings(
		WebconfigRepository $webconfigRepository,
		SliderSettingRepository $sliderSettingRepository,
		SliderPicRepository $sliderPicRepository,
		ContactForm $contactForm,
		MenuController $menuController,
		MenuRepository $menuRepository,
		FileController $fileController,
		BlockRepository $blockRepository,
		LangRepository $langRepository,
		PasswordResetForm $passwordResetForm,
		UserRepository $userRepository
	) {
		$this->webconfigRepository = $webconfigRepository;
		$this->sliderSettingRepository = $sliderSettingRepository;
		$this->sliderPicRepository = $sliderPicRepository;
		$this->contactForm = $contactForm;
		$this->menuController = $menuController;
		$this->menuRepository = $menuRepository;
		$this->fileController = $fileController;
		$this->blockRepository = $blockRepository;
		$this->langRepository = $langRepository;
		$this->passwordResetForm = $passwordResetForm;
		$this->userRepository = $userRepository;
	}

	public function startup() {
		parent::startup();

		// language setting
		$lang = $this->langRepository->getCurrentLang($this->session);
		if (!isset($lang) || $lang == "") {
			$lang = $this->context->parameters['language']['default'];
			$this->langRepository->switchToLanguage($this->session, $lang);
		}
		$this->langRepository->loadLanguageMutation($lang);

		$lang = $this->langRepository->getCurrentLang($this->session);

		if (strpos($this->getName(), 'Admin:') === false) {		// tohle m� zaj�m� jen frontendu
			// load another page settings
			$this->loadWebConfig($lang);
			$this->loadHeaderConfig();
			$this->loadLanguageStrap();
			$this->loadSliderConfig();
			$this->loadFooterConfig();

			$this->template->currentLang = $lang;
			$this->template->menuHtml = $this->menuController->renderMenuInFrontend($this->presenter, $lang);
			$this->template->contactFormId = BlockContentPresenter::CONTACT_FORM_ID_AS_BLOCK;
			$this->template->widthEnum = new WebWidthEnum();
		}
	}

	/**
	 * Přepne jazyk
	 * @param string $id
	 */
	public function actionToLanguage($id) {
		$lang = $id;
		$availableLangs = $this->langRepository->findLanguages();
		// what if link will have the same shortcut like language
		if (isset($availableLangs[$lang]) && ($lang != $this->langRepository->getCurrentLang($this->session))) {
			$this->langRepository->switchToLanguage($this->session, $lang);
		}
		$this->redirect("Homepage:default", [ 'lang' => $lang, 'id' => $id ]);
	}

	/**
	 * Proceed contact form
	 *
	 * @param Nette\Application\UI\Form $form
	 * @param $values
	 * @throws \Exception
	 * @throws \phpmailerException
	 */
	public function contactFormSubmitted($form, $values) {
		if (
			isset($values['contactEmail']) && $values['contactEmail'] != ""
			&& isset($values['name']) && $values['name'] != ""
			&& isset($values['subject']) && $values['subject'] != ""
			&& isset($values['text']) && $values['text'] != ""
		) {
			$supportedFilesFormat = ["png", "jpg", "bmp", "pdf", "doc", "xls", "docx", "xlsx"];
			$fileError = false;
			$path = "";
			if (!empty($values['attachment'])) {
				/** @var FileUpload $file */
				$file = $values['attachment'];
				if (!empty($file->name)) {
					$fileController = new FileController();
					if ($fileController->upload($file, $supportedFilesFormat, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
						$fileError = true;
						$this->flashMessage(CONTACT_FORM_UNSUPPORTED_FILE_FORMAT, "alert-danger");
					} else {
						$path = $fileController->getPath();
					}
				}
			}

			if ($fileError == false) {
                $attachments = [];
				if (!empty($path)) {
                    $attachments[] = $path;
				}
                $emailTo = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
                EmailController::SendPlainEmail($values['contactEmail'], $emailTo, CONTACT_FORM_EMAIL_MY_SUBJECT . " - " . $values['subject'], $values['text'], $attachments, $values['name']);
				$this->flashMessage(CONTACT_FORM_WAS_SENT, "alert-success");
			}
		} else {
			$this->flashMessage(CONTACT_FORM_SENT_FAILED, "alert-danger");
		}
		$this->redirect("default");
	}

	/**
	 * Vytvoří componentu kontaktního formuláře
	 * @return Nette\Application\UI\Form
	 */
	public function createComponentContactForm() {
		$form = $this->contactForm->create();
		if ($this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON) == "") {
			$form["confirm"]->setDisabled();
		}
		$form->onSuccess[] = [$this, 'contactFormSubmitted'];
		return $form;
	}

	protected function getMenuLevelPresenter($presenterName) {
		$completePath = preg_split('/:/', $presenterName);
		$data = str_replace(self::PRESENTER_PREFIX, "", $completePath[1]);
		return intval($data);
	}

	protected function getMenuOrderPresenter($presenterName) {
		$data = preg_split('/' .self::LEVEL_ORDER_DELIMITER . '/', $presenterName);
		return intval($data[1]);
	}

	/**
	 * Vytvoří odkaz podle úrovně a po�ad�
	 * @param int $level
	 * @param int $order
	 * @return string
	 */
	protected function getPresenterLink($level, $order) {
		return self::PRESENTER_PREFIX . $level . self::LEVEL_ORDER_DELIMITER . $order;
	}

	protected function createComponentPasswordResetForm() {
		$form = $this->passwordResetForm->create();
		$form->onSubmit[] = [$this, 'resetUserPassword'];

		return $form;
	}

	/**
	 * @param Nette\Forms\Form $form
	 */
	public function resetUserPassword(Nette\Forms\Form $form) {
		$values = $form->getHttpData();
		if (isset($values["login"]) && $values["login"] != "") {
			$user = $this->userRepository->getUserByEmail(trim($values["login"]));
			if ($user != null) {
				try {
					$newPass = $this->userRepository->resetUserPassword($user);
					$emailFrom = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_RECIPIENT, WebconfigRepository::KEY_LANG_FOR_COMMON);
                    $webTitle =  $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_TITLE, $this->langRepository->getCurrentLang($this->session));
                    $subject = $webTitle . " - " . ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_SUBJECT_PLAIN; // . " - " . sprintf(ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_SUBJECT, $this->getHttpRequest()->getUrl()->getBaseUrl());
					$body = sprintf(ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_BODY, $this->getHttpRequest()->getUrl()->getBaseUrl(), $newPass);
					EmailController::SendPlainEmail($emailFrom, $user->getEmail(), $subject, $body);

					$this->flashMessage(ADMIN_LOGIN_RESET_SUCCESS, "alert-success");
					$this->redirect("default");
				} catch (Exception $e) {
					if ($e instanceof Nette\Application\AbortException) {
						throw $e;
					} else {
						$this->flashMessage(ADMIN_LOGIN_RESET_FAILED, "alert-danger");
						$form->addError(ADMIN_LOGIN_RESET_FAILED);
					}
				}
			} else {
				$this->flashMessage(ADMIN_LOGIN_RESET_PASSWORD_EMAIL_FAIL, "alert-danger");
				$form->addError(ADMIN_LOGIN_RESET_PASSWORD_EMAIL_FAIL);
			}
		}
	}

	/**
	 * It loads config from admin to page
	 */
	private function loadWebConfig($lang) {
		// depending on language
		$this->template->title = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_TITLE, $lang);
		$this->template->googleAnalytics = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_GOOGLE_ANALYTICS, $lang);
		$this->template->webKeywords = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_KEYWORDS, $lang);

		// language free
		$widthEnum = new WebWidthEnum();
		$langCommon = WebconfigRepository::KEY_LANG_FOR_COMMON;
		$this->template->favicon = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_FAVICON, $langCommon);
		$this->template->bodyWidth = $widthEnum->getValueByKey($this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_WIDTH, $langCommon));
		$this->template->bodyBackgroundColor = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_BODY_BACKGROUND_COLOR, $langCommon);
		$this->template->showMenu = ($this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_SHOW_MENU, $langCommon) == 1 ? true : false);
		$this->template->showHomeButtonInMenu = ($this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_SHOW_HOME, $langCommon) == 1 ? true : false);
		$this->template->menuColor = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_MENU_BG, $langCommon);
		$this->template->menuLinkColor = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_MENU_LINK_COLOR, $langCommon);
	}

	/**
	 * Loads language strap configuration
	 */
	private function loadLanguageStrap() {
		if (count($this->langRepository->findLanguages()) > 1) {
			// language free
			$widthEnum = new WebWidthEnum();
			$langCommon = WebconfigRepository::KEY_LANG_FOR_COMMON;

			$this->template->languageStrapShow = true;
			$this->template->languageStrapWidth = $widthEnum->getValueByKey($this->webconfigRepository->getByKey(LangRepository::KEY_LANG_WIDTH, $langCommon));
			$this->template->languageStrapBgColor = $this->webconfigRepository->getByKey(LangRepository::KEY_LANG_BG_COLOR, $langCommon);
			$this->template->languageStrapFontColor = $this->webconfigRepository->getByKey(LangRepository::KEY_LANG_FONT_COLOR, $langCommon);
			$this->template->langFlagKey = LangRepository::KEY_LANG_ITEM_FLAG;
			$this->template->languageStrapLanguages = $this->langRepository->findLanguagesWithFlags();
		} else {
			$this->template->languageStrapShow = false;
		}
	}

	/**
	 * Loads configuration for static header
	 */
	private function loadHeaderConfig() {
		// language free
		$langCommon = WebconfigRepository::KEY_LANG_FOR_COMMON;
		$this->template->showHeader = $showHeader = ($this->webconfigRepository->getByKey(WebconfigRepository::KEY_SHOW_HEADER, $langCommon) == 1 ? true : false);
		if ($showHeader) {
			$widthEnum = new WebWidthEnum();

			$this->template->headerBg = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_HEADER_BACKGROUND_COLOR, $langCommon);
			$this->template->headerColor = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_HEADER_COLOR, $langCommon);
			$this->template->headerWidth = $widthEnum->getValueByKey($this->webconfigRepository->getByKey(WebconfigRepository::KEY_HEADER_WIDTH, $langCommon));
			$this->template->headerHeight = (int)$this->webconfigRepository->getByKey(WebconfigRepository::KEY_HEADER_HEIGHT, $langCommon);

			// img path fixing
			$headerContent = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_HEADER_CONTENT, $this->langRepository->getCurrentLang($this->session));
			$this->template->headerContent = str_replace("../../upload/", "./upload/", $headerContent);
		}
	}

	/**
	 * It loads slider option to page
	 */
	private function loadSliderConfig() {
		// slider and its pics
		if ($this->sliderSettingRepository->getByKey(SliderSettingRepository::KEY_SLIDER_ON)) {
			$this->template->sliderEnabled = true;
			$this->template->sliderPics = $this->sliderPicRepository->findPics();

			$widthEnum = new WebWidthEnum();
			$widthOption = $this->sliderSettingRepository->getByKey(SliderSettingRepository::KEY_SLIDER_WIDTH);
			$width = $widthEnum->getValueByKey($widthOption);
			$this->template->sliderWidth = (empty($width) ? "100%" : $width);
			$this->template->sliderSpeed = $this->sliderSettingRepository->getByKey(SliderSettingRepository::KEY_SLIDER_TIMING) * 1000;
			$this->template->slideShow = ($this->sliderSettingRepository->getByKey(SliderSettingRepository::KEY_SLIDER_SLIDE_SHOW) == "1" ? true : false);
			$this->template->sliderControls = ($this->sliderSettingRepository->getByKey(SliderSettingRepository::KEY_SLIDER_CONTROLS) == "1" ? true : false);
		} else {
			$this->template->sliderEnabled = false;
			$this->template->sliderPics = [];
		}
	}

	/**
	 * It loads info about footer
	 */
	private function loadFooterConfig() {
		$langCommon = WebconfigRepository::KEY_LANG_FOR_COMMON;
		$this->template->showFooter = $showFooter = ($this->webconfigRepository->getByKey(WebconfigRepository::KEY_SHOW_FOOTER, $langCommon) == 1 ? true : false);
		if ($showFooter) {
			$widthEnum = new WebWidthEnum();

			$this->template->footerBg = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_FOOTER_BACKGROUND_COLOR, $langCommon);
			$this->template->footerColor = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_FOOTER_COLOR, $langCommon);
			$this->template->footerWidth = $widthEnum->getValueByKey($this->webconfigRepository->getByKey(WebconfigRepository::KEY_FOOTER_WIDTH, $langCommon));

			// img path fixing
			$footerContent = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_FOOTER_CONTENT, $this->langRepository->getCurrentLang($this->session));
			$this->template->footerContent = str_replace("../../upload/", "./upload/", $footerContent);
		}

		$contactFormInFooter = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_SHOW_CONTACT_FORM_IN_FOOTER, $langCommon);
		$this->template->isContactFormInFooter = ($contactFormInFooter == "1" ? true : false);
		if ($contactFormInFooter) {
			$this->template->contactFormHeader = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_TITLE, $this->langRepository->getCurrentLang($this->session));
			$this->template->contactFormContent = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_CONTENT, $this->langRepository->getCurrentLang($this->session));
			$this->template->contactFormBackground = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_BACKGROUND_COLOR, $langCommon);
			$this->template->contactFormColor = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_COLOR, $langCommon);

			$allowAttachment = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_ATTACHMENT, $langCommon);
			$this->template->allowAttachment =  ($allowAttachment == "1" ? true : false);
		}
	}

	/**
	 * @return array
	 */
	public function decodeFilterFromQuery() {
		$filter = [];
		if ($this->filter != "") {
			$arr = explode("&", $this->filter);
			foreach ($arr as $filterItem) {
				$filterPiece = explode("=", $filterItem);
				if (
					(count($filterPiece) > 1)
					&& ($filterPiece[0] != "")
					&& ($filterPiece[1] != "")
					&& ($filterPiece[0] != "filter")
					&& ($filterPiece[0] != "do")
					&& ($filterPiece[0] != "_do")
					&& ($filterPiece[1] != "0")
				) {
                    if (strpos($filterPiece[1], '##') !== false) {
                        $filter[$filterPiece[0]] = explode("##", $filterPiece[1]);
                    } else {
                        $filter[$filterPiece[0]] = $filterPiece[1];
                    }
				}
			}
		}

		return $filter;
	}
}
