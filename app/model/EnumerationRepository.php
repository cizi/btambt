<?php

namespace App\Model;

use App\Model\Entity\EnumerationEntity;
use App\Model\Entity\EnumerationItemEntity;

class EnumerationRepository extends BaseRepository {

	/** @const string nevybran� polo�ka */
	const NOT_SELECTED = "-";

	/** @var int čísla číselní napříč aplikací */
	const POHLAVI = 8,
	PLEMENO = 7,
	BARVA = 4,
	SRST = 11,
	VARLATA = 12,
	SKUS = 10,
	CHOVNOST = 5;

	/**
	 * @param int $id
	 */
	public function deleteEnum($id) {
		$return = true;
		$this->connection->begin();
		try {
			$query = ["delete from enum_item where enum_header_id = %i", $id];
			$this->connection->query($query);

			$query = ["delete from enum_translation where enum_header_id = %i", $id];
			$this->connection->query($query);

			$query = ["delete from enum_header where id = %i", $id];
			$this->connection->query($query);
		} catch (\Exception $e) {
			$this->connection->rollback();
			$return = false;
		}
		$this->connection->commit();

		return $return;
	}

	/**
	 * @param string $lang
	 * @return array
	 */
	public function findEnums($lang) {
		$return = [];
		$query = ["select et.lang, et.description, et.enum_header_id, eh.id from enum_header as eh
				left join enum_translation as et on eh.id = et.enum_header_id
				where lang = %s",
			$lang];

		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$enum = new EnumerationEntity();
			$enum->hydrate($item->toArray());
			$enum->setItems($this->findEnumItems($lang, $enum->getEnumHeaderId()));
			$return[] = $enum;
		}

		return $return;
	}

	/**
	 * @param int $id
	 * @param string $lang
	 * @return EnumerationEntity
	 */
	public function getEnumDescription($id, $lang) {
		$query = ["select et.lang, et.description, et.enum_header_id, et.id from enum_translation as et
				where et.enum_header_id = %i and lang = %s",
			$id,
			$lang
		];

		$result = $this->connection->query($query)->fetch();
		if ($result) {
			$enum = new EnumerationEntity();
			$enum->hydrate($result->toArray());

			return $enum;
		}
	}

	/**
	 * @param string $lang
	 * @param int $enumHeaderId
	 * @return array
	 */
	public function findEnumItems($lang, $enumHeaderId) {
		$return = [];
		$query = ["select * from enum_item where enum_header_id = %i and lang = %s", $enumHeaderId, $lang];
		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$enumItem = new EnumerationItemEntity();
			$enumItem->hydrate($item->toArray());
			$return[] = $enumItem;
		}

		return $return;
	}

	/**
	 * @param string $lang
	 * @param int $enumHeaderId
	 * @return array
	 */
	public function findEnumItemsForSelectIgnoreEmpty($lang, $enumHeaderId) {
		$return = [];
		$query = ["select * from enum_item where enum_header_id = %i and lang = %s", $enumHeaderId, $lang];
		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$enumItem = new EnumerationItemEntity();
			$enumItem->hydrate($item->toArray());
			if ($enumItem->getItem() != self::NOT_SELECTED) {
				$return[$enumItem->getOrder()] = $enumItem->getItem();
			}
		}

		return $return;
	}

	/**
	 * @param string $lang
	 * @param int $enumHeaderId
	 * @return array
	 */
	public function findEnumItemsForSelect($lang, $enumHeaderId) {
		$return = [];
		$query = ["select * from enum_item where enum_header_id = %i and lang = %s", $enumHeaderId, $lang];
		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$enumItem = new EnumerationItemEntity();
			$enumItem->hydrate($item->toArray());
			if ($enumItem->getItem() == self::NOT_SELECTED) {
				$return[0] = $enumItem->getItem();
			} else {
				$return[$enumItem->getOrder()] = $enumItem->getItem();
			}
		}

		return $return;
	}

	/**
	 * @param string $lang
	 * @param int $enumHeaderId
	 * @return array
	 */
	public function findEnumItemsForSelectWithEmpty($lang, $enumHeaderId) {
		$return[0] = self::NOT_SELECTED;
		$items = $this->findEnumItems($lang, $enumHeaderId);
		/** @var EnumerationItemEntity $enumItem */
		foreach($items as $enumItem) {
			$return[$enumItem->getOrder()] = $enumItem->getItem();
		}

		return $return;
	}

	/**
	 * @param string $lang
	 * @param int $enumHeaderId
	 * @return array
	 */
	public function findEnumItemsForSelectWithoutEmpty($lang, $enumHeaderId) {
		$return = [];
		$items = $this->findEnumItems($lang, $enumHeaderId);
		/** @var EnumerationItemEntity $enumItem */
		foreach($items as $enumItem) {
			$return[$enumItem->getOrder()] = $enumItem->getItem();
		}

		return $return;
	}

	/**
	 * @param string $lang
	 * @param int $enumHeaderId
	 * @return array
	 */
	public function findEnumItemsByOrder($order) {
		$return = [];
		$query = ["select * from enum_item where `order` = %i", $order];
		$result = $this->connection->query($query)->fetchAll();
		foreach ($result as $item) {
			$enumItem = new EnumerationItemEntity();
			$enumItem->hydrate($item->toArray());
			$return[] = $enumItem;
		}

		return $return;
	}

	/**
	 * Sma�e hodnotu ��seln�ku
	 * @param int $headerId
	 * @param int $order
	 */
	public function deleteEnumItem($headerId, $order) {
		$query = ["delete from enum_item where `order` = %i and enum_header_id = %i", $order, $headerId];
		$this->connection->query($query);
	}

	/**
	 * @param array $items
	 * @return bool
	 */
	public function saveEnumerationItems(array $items) {
		$result = true;
		$this->connection->begin();
		try {
			$query = "select max(`order`) from enum_item";
			$newOrder = $this->connection->query($query)->fetchSingle() + 1;
			/** @var EnumerationItemEntity $item */
			foreach($items as $item) {
				if ($item->getOrder() == null) {
					$item->setOrder($newOrder);
					$query = ["insert into enum_item", $item->extract()];
				} else {
					$query = ["update enum_item set item = %s where id = %i", $item->getItem(), $item->getId()];
				}
				$this->connection->query($query);
			}
		} catch (\Exception $e) {
			$this->connection->rollback();
			$result = false;
		}
		$this->connection->commit();

		return $result;
	}

	public function saveEnumeration(array $items, $enumHeaderId) {
		$this->connection->begin();
		try {
			if ($enumHeaderId == null) {
				$query = "insert into enum_header (description) values ('USER ENUM')";
				$this->connection->query($query);
				$enumHeaderId = $this->connection->getInsertId();
			}
			/** @var EnumerationEntity $item */
			foreach($items as $item) {
				if ($item->getId() == null) {
					$item->setEnumHeaderId($enumHeaderId);
					$query = ["insert into enum_translation", $item->extract()];
				} else {
					$query = ["update enum_translation set description = %s where id = %i", $item->getDescription(), $item->getId()];
				}
				$this->connection->query($query);
			}
		} catch (\Exception $e) {
			$this->connection->rollback();
		}
		$this->connection->commit();

		return $enumHeaderId;
	}

	/**
	 * @param string $lang
	 * @param int $enumHeaderId
	 * @return array
	 */
	public function findEnumItemByOrder($lang, $order) {
		$result  = "";
		if (!empty($order)) {
			$query = ["select * from enum_item where lang = %s and `order` = %i", $lang, $order];
			$result = $this->connection->query($query)->fetch();
			if ($result) {
				$enumItem = new EnumerationItemEntity();
				$enumItem->hydrate($result->toArray());
				$result = $enumItem->getItem();
			}
		}

		return $result;
	}
}