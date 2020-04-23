<?php

namespace App\Model;

use App\AdminModule\Presenters\BlockContentPresenter;
use App\Model\Entity\BlockContentEntity;
use App\Model\Entity\BlockEntity;
use App\Model\Entity\PicEntity;
use App\Model\Entity\MenuEntity;
use App\Model\Entity\PageContentEntity;
use App\Model\PicRepository;

class BlockRepository extends BaseRepository {

	/** @const length of text in select for block  */
	const BLOCK_SELECT_LENGTH = 50;

	/** @const for width (name of column in DB as well) */
	const KEY_WIDTH = "width";

	/** @const for font color (name of column in DB as well) */
	const KEY_COLOR = "color";

	/** @const for background color (name of column in DB as well) */
	const KEY_BACKGROUND_COLOR = "background_color";

	/** @const for content lang (name of column in DB as well) */
	const KEY_CONTENT_LANG = "lang";

	/** @const for contetn (name of column in DB as well) */
	const KEY_CONTENT = "content";

	/** @var MenuRepository */
	private $menuRepository;

	/** @var WebconfigRepository */
	private $webconfigRepository;

	/** @var PicRepository */
	private $picRepository;

	/**
	 * @param \Dibi\Connection $connection
	 * @param MenuRepository $menuRepository
	 * @param WebconfigRepository $webconfigRepository
	 * @param \App\Model\PicRepository $picRepository
	 */
	public function	__construct(
		\Dibi\Connection $connection,
		MenuRepository $menuRepository,
		WebconfigRepository $webconfigRepository,
		PicRepository $picRepository
	) {
		parent::__construct($connection);
		$this->menuRepository = $menuRepository;
		$this->webconfigRepository = $webconfigRepository;
		$this->picRepository = $picRepository;
	}

	/**
	 * @param string $lang
	 * @return BlockEntity[]
	 */
	public function findBlockList($lang) {
		$query = ["
			select b.id as id, b.background_color, b.color, b.width, bc.lang, bc.content
			from block as b left join block_content as bc on b.id = bc.block_id where lang = %s",
			$lang
		];

		$result = $this->connection->query($query)->fetchAll();
		$blocks = [];
		foreach ($result as $item) {
			$blockContentEntity = new BlockContentEntity();
			$blockContentEntity->hydrate($item->toArray());

			$blockEntity = new BlockEntity();
			$blockEntity->hydrate($item->toArray());
			$blockEntity->setBlockContent($blockContentEntity);

			$blocks[] = $blockEntity;
		}
		$blocks[] = $this->getContactFormBlock();		// not forget form block which is excluded from DB

		return $blocks;
	}

	/**
	 * @param string $lang
	 * @return BlockEntity[]
	 */
	public function findBlockListAsKeyValue($lang) {
		$query = ["
			select b.id as id, b.background_color, b.color, b.width, bc.lang, bc.content
			from block as b left join block_content as bc on b.id = bc.block_id where lang = %s",
			$lang
		];

		$result = $this->connection->query($query)->fetchAll();
		$blocks = [];
		foreach ($result as $item) {
			$blockContentEntity = new BlockContentEntity();
			$blockContentEntity->hydrate($item->toArray());

			$blockEntity = new BlockEntity();
			$blockEntity->hydrate($item->toArray());
			$blockEntity->setBlockContent($blockContentEntity);

			$blocks[$blockEntity->getId()] = substr($blockEntity->getBlockContent()->getContentText(), 0, self::BLOCK_SELECT_LENGTH);
		}
		$blocks[BlockContentPresenter::CONTACT_FORM_ID_AS_BLOCK] = substr($this->getContactFormBlock()->getBlockContent()->getContentText(), 0, self::BLOCK_SELECT_LENGTH);		// not forget form block which is excluded from DB

		return $blocks;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function deleteBlockItem($id) {
		$this->connection->begin();
		try {
			$query = ["delete from block_content where block_id = %i", $id];
			$this->connection->query($query);
			$query = ["delete from block where id = %i", $id];
			$this->connection->query($query);
		} catch (\Exception $e) {
			$this->connection->rollback();
			return false;
		}

		$this->connection->commit();
		return true;
	}

	/**
	 * @param BlockEntity $blockEntity
	 * @param BlockContentEntity[] $blockContentEntities
	 * @param PicEntity[] $blockPicsEntities
	 * @return bool
	 */
	public function saveCompleteBlockItem(
		BlockEntity $blockEntity,
		array $blockContentEntities,
		array $blockPicsEntities = []
	) {
		$this->connection->begin();
		try {
			$blockId = $this->saveBlockEntity($blockEntity);
			if (empty($blockId)) {
				throw new \Exception("Block ID is missing.");
			}
			$this->saveBlockContents($blockContentEntities, $blockId);
			foreach ($blockPicsEntities as $picEnt) {
				$this->picRepository->save($picEnt);
			}
		} catch (\Exception $e) {
			$this->connection->rollback();
			return false;
		}

		$this->connection->commit();
		return true;
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function getEditArray($id) {
		$query = ["select * from block where id = %i", $id];
		$return = $this->connection->query($query)->fetch()->toArray();

		$query = ["select * from block_content where block_id = %i", $id];
		$result = $this->connection->query($query)->fetchAll();
		foreach($result as $langItem) {
			$blockContentEntity = new BlockContentEntity();
			$blockContentEntity->hydrate($langItem->toArray());
			$return[$blockContentEntity->getLang()] = $blockContentEntity->extract();
		}

		return $return;
	}

	/**
	 * @param int $idMenu
	 * @param int $idBlock
	 *
	 */
	public function savePageContent($idMenu, $idBlock) {
		$query = ["select max(`order`) from page_content where menu_item_id = %i", $idMenu];
		$result = $this->connection->query($query)->fetchSingle();

		$menuItemEntity = $this->menuRepository->getMenuEntityById($idMenu);
		$allLangsMenuItems = $this->menuRepository->findItemsByOrder($menuItemEntity->getOrder());

		$newOrder =(int)$result + 1;
		foreach($allLangsMenuItems as $menuItem) {
			$pageContent = new PageContentEntity();
			$pageContent->setMenuItemId($menuItem->getId());
			$pageContent->setBlockId($idBlock);
			$pageContent->setOrder($newOrder);

			$query = ["insert into page_content", $pageContent->extract()];
			$this->connection->query($query);
		}
	}

	/**
	 * @param int $idMenu
	 * @param int $idBlock
	 */
	public function deletePageContent($idMenu, $idBlock) {
		$menuItemEntity = $this->menuRepository->getMenuEntityById($idMenu);
		$allLangsMenuItems = $this->menuRepository->findItemsByOrder($menuItemEntity->getOrder());

		foreach($allLangsMenuItems as $menuItem) {
			$query = ["
				delete from page_content where menu_item_id = %i and block_id = %i",
				$menuItem->getId(),
				$idBlock
			];
			$this->connection->query($query);
		}
	}

	/**
	 * @param int $idMenu
	 * @param int $idBlock
	 * @param bool $moveUp indicates direction of moving
	 */
	public function movePageContent($idMenu, $idBlock, $moveUp = true) {
		$menuItemEntity = $this->menuRepository->getMenuEntityById($idMenu);
		$allLangsMenuItems = $this->menuRepository->findItemsByOrder($menuItemEntity->getOrder());

		$pageContentEntity = $this->getPageContentEntity($idMenu, $idBlock);
		$originalItemOrder = $pageContentEntity->getOrder();
		if ($originalItemOrder) {
			$this->connection->begin();
			try {
				foreach ($allLangsMenuItems as $menuItem) {
					$newItemOrder = ($moveUp ? $originalItemOrder - 1 : $originalItemOrder + 1);
					$query = [	// first need to change order with lower order to higher
						"update page_content set `order` = %i
							where menu_item_id = %i and `order` = %i",
						$originalItemOrder,
						$menuItem->getId(),
						$newItemOrder		// it is order of lower items
					];
					$this->connection->query($query);

					$query = [	// then need to upgrade current (clicked) item
						"update page_content set `order` = %i
							where menu_item_id = %i and block_id = %i",
						$newItemOrder,
						$menuItem->getId(),
						$idBlock
					];
					$this->connection->query($query);
				}
			} catch (\Exception $e) {
				//dump($e->getMessage());
				$this->connection->rollback();
			}
			$this->connection->commit();
		}
	}

	/**
	 * returns all already included block in link
	 *
	 * @param int $idMenu
	 * @return BlockEntity[]
	 */
	public function findAddedBlock($idMenu, $lang) {
		$blocks = [];
		$query = ["select * from page_content where menu_item_id = %s order by `order`", $idMenu];

		$result = $this->connection->query($query)->fetchAll();
		foreach($result as $item) {
			$blocks[] = $this->getBlockById($lang, $item['block_id']);

		}

		return $blocks;
	}

	/**
	 * Return contact form as a block into content
	 *
	 * @return BlockEntity
	 */
	public function getContactFormBlock() {
		$langCommon = WebconfigRepository::KEY_LANG_FOR_COMMON;

		$contactBlock = new BlockEntity();
		$contactBlock->setBackgroundColor($this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_BACKGROUND_COLOR, $langCommon));
		$contactBlock->setColor($this->webconfigRepository->getByKey(WebconfigRepository::KEY_CONTACT_FORM_COLOR, $langCommon));
		$contactBlock->setId(BlockContentPresenter::CONTACT_FORM_ID_AS_BLOCK);

		$contentEntity = new BlockContentEntity();
		$contentEntity->setContent(BLOCK_CONTENT_SETTINGS_CONTACT_FORM_AS_BLOCK);
		$contentEntity->setLang($langCommon);
		$contactBlock->setBlockContent($contentEntity);

		return $contactBlock;
	}

	/**
	 * Finds all block by link in URL and language
	 *
	 * @param string $link
	 * @param string $lang
	 * @return BlockEntity[]
	 */
	public function findAddedBlockFronted($link, $lang) {
		$menuItem = $this->menuRepository->getMenuItemByLink($link, $lang);
		 return $this->findBlocksByPageContentEntity($menuItem, $lang);
	}

	/**
	 * Returns array with BlockContentEntities by menu entity and lang
	 *
	 * @param MenuEntity $pageContentEntity
	 * @param string $lang
	 * @return BlockEntity[]
	 */
	private function findBlocksByPageContentEntity(MenuEntity $pageContentEntity, $lang) {
		$query = [
			"select * from page_content where menu_item_id = %i order by `order`",
			$pageContentEntity->getId()
		];

		$result = $this->connection->query($query)->fetchAll();
		$blocks = [];
		foreach($result as $item) {
			if ($item->block_id == BlockContentPresenter::CONTACT_FORM_ID_AS_BLOCK) {
				$blocks[]= $this->getContactFormBlock();
			} else {
				$blocks[] = $this->getBlockById($lang, $item->block_id);
			}
		}

		return $blocks;
	}

	/**
	 * @param int $idMenu
	 * @param int $idBlock
	 * @return PageContentEntity
	 */
	private function getPageContentEntity($idMenu, $idBlock) {
		$query = [
			"select * from page_content where menu_item_id = %i and block_id = %i",
			$idMenu,
			$idBlock
		];

		$result = $this->connection->query($query)->fetch();
		$pageContentEntity = new PageContentEntity();
		$pageContentEntity->hydrate($result->toArray());

		return $pageContentEntity;
	}

	/**
	 * @param string $lang
	 * @param int $blockId
	 * @return BlockEntity
	 */
	public function getBlockById($lang, $blockId) {
		if ($blockId == BlockContentPresenter::CONTACT_FORM_ID_AS_BLOCK) {
			$blockEntity = $this->getContactFormBlock();
		} else {
			$query = [
				"
			select b.id as id, b.background_color, b.color, b.width, bc.lang, bc.content
			from block as b left join block_content as bc on b.id = bc.block_id
				where lang = %s and
				b.id = %i",
				$lang,
				$blockId
			];

			$result = $this->connection->query($query)->fetch();
			if ($result) {	// data z DB
				$blockContentEntity = new BlockContentEntity();
				$blockContentEntity->hydrate($result->toArray());

				$blockEntity = new BlockEntity();
				$blockEntity->hydrate($result->toArray());
				$blockEntity->setBlockContent($blockContentEntity);
			} else {	// prázdná entita
				$blockContentEntity = new BlockContentEntity();
				$blockEntity = new BlockEntity();
				$blockEntity->setBlockContent($blockContentEntity);
			}
		}

		return $blockEntity;
	}

	/**
	 * @param BlockContentEntity[] $blockContentMutation
	 * @param int $blockId
	 */
	private function saveBlockContents(array $blockContentMutation, $blockId) {
		$query = ["select * from block_content where block_id = %i", $blockId];
		$result = $this->connection->query($query)->fetchAll();
		/** @var BlockContentEntity $blockContentEntity */
		foreach ($blockContentMutation as $blockContentEntity) {
			$blockContentEntity->setBlockId($blockId);
			if ($result) {
				$query = ["update block_content set " . self::KEY_CONTENT . " = %s
						  where ".self::KEY_CONTENT_LANG." = %s
						  	and block_id = %i",
						$blockContentEntity->getContent(),
						$blockContentEntity->getLang(),
						$blockId
				];
			} else {
				$query = ["insert into block_content", $blockContentEntity->extract()];
			}
			$this->connection->query($query);
		}
	}

	/**
	 * @param BlockEntity $blockEntity
	 * @return int
	 */
	private function saveBlockEntity(BlockEntity $blockEntity) {
		if ($blockEntity->getId() == null) {
			$query = ["insert into block", $blockEntity->extract()];
		} else {
			$query = ["
				update block set ". self::KEY_BACKGROUND_COLOR ." = %s,
				  ". self::KEY_COLOR . " = %s,
				  " . self::KEY_WIDTH . " = %s
				where id = %i",
				$blockEntity->getBackgroundColor(),
				$blockEntity->getColor(),
				$blockEntity->getWidth(),
				$blockEntity->getId()
			];
		}
		$this->connection->query($query);

		return ($blockEntity->getId() == null ? $this->connection->getInsertId() : $blockEntity->getId());
	}
}