<?php

namespace App\AdminModule\Presenters;

use App\Enum\UserRoleEnum;
use App\Forms\HeaderForm;
use App\Controller\FileController;
use App\Model\Entity\PicEntity;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;
use App\Model\PicRepository;

class HeaderPresenter extends SignPresenter {

	/** @consts depends on language */
	private $LANG_DEPENDS = [WebconfigRepository::KEY_HEADER_CONTENT];

	/** @var HeaderForm */
	private $headerForm;

	/** @var PicRepository */
	private $picRepository;

	/**
	 * @param HeaderForm $headerForm
	 * @param PicRepository $picRepository
	 */
	public function __construct(HeaderForm $headerForm, PicRepository $picRepository) {
		$this->headerForm = $headerForm;
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
		$this['headerForm']->setDefaults($defaults);

		$this->template->headerPics = $this->picRepository->load();
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
	 * @param string $id language code (shortcut)
	 */
	public function actionLangChange($id) {
		$this->langRepository->switchToLanguage($this->session, $id);
		$this->redirect("default");
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentHeaderForm() {
		$form = $this->headerForm->create($this->presenter);
		$form->onSuccess[] = [$this, 'saveForm'];

		return $form;
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
		if (!empty($valuesToSave[WebconfigRepository::KEY_HEADER_FILES])) {
			/** @var FileUpload $file */
			foreach ($valuesToSave[WebconfigRepository::KEY_HEADER_FILES] as $file) {
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
			unset($valuesToSave[WebconfigRepository::KEY_HEADER_FILES]);
			$lang = WebconfigRepository::KEY_LANG_FOR_COMMON;	// its going on common parameters, no language need
			foreach ($valuesToSave as $key => $value) {
				$this->webconfigRepository->save($key, $value, $lang);

			}
			$this->flashMessage(HEADER_SETTING_SAVED, "alert-success");
		}
		$this->redirect("default");
	}
}