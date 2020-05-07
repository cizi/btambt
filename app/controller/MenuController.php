<?php

namespace App\Controller;

use App\Enum\UserRoleEnum;
use App\FrontendModule\Presenters\BasePresenter;
use App\Model\Entity\MenuEntity;
use App\Model\MenuRepository;
use Nette\Application\UI\Link;
use Nette\Application\UI\Presenter;
use Nette\Security\User;

class MenuController {

	/** @var MenuRepository  */
	private $menuRepository;

	/** @var User */
	private $user;

	public function __construct(MenuRepository $menuRepository, User $user) {
		$this->menuRepository = $menuRepository;
		$this->user = $user;
	}

	/**
	 * Recursively menu rendering in block content section
	 *
	 * @param Presenter $presenter
	 * @param MenuEntity[] $menuEntities
	 * @return string
	 */
	public function renderMenuItemWithSubItemsInBlockContent(Presenter $presenter, $menuEntities) {
		$tableData = "";
		/** @var MenuEntity $menuEntity */
		$counter = 0;
		foreach ($menuEntities as $menuEntity) {
			$blockForMenuItem = new Link($presenter, "BlockContent:ItemDetail", [$menuEntity->getOrder()]);

			$prefix = "";
			for ($i = 1; $i < $menuEntity->getLevel(); $i++) {
				$prefix .= " - ";
			}

			$tableData .= "
				<tr>
					<td>{$prefix}{$menuEntity->getOrder()}</td>
					<td>{$menuEntity->getTitle()}</td>
					<td>{$menuEntity->getLink()}</td>
					<td>{$menuEntity->getAlt()}</td>
					<td class='alignRight'>";

			$tableData .= "<a href='{$blockForMenuItem}' title='" . BLOCK_CONTENT_SETTINGS_BLOCKS_IN_MENU . "'>
					<span class='glyphicon glyphicon-circle-arrow-right colorGreen'></span></a></td></tr>";

			if ($menuEntity->hasSubItems()) {
				$anotherEntities = $this->menuRepository->findItems($menuEntity->getLang(),
					$menuEntity->getLevel() + 1);
				$tableData .= $this->renderMenuItemWithSubItemsInBlockContent($presenter, $anotherEntities);
			}
			$counter++;
		}

		return $tableData;
	}

	/**
	 * Recursively menu rendering
	 *
	 * @param Presenter $presenter
	 * @param MenuEntity[] $menuEntities
	 * @return string
	 */
	public function renderMenuItemWithSubItems(Presenter $presenter, $menuEntities, $onlyVisible) {
		$tableData = "";
		/** @var MenuEntity $menuEntity */
		$counter = 0;
		foreach ($menuEntities as $menuEntity) {
			$moveOrderUpLink = new Link($presenter, "Menu:MoveUp", [$menuEntity->getId()]);
			$moveOrderDownLink = new Link($presenter, "Menu:MoveDown", [$menuEntity->getId()]);
			$linkAddSubmenu = new Link($presenter, "Menu:Edit", [$menuEntity->getId(), null, $menuEntity->getLevel() + 1]);
			$linkEdit = new Link($presenter, "Menu:Edit", [$menuEntity->getId()]);
			$linkDelete = new Link($presenter, "Menu:Delete", [$menuEntity->getId()]);

			$prefix = "";
			for ($i=1; $i<$menuEntity->getLevel(); $i++) {
				$prefix .= " - ";
			}

			$tableData .= "
				<tr>
					<td>{$prefix}{$menuEntity->getOrder()}</td>
					<td>{$menuEntity->getTitle()}</td>
					<td>{$menuEntity->getLink()}</td>
                    <td>{$menuEntity->getAlt()}</td>";
                    
                    if ($menuEntity->isVisible()) {
                        $tableData .= "<td><label class='checkbox-inline'><input class='activeToggleEvent' user-data={$menuEntity->getId()} type='checkbox' checked data-toggle='toggle' data-height='25' data-width='50'></label></td>";
                    } else {
                        $tableData .= "<td><label class='checkbox-inline'><input class='activeToggleEvent' user-data={$menuEntity->getId()} type='checkbox' data-toggle='toggle' data-height='25' data-width='50'></label></td>";
                    }


            $tableData .= "<td class='alignRight'>";
					if ($counter != 0) {
						// $tableData .= "<a href='{$moveOrderUpLink}' title='" . MENU_SETTINGS_MOVE_ITEM_UP . "'><span class='glyphicon glyphicon-chevron-up colorGrey'></span></a> &nbsp;&nbsp;";
					}
					if (($counter + 1) == count($menuEntities)) {
						// $tableData .= "<div class='menuMoverPlaceholder'></div>";
					} else {
						// $tableData .= "<a href='{$moveOrderDownLink}' title='" . MENU_SETTINGS_MOVE_ITEM_DOWN . "'><span class='glyphicon glyphicon-chevron-down colorGrey'></span></a> &nbsp;&nbsp;";
					}

                    // "<a href='{$linkAddSubmenu}' title='" . MENU_SETTINGS_ADD_SUBITEM . "'><span class='glyphicon glyphicon-plus colorGreen'></span></a> &nbsp;&nbsp;
                    // <a href='#' data-href='{$linkDelete}' class='colorRed' data-toggle='modal' data-target='#confirm-delete' title='" . MENU_SETTINGS_MENU_TOP_DELETE . "'><span class='glyphicon glyphicon-remove'></span></a>
            $tableData .= "<a href='{$linkEdit}' title='" . MENU_SETTINGS_EDIT_ITEM . "'}><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;&nbsp;
					</td>
				</tr>
				";

			if ($menuEntity->hasSubItems()) {
				$anotherEntities = $this->menuRepository->findItems($menuEntity->getLang(), $menuEntity->getLevel() + 1, $onlyVisible);
				$tableData .= $this->renderMenuItemWithSubItems($presenter, $anotherEntities, $onlyVisible);
			}
			$counter++;
		}

		return $tableData;
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function prepareMenuItemsForEdit($id) {
		$menuItemsForEdit = $this->menuRepository->findForEditById($id);
		$level = "";
		$submenu = "";
		$result = [];
		foreach ($menuItemsForEdit as $menuItem) {
			$level = $menuItem->getLevel();
			$submenu = $menuItem->getSubmenu();

			$result[$menuItem->getLang()] = [
				'title' => $menuItem->getTitle(),
				'link' => $menuItem->getLink(),
				'alt' => $menuItem->getAlt(),
				'id' => $menuItem->getId()
			];
		}
		$result['level'] = $level;
		$result['submenu'] = $submenu;

		return $result;
	}

	/**
	 * @param Presenter $presenter
	 * @param string $lang
	 * @param int $level
	 * @param null $id
	 * @return string
	 */
	public function renderMenuInFrontend($presenter, $lang, $level = 1, $id = null) {
		$menu = "";

		if ($id == null) {	// top menu entries
			$itemsInLevel = $this->menuRepository->findItems($lang, $level);
		} else {	// inner entries
			$itemsInLevel = $this->menuRepository->findSubItems($id, $lang, $level);
		}

		foreach ($itemsInLevel as $item) {
			if (
				($this->user->isLoggedIn() && (($item->getLevel() == 1) && ($item->getOrder() == 14))) // pokud je přihlášený tak mu nebudeme zobrazovat odkaz přihlásit
                || (($this->user->isLoggedIn() == false) && ($item->getLevel() == 1) && ($item->getOrder() == 8))	// pokud není přihlášený nebudeme mu zobrazovat odkaz krycího listu
                || (($this->user->isLoggedIn() == false) && ($item->getLevel() == 1) && ($item->getOrder() == 9))	// pokud není přihlášený nebudeme mu zobrazovat odkaz přihlášky vrhu
                || (($this->user->isLoggedIn() == false) && ($item->getLevel() == 1) && ($item->getOrder() == 10))	// pokud není přihlášený nebudeme mu zobrazovat odkaz uživatelského menu
				|| ($this->user->isLoggedIn() && ($item->getLevel() == 1) && ($item->getOrder() == 15)) // nebo je prihlášený tak nechci vidět možnost registrace
				|| ($this->user->isLoggedIn() && ($item->getLevel() == 2) && ($item->getOrder() == 12) // pokud je admin nebo editor tak může do red. systému
						&& ($this->user->isInRole(UserRoleEnum::USER_ROLE_ADMINISTRATOR) == false) && ($this->user->isInRole(UserRoleEnum::USER_EDITOR) == false)
					)
			) {
				continue;
			}

			if ($item->hasSubItems()) {
				$menu .= (($level == 1) ? '<li class="dropdown">' : '<li class="dropdown-submenu">');

				$menu .= '<a href="#" class="dropdown-toggle menuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'. $item->getTitle();
				if ($level == 1) {
					$menu .= '<span class="caret"></span>';
				}
				$menu .= '</a>';

				$menu .= '<ul class="dropdown-menu">';
				$menu .= $this->renderMenuInFrontend($presenter, $lang, $level + 1, $item->getId());
				$menu .= '</ul>';
				$menu .= '</li>';
			} else {
				$link = $presenter->link(BasePresenter::PRESENTER_PREFIX . $item->getLevel() . BasePresenter::LEVEL_ORDER_DELIMITER . $item->getOrder() . ":default", []);
				$menu .= '<li><a href="' . $link . '" class="menuLink">' . $item->getTitle() . '</a></li>';
			}
		}

		/* working part of menu for example
		$menu2 = '<li><a href="#" class="menuLink">O společnoti</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle menuLink" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reference <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<!-- <li role="separator" class="divider"></li> -->
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>

							<li class="dropdown-submenu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
								<ul class="dropdown-menu">
									<li><a href="#">Action</a></li>
									<li class="dropdown-submenu">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
										<ul class="dropdown-menu">
											<li class="dropdown-submenu">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
												<ul class="dropdown-menu">
													<li><a href="#">Action</a></li>
													<li><a href="#">Another action</a></li>
													<li><a href="#">Something else here</a></li>
													<li><a href="#">Separated link</a></li>
													<li><a href="#">One more separated link</a></li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				<li><a href="#" class="menuLink">Kontakt</a></li>';
		*/

		return $menu;
	}
	
}