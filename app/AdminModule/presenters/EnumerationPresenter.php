<?php

namespace App\AdminModule\Presenters;


use App\Enum\UserRoleEnum;
use App\Forms\EnumerationForm;
use App\Forms\EnumerationItemForm;
use App\Model\Entity\EnumerationEntity;
use App\Model\Entity\EnumerationItemEntity;
use App\Model\EnumerationRepository;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class EnumerationPresenter extends SignPresenter {

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/** @var  EnumerationForm */
	private $enumerationForm;

	/** @var  EnumerationItemForm */
	private $enumerationItemForm;

	public function __construct(
		EnumerationRepository $enumerationRepository,
		EnumerationForm $enumerationForm,
		EnumerationItemForm $enumerationItemForm
	) {
		$this->enumerationRepository = $enumerationRepository;
		$this->enumerationForm = $enumerationForm;
		$this->enumerationItemForm = $enumerationItemForm;
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
		$this->template->enums = $this->enumerationRepository->findEnums($this->langRepository->getCurrentLang($this->session));
	}

	public function actionDelete($id) {
		if ($this->enumerationRepository->deleteEnum($id)) {
			$this->flashMessage(ENUM_DELETE_SUCCESS, "alert-success");
		} else {
			$this->flashMessage(ENUM_DELETE_FAIL, "alert-danger");
		}
		$this->redirect("default");
	}

	/**
	 * Smaže položku číselníku
	 * @param int $headerId
	 * @param int $order
	 */
	public function actionDeleteItem($headerId, $order) {
		try {
			$this->enumerationRepository->deleteEnumItem($headerId, $order);
		} catch (\Exception $e) {
			if (strpos($e->getMessage(), 'CONSTRAINT ') !== false) {
				$this->flashMessage(ENUM_DELETE_ITEM_FAIL, "alert-danger");
			}
		}
		$this->redirect("edit", $headerId);
	}

	public function actionEditItem($order, $getEnumHeaderId) {
		$items = $this->enumerationRepository->findEnumItemsByOrder($order);
		$data = [];
		/** @var EnumerationItemEntity $item */
		foreach ($items as $item) {
			$data[$item->getLang()]['item'] = $item->getItem();
			$data[$item->getLang()]["order"] = $item->getOrder();
			$data[$item->getLang()]["enum_header_id"] = $item->getEnumHeaderId();
			$data[$item->getLang()]["id"] = $item->getId();
		}
		if (count($items) == 0) {
			foreach($this->langRepository->findLanguages() as $lang) {
				$data[$lang]["enum_header_id"] = $getEnumHeaderId;
			}
		}
		$this['enumerationItemForm']->setDefaults($data);
	}

	/**
	 * @param int $id
	 */
	public function actionEdit($id) {
		$this->template->enumHeaderId = $id;
		$this->template->langs = $this->langRepository->findLanguages();
		$this->template->enumerationRepository = $this->enumerationRepository;
		if ($id != null) {
			foreach($this->langRepository->findLanguages() as $lang) {
				$desc = $this->enumerationRepository->getEnumDescription($id, $lang);
				$data[$lang] = [ 'description' => $desc->getDescription(), 'enum_header_id' => $desc->getEnumHeaderId(), 'id' => $desc->getId() ];
				$this['enumerationForm']->setDefaults($data);
			}
			$this->template->enumItems = $this->enumerationRepository->findEnumItems($this->langRepository->getCurrentLang($this->session), $id);
		} else {
			$this->template->enumItems = [];
		}
	}

	public function createComponentEnumerationForm() {
		$form = $this->enumerationForm->create($this->langRepository->findLanguages(), $this->link("default"));
		$form->onSuccess[] = [$this, 'saveEdit'];
		return $form;
	}

	public function createComponentEnumerationItemForm() {
		$form = $this->enumerationItemForm->create($this->langRepository->findLanguages(), $this->link("default"));
		$form->onSuccess[] = [$this, 'saveEditItem'];

		return $form;
	}

	public function saveEdit(Form $form, ArrayHash $values) {
		$items = [];
		foreach ($values as $data) {
			if (is_a($data, "Nette\Utils\ArrayHash")) {
				$item = new EnumerationEntity();
				$item->setLang($data['lang']);
				$item->setDescription($data['description']);
				if (isset($data["id"]) && ($data["id"] != "")) {
					$item->setEnumHeaderId($data["id"]);
					$item->setId($data["id"]);
				}
				if (isset($data["enum_header_id"]) && ($data["enum_header_id"] != "")) {
					$item->setEnumHeaderId($data["enum_header_id"]);
				}
				$items[] = $item;
			}
		}
		$enumHeaderId = $this->enumerationRepository->saveEnumeration($items, reset($items)->getEnumHeaderId());
		if ($enumHeaderId) {
			$this->flashMessage(ENUM_EDIT_ITEM_SAVE, "alert-success");
			$this->redirect("edit", $enumHeaderId);
		} else {
			$this->flashMessage(ENUM_EDIT_ITEM_FAIL, "alert-danger");
		}
	}

	/**
	 * Ulo�� polo�ku ��seln�ku
	 * @param Form $form
	 * @param ArrayHash $values
	 */
	public function saveEditItem(Form $form, ArrayHash $values) {
		$items = [];
		foreach ($values as $data) {
			if (is_a($data, "Nette\Utils\ArrayHash")) {
				$item = new EnumerationItemEntity();
				$item->setEnumHeaderId($data["enum_header_id"]);
				$item->setOrder($data["order"]);
				$item->setItem($data["item"]);
				$item->setLang($data["lang"]);
				if (isset($data["id"]) && ($data["id"] != "")) {
					$item->setId($data["id"]);
				}
				$items[] = $item;
			}
		}
		if ($this->enumerationRepository->saveEnumerationItems($items)) {
			$this->flashMessage(ENUM_EDIT_ITEM_SAVE, "alert-success");
			$this->redirect("edit", reset($items)->getEnumHeaderId());
		} else {
			$this->flashMessage(ENUM_EDIT_ITEM_FAIL, "alert-danger");
		}
	}
	
}