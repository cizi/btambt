<?php

namespace App\AdminModule\Presenters;

use App\Controller\FileController;
use App\Enum\UserRoleEnum;
use App\Forms\BlockForm;
use App\Model\BlockRepository;
use App\Model\Entity\BlockContentEntity;
use App\Model\Entity\BlockEntity;
use App\Model\Entity\PicEntity;
use App\Model\LangRepository;
use App\Model\PicRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;

class BlockPresenter extends SignPresenter {

	/** @var PicRepository */
	private $picRepository;

	/** @var BlockForm */
	private $blockForm;

	/**
	 * @param PicRepository $picRepository
	 * @param BlockForm $blockForm
	 */
	public function __construct(PicRepository $picRepository, BlockForm $blockForm) {
		$this->picRepository = $picRepository;
		$this->blockForm = $blockForm;
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
		$lang = $this->langRepository->getCurrentLang($this->session);
		$this->template->blocks = $this->blockRepository->findBlockList($lang);
		$this->template->contactFormId = BlockContentPresenter::CONTACT_FORM_ID_AS_BLOCK;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	public function createComponentBlockForm() {
		$form = $this->blockForm->create();
		$form->onSuccess[] = [$this, 'saveBlock'];

		return $form;
	}

	/**
	 * @param $id
	 * @param array $values
	 */
	public function actionEdit($id, array $values = null) {
		if ($values != null) {
			$this['blockForm']->setDefaults($values);
		}
		if (!empty($id)) {
			$defaults = $this->blockRepository->getEditArray($id);
			$this['blockForm']['id']->setValue($id);
			$this['blockForm']->setDefaults($defaults);
		}

		$this->template->blockId = $id;
		$this->template->blockPics = $this->picRepository->load();
	}

	/**
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function saveBlock($form, $values) {
		$blockEntity = new BlockEntity();
		$blockEntity->hydrate((array)$values);

		$error = false;
		$supportedFileFormats = ["jpg", "png", "doc"];
		$mutation = [];
		$pics = [];
		foreach($values as $key => $value) {
			if ($value instanceof ArrayHash) {	// language mutation
				$blockContentEntity = new BlockContentEntity();
				$blockContentEntity->hydrate((array)$value);
				$blockContentEntity->setLang($key);

				$mutation[] = $blockContentEntity;
			}
			if (is_array($value)) {	// obr�zky
				/** @var FileUpload $file */
				foreach ($value as $file) {
					if ($file->name != "") {
						$fileController = new FileController();
						if ($fileController->upload($file, $supportedFileFormats, $this->getHttpRequest()->getUrl()->getBaseUrl()) == false) {
							$error = true;
							break;
						}

						$blockPic = new PicEntity();
						$blockPic->setPath($fileController->getPathDb());
						$pics[] = $blockPic;
					}
				}
			}
		}

		if ($error) {
			$flashMessage = sprintf(UNSUPPORTED_UPLOAD_FORMAT, implode(",", $supportedFileFormats));
			$this->flashMessage($flashMessage, "alert-danger");
			$this->redirect("edit", null, $values);
		} else {
			if ($this->blockRepository->saveCompleteBlockItem($blockEntity, $mutation, $pics) == false) {
				$this->flashMessage(BLOCK_SETTINGS_ITEM_SAVED_FAILED, "alert-danger");
				$this->redirect("edit", null, $values);
			}
		}
		$this->redirect("default");
	}


	/**
	 * @param int $id
	 */
	public function actionDelete($id) {
		$idDefaultBlock = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_HOME_BLOCK,
			WebconfigRepository::KEY_LANG_FOR_COMMON);
		if ($idDefaultBlock == $id) {
			$this->flashMessage(BLOCK_SETTINGS_ITEM_DEFAULT_BLOCK, "alert-danger");
		} else {
			if ($this->blockRepository->deleteBlockItem($id) == true) {
				$this->flashMessage(BLOCK_SETTINGS_ITEM_DELETED, "alert-success");
			} else {
				$this->flashMessage(BLOCK_SETTINGS_ITEM_DELETED_FAILED, "alert-danger");
			}
		}

		$this->redirect("default");
	}

	/**
	 * @param int $blockId
	 * @param int $picId
	 */
	public function actionDeletePic($blockId, $picId) {
		$this->picRepository->delete($picId);
		$this->redirect("edit", $blockId);
	}
	
}