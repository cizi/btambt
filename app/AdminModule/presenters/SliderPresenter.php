<?php

namespace App\AdminModule\Presenters;

use App\Controller\FileController;
use App\Enum\UserRoleEnum;
use App\Forms\SliderForm;
use App\Model\Entity\SliderPicEntity;
use App\Model\SliderSettingRepository;
use Nette\Application\UI\Form;
use App\Model\SliderPicRepository;
use Nette\Http\FileUpload;

class SliderPresenter extends SignPresenter {

	/** @var SliderForm  */
	private $sliderForm ;

	/** SliderPicRepository  */
	private $sliderPicRepository;

	/** @var SliderSettingRepository */
	private $sliderSettingRepository;

	public function __construct(
		SliderForm $sliderForm,
		SliderPicRepository $sliderPicRepository,
		SliderSettingRepository $sliderSettingRepository
	) {
		$this->sliderForm = $sliderForm;
		$this->sliderPicRepository = $sliderPicRepository;
		$this->sliderSettingRepository = $sliderSettingRepository;
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
		$this->template->sliderPics = $this->sliderPicRepository->findPics();

		$defaults = $this->sliderSettingRepository->load();
		$this['sliderForm']->setDefaults($defaults);
	}

	/**
	 * @param int $id
	 */
	public function actionPicDelete($id) {
		$this->sliderPicRepository->delete($id);
		$this->redirect("default");
	}

	/**
	 * @return Form
	 */
	public function createComponentSliderForm() {
		$form = $this->sliderForm->create();
		$form->onSuccess[] = [$this, 'proceedForm'];

		return $form;
	}

	/**
	 * @param Form $form
	 * @param array $values
	 */
	public function proceedForm($form, $values) {
		$supportedFileFormats = ["png", "jpg", "bmp"];
		$fileError = false;
		foreach($values as $key => $inputValue) {
			if ($key == SliderPicRepository::KEY_SLIDER_FILES_UPLOAD) {		// pics
				foreach ($values[SliderPicRepository::KEY_SLIDER_FILES_UPLOAD] as $value) {
					/** @var FileUpload $file */
					$file = $value;
					if ($file->name != "") {
						$fileController = new FileController();
						if ($fileController->upload($file, $supportedFileFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
							$fileError = true;
							break;
						}
						$sliderPicEntity = new SliderPicEntity();
						$sliderPicEntity->setPath($fileController->getPathDb());
						$this->sliderPicRepository->save($sliderPicEntity);
					}
				}
			} else {		// input data
				$this->sliderSettingRepository->save($key, $inputValue);
			}
		}

		if ($fileError) {
			$flashMessage = sprintf(UNSUPPORTED_UPLOAD_FORMAT, implode(",", $supportedFileFormats));
			$this->flashMessage($flashMessage, "alert-danger");
		} else {
			$this->flashMessage(SLIDER_SETTINGS_SAVE_OK, "alert-success");
		}
		$this->redirect("default");
	}
}