<?php

namespace App\Model;

use App\Model\Entity\MenuEntity;

class MenuRepository extends BaseRepository {

	/** @const for temporary value during order changes in menu */
	const TEMP_INTEGER_VALUE = 2147483647;

	/**
	 * @param string $lang
	 * @param int $level
     * @param bool $onlyVisible
	 * @return MenuEntity[]
	 */
	public function findItems($lang, $level = 1, $onlyVisible = true) {
        $items = [];      
        $query = [
            "select * from menu_item as mi where level = %i and lang = %s order by `order`",
			$level,
            $lang,
        ];

        if ($onlyVisible) {
                $query = [
                    "select * from menu_item as mi where level = %i and lang = %s and visible = 1 order by `order`",
                    $level,
                    $lang,
                ];
        }

		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$menuItem = new MenuEntity();
			$menuItem->hydrate($item->toArray());
			$menuItem->setHasSubItems($this->hasSubItems($lang, $level, $menuItem->getOrder()));
			$items[] = $menuItem;
		}

		return $items;
	}

	/**
	 * Finds all links in all languages
	 */
	public function findAllItems() {
		$items = [];
		$query = ["select * from menu_item order by `order`"];

		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$menuItem = new MenuEntity();
			$menuItem->hydrate($item->toArray());
			$items[] = $menuItem;
		}

		return $items;
	}

	/**
	 * @param string $link
	 * @param string $lang
	 * @return MenuEntity
	 */
	public function getMenuItemByLink($link, $lang) {
		$query = ["select * from menu_item as mi
			where link = %s
			and lang = %s
			order by `order`",
			$link,
			$lang
		];

		$menuItem = new MenuEntity();
		$result = $this->connection->query($query)->fetch();
		if ($result) {
			$menuItem->hydrate($result->toArray());
		}

		return $menuItem;
	}

	/**
	 * @param string $lang
	 * @param int $order
	 * @return MenuEntity[]
	 */
	public function findItemsByOrder($order) {
		$items = [];
		$query = ["select * from menu_item as mi
			where `order` = %i
			order by `order`",
			$order
		];

		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$menuItem = new MenuEntity();
			$menuItem->hydrate($item->toArray());
			$items[] = $menuItem;
		}

		return $items;
	}

	/**
	 * @param int $menuId
	 * @param string $lang
	 * @param int $level
     * @param bool $onlyVisible
	 * @return bool
	 */
	public function findSubItems($menuId, $lang, $level, $onlyVisible = true) {
		$menuItem = $this->getMenuEntityById($menuId);
		$itemsByOrder = $this->findItemsByOrder($menuItem->getOrder());
		$menuIds = [];
		foreach($itemsByOrder as $itemOrder) {
			$menuIds[] = $itemOrder->getId();
		}

		$items = [];
		$query = [
            "select * from menu_item as mi where submenu in %in and `level` = %i and lang = %s order by `order`",
			$menuIds,
			$level,
			$lang
        ];
        if ($onlyVisible) {
            $query = [
                "select * from menu_item as mi where submenu in %in and `level` = %i and lang = %s and visible = 1 order by `order`",
                $menuIds,
                $level,
                $lang
            ];
        }

		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$menuItem = new MenuEntity();
			$menuItem->hydrate($item->toArray());
			$menuItem->setHasSubItems($this->hasSubItems($lang, $level, $menuItem->getOrder()));
			$items[] = $menuItem;
		}

		return $items;
	}

	/**
	 * @param $lang
	 * @param $level
	 * @param $order
	 * @return bool
	 */
	public function hasSubItems($lang, $level, $order) {
		$itemsByOrder = $this->findItemsByOrder($order);
		$menuIds = [];
		foreach($itemsByOrder as $itemOrder) {
			$menuIds[] = $itemOrder->getId();
		}

		$query = ["select * from menu_item as mi
			where submenu in %in
			and `level` = %i
			and lang = %s
			order by `order`",
			$menuIds,
			$level+1,
			$lang
		];

		return (count($this->connection->query($query)->fetchAll()) != 0);
	}

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function delete($id) {
		$this->connection->begin();
		try {
			$idToDelete = [];
			// delete both top menu items
			$query = ["select `order` from menu_item where id = %i", $id];
			$result = $this->connection->query($query)->fetch();
			$topOrder = $result->toArray()['order'];

			$query = ["select id from menu_item where `order` = %i", $topOrder];
			foreach($this->connection->query($query)->fetchAll() as $item) {
				$idToDelete[] = $item->toArray()['id'];
			}

			// checking another id to delete
			$query = ["select id from menu_item where submenu in %in", $idToDelete];
			do {
				$nextId = [];
				foreach ($this->connection->query($query)->fetchAll() as $item) {
					$idToDelete[] = $item->toArray()['id'];
					$nextId[] = $item->toArray()['id'];
				}
				$query = ["select id from menu_item where submenu in %in", $nextId];
			} while (count($this->connection->query($query)->fetchAll()));

			$query = ["delete from menu_item where id in %in or submenu in %in", $idToDelete, $idToDelete];
			$this->connection->query($query);
		} catch (\Exception $e) {
			$this->connection->rollback();
			return false;
		}

		$this->connection->commit();
		return true;
	}

	/**
	 * @param MenuEntity[] $langItems
	 */
	public function saveItem(array $langItems) {
		$this->connection->begin();

		$orderValue = $this->connection->query("select ifnull(MAX(`order`),0) + 1 from menu_item");
		foreach ($langItems as $menuItem) {
			$menuItemId = $menuItem->getId();
			if (empty($menuItemId)) {// insert
				if ($this->insertNewMenuItem($menuItem, $orderValue) == false) {
					$this->connection->rollback();
					return false;
				}
			} else {	// update
				$this->updateMenuItem($menuItem);
			}
		}

		$this->connection->commit();
		return true;
	}

	/**
	 * @param int $id
	 * @return MenuEntity[]
	 */
	public function findForEditById($id) {
		$query = ["select `order` from menu_item where id = %i", $id];
		$result = $this->connection->query($query)->fetch();
		$menuEntity = new MenuEntity();
		$menuEntity->hydrate($result->toArray());

		$query = ["select * from menu_item where `order` = %i", $menuEntity->getOrder()];
		$result = $this->connection->query($query)->fetchAll();
		$menuEntities = [];
		foreach ($result as $item) {
			$menuEnt = new MenuEntity();
			$menuEnt->hydrate($item->toArray());
			$menuEntities[] = $menuEnt;
		}

		return $menuEntities;
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public function orderEntryUp($id) {
		$menuEntity = $this->getMenuEntityById($id);
		$query = ["select * from menu_item where level = %i order by `order`", $menuEntity->getLevel()];
		$result = $this->connection->query($query)->fetchAll();

		// next record in order
		$query = ["select * from menu_item where `order` = %i", $menuEntity->getOrder()-1];
		$previousItemResult = $this->connection->query($query)->fetch();
		$lastItem = new MenuEntity();
		$lastItem->hydrate($previousItemResult->toArray());

		$this->connection->begin();
		try {
			foreach ($result as $item) {
				$menuEntityItem = new MenuEntity();
				$menuEntityItem->hydrate($item->toArray());
				if ($menuEntity->getId() == $menuEntityItem->getId()) {
					$query = ["update menu_item set `order` = %i where `order` = %i", self::TEMP_INTEGER_VALUE, $menuEntity->getOrder()];
					$this->connection->query($query);

					$query = ["update menu_item set `order` = %i where `order` = %i", $menuEntity->getOrder() ,$lastItem->getOrder()];
					$this->connection->query($query);

					$query = ["update menu_item set `order` = %i where `order` = %i", $lastItem->getOrder(), self::TEMP_INTEGER_VALUE];
					$this->connection->query($query);
				}
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
	 * @return bool
	 */
	public function orderEntryDown($id) {
		$menuEntity = $this->getMenuEntityById($id);
		$query = ["select * from menu_item where level = %i order by `order`", $menuEntity->getLevel()];
		$result = $this->connection->query($query)->fetchAll();

		// next record in order
		$query = ["select * from menu_item where `order` = %i", $menuEntity->getOrder()+1];
		$nextItemResult = $this->connection->query($query)->fetch();
		$nextItem = new MenuEntity();
		$nextItem->hydrate($nextItemResult->toArray());

		$this->connection->begin();
		try {
			foreach($result as $item) {
				$menuEntityItem = new MenuEntity();
				$menuEntityItem->hydrate($item->toArray());

				if ($menuEntity->getId() == $menuEntityItem->getId()) {
					$query = ["update menu_item set `order` = %i where `order` = %i", self::TEMP_INTEGER_VALUE, $nextItem->getOrder()];
					$this->connection->query($query);

					$query = ["update menu_item set `order` = %i where `order` = %i", $nextItem->getOrder() ,$menuEntity->getOrder()];
					$this->connection->query($query);

					$query = ["update menu_item set `order` = %i where `order` = %i", $menuEntity->getOrder(), self::TEMP_INTEGER_VALUE];
					$this->connection->query($query);
				}
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
	 * @return MenuEntity
	 */
	public function getMenuEntityById($id) {
		$query = ["select * from menu_item where id = %i", $id];
		$result = $this->connection->query($query)->fetch();
		$menuEntity = new MenuEntity();
		$menuEntity->hydrate($result->toArray());

		return $menuEntity;
	}

	/**
	 * @param int $order
	 * @param string $lang
	 * @return MenuEntity
	 */
	public function getMenuEntityByOrder($order, $lang) {
		$query = ["select * from menu_item where `order` = %i and lang = %s", $order, $lang];
		$result = $this->connection->query($query)->fetch();
		$menuEntity = new MenuEntity();
		$menuEntity->hydrate($result->toArray());

		return $menuEntity;
	}

	/**
	 * @param MenuEntity $menuItem
	 * @param int $level
	 * @param int $suborder
	 * @param int $submenu
	 *
	 * @return bool
	 */
	private function insertNewMenuItem(MenuEntity $menuItem, $order) {
		try {
			$query = ["select * from menu_item where lang = %s and link = %s", $menuItem->getLang(), $menuItem->getLink()];
			$result = $this->connection->query($query)->fetchAll();
			if ($result) {
				throw new \Dibi\Exception("Duplicitn� unik�tn� kl��");
			}
			$query = [
				"
				insert into menu_item values (null,%s, %s, %s, %s, %i, %i, %i)",
				$menuItem->getLang(),
				$menuItem->getLink(),
				$menuItem->getTitle(),
				$menuItem->getAlt(),
				$menuItem->getLevel(),
				$order,
				$menuItem->getSubmenu()
			];
			$this->connection->query($query);
			return true;
		} catch (\Dibi\Exception $e) {
			return false;
		}
	}

	/**
	 * @param MenuEntity $menuItemEntity
	 */
	private function updateMenuItem(MenuEntity $menuItemEntity) {
		$query = ["update menu_item set
				link = %s,
				title = %s,
				alt = %s
		 	where id = %i",
			$menuItemEntity->getLink(),
			$menuItemEntity->getTitle(),
			$menuItemEntity->getAlt(),
			$menuItemEntity->getId()
		];

		$this->connection->query($query);
    }
    
    /**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function setMenuActive($id) {
        $menuEntity = $this->getMenuEntityById($id);
        $query = ["update menu_item set visible = 1 where `order` = %i", $menuEntity->getOrder()];
		return $this->connection->query($query);
	}

	/**
	 * @param int $id
	 * @return \Dibi\Result|int
	 */
	public function setMenuInactive($id) {
        $menuEntity = $this->getMenuEntityById($id);
		$query = ["update menu_item set visible = 0 where `order` = %i", $menuEntity->getOrder()];
		return $this->connection->query($query);
	}
}