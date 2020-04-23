<?php

namespace App\AdminModule\Presenters;

use App\Controller\MenuController;
use App\Enum\UserRoleEnum;
use App\Model\BlockRepository;
use App\Model\Entity\BlockContentEntity;
use App\Model\Entity\BlockEntity;
use App\Model\LangRepository;
use App\Model\MenuRepository;
use App\Model\WebconfigRepository;

class BlockContentPresenter extends SignPresenter {

	/** @var MenuController */
	private $menuController;

	public function __construct(MenuController $menuController) {
		$this->menuController = $menuController;
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

	/**
	 * ID for contact form as a block into content
	 */
	const CONTACT_FORM_ID_AS_BLOCK = -1;

	public function actionDefault() {
		$lang = $this->langRepository->getCurrentLang($this->session);
		$this->template->topMenuEntities = $this->menuRepository->findItems($lang);
		$this->template->menuController = $this->menuController;
		$this->template->presenter = $this->presenter;
	}

	/**
	 * @param int $id is order of item (is unique)
	 */
	public function actionItemDetail($id) {
		$order = $id;
		$lang = $this->langRepository->getCurrentLang($this->session);
		$menuItemEntity = $this->menuRepository->getMenuEntityByOrder($order, $lang);
		$this->template->menuItem = $menuItemEntity;

		$includedBlocks = $this->blockRepository->findAddedBlock($menuItemEntity->getId(), $lang);
		$this->template->includedBlocks = $includedBlocks;

		$availableBlock = $this->blockRepository->findBlockList($lang);

		$this->template->availableBlocks = $this->filterBlocksFromAdded($includedBlocks, $availableBlock);
	}

	/**
	 * Add block to current link
	 *
	 * @param int $idMenu
	 * @param int $idBlock
	 */
	public function actionAddBlockToLink($idMenu, $idBlock) {
		$this->blockRepository->savePageContent($idMenu, $idBlock);
		$this->redirectToItemDetail($idMenu);
	}

	/**
	 * @param int $idMenu
	 * @param int $idBlock
	 */
	public function actionRemoveBlockFromLink($idMenu,  $idBlock) {
		$this->blockRepository->deletePageContent($idMenu, $idBlock);
		$this->redirectToItemDetail($idMenu);
	}

	/**
	 * @param int $idMenu
	 * @param int $idBlock
	 */
	public function actionMovePageUp($idMenu,  $idBlock) {
		$this->blockRepository->movePageContent($idMenu, $idBlock, true);
		$this->redirectToItemDetail($idMenu);
	}

	public function actionMovePageDown($idMenu,  $idBlock) {
		$this->blockRepository->movePageContent($idMenu, $idBlock, false);
		$this->redirectToItemDetail($idMenu);
	}

	/** redirects to itemDetail
	 * @param $idMenu
	 */
	private function redirectToItemDetail($idMenu) {
		$menuEntity = $this->menuRepository->getMenuEntityById($idMenu);
		$this->redirect("itemDetail", $menuEntity->getOrder());
	}

	/**
	 * @param BlockEntity[] $included
	 * @param BlockEntity[] $available
	 * @return BlockEntity[]
	 */
	private function filterBlocksFromAdded(array $included, array $available) {
		if (count($included) != 0) {
			$availableFilter = [];
			foreach($available as $avail) {
				$found = false;
				foreach($included as $inc) {
					if (!empty($inc) && ($avail->getId() == $inc->getId())) {
						$found = true;
						break;
					}
				}
				if ($found == false) {
					$availableFilter[] = $avail;
				}
			}
			$available = $availableFilter;
		}

		return $available;
	}
}