<?php

namespace App\AdminModule\Presenters;

use App\Controller\FileController;
use App\Enum\UserRoleEnum;
use App\Forms\FooterForm;
use App\Model\Entity\PicEntity;
use App\Model\LangRepository;
use App\Model\PicRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;

class FooterPresenter extends SignPresenter {

	/** @consts depends on language */
	private $LANG_DEPENDS = [WebconfigRepository::KEY_FOOTER_CONTENT];

	/** @var FooterForm */
	private $footerForm;

	/** @var PicRepository */
	private $picRepository;

	/**
	 * @param WebconfigRepository $webconfigRepository
	 * @param FooterForm $footerForm
	 * @param PicRepository $picRepository
	 * @param LangRepository $langRepository
	 */
	public function __construct(
		WebconfigRepository $webconfigRepository,
		FooterForm $footerForm,
		PicRepository $picRepository
	) {
		$this->footerForm = $footerForm;
		$this->picRepository = $picRepository;
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
		$this['footerForm']->setDefaults($defaults);

		$this->template->footerPics = $this->picRepository->load();
	}

	/**
	 * @param int $id
	 */
	public function actionDeletePic($id) {
		$this->picRepository->delete($id);
		$this->flashMessage(FOOTER_PIC_DELETED, "alert-success");
		$this->redirect("default");
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentFooterForm() {
		$form = $this->footerForm->create($this->presenter);
		$form->onSuccess[] = [$this, 'saveForm'];

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
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function saveForm($form, $values) {
		$lang = $values[WebconfigRepository::KEY_WEB_MUTATION];
		unset($values[WebconfigRepository::KEY_WEB_MUTATION]); // no more needed
		foreach ($values as $key => $value) {
			if (in_array($key, $this->LANG_DEPENDS)) {
				$this->webconfigRepository->save($key, $value, $lang);
				unset($values[$key]);
			}
		}

		$valuesToSave = (array)$values;
		$supportedFilesFormat = ["png", "jpg", "bmp"];
		$fileError = false;
		if (!empty($valuesToSave[WebconfigRepository::KEY_FOOTER_FILES])) {
			/** @var FileUpload $file */
			foreach ($valuesToSave[WebconfigRepository::KEY_FOOTER_FILES] as $file) {
				if ($file->name != "") {
					$fileController = new FileController();
					if ($fileController->upload($file, $supportedFilesFormat, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
						$fileError = true;
						break;
					}
					$pic = new PicEntity();
					$pic->setPath($fileController->getPathDb());
					$this->picRepository->save($pic);
				}
			}
		}

		if ($fileError) {
			$flashMessage = sprintf(UNSUPPORTED_UPLOAD_FORMAT, implode(",", $supportedFilesFormat));
			$this->flashMessage($flashMessage, "alert-danger");
		} else {
			unset($valuesToSave[WebconfigRepository::KEY_FOOTER_FILES]);
			$land = WebconfigRepository::KEY_LANG_FOR_COMMON;
			foreach ($valuesToSave as $key => $value) {
				$this->webconfigRepository->save($key, $value, $land);

			}
			$this->flashMessage(FOOTER_SETTING_SAVED, "alert-success");
		}
		$this->redirect("default");
	}
}