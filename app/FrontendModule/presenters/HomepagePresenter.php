<?php

namespace App\FrontendModule\Presenters;

use App\Controller\FileController;
use App\Controller\MenuController;
use App\Model\Entity\BlockContentEntity;
use App\Model\Entity\MenuEntity;
use App\Model\SliderPicRepository;
use App\Model\WebconfigRepository;

class HomepagePresenter extends BasePresenter {

	/** var SliderPicRepository */
	private $sliderPicRepository;

	/** @var MenuController */
	private $menuController;

	/** @var FileController */
	private $fileController;

	public function __construct(
		SliderPicRepository $sliderPicRepository,
		MenuController $menuController,
		FileController $fileController
	) {
		$this->sliderPicRepository = $sliderPicRepository;
		$this->menuController = $menuController;
		$this->fileController = $fileController;
	}

	/**
	 * @param string $lang
	 * @param string $id
	 */
	public function renderDefault($lang, $id) {
		if (empty($lang)) {
			$lang = $this->langRepository->getCurrentLang($this->session);
			$this->redirect("default", [ 'lang' => $lang, 'id' => $id]);
		}

		$userBlocks = [];
		$availableLangs = $this->langRepository->findLanguages();
		// what if link will have the same shortcut like language
		if (isset($availableLangs[$lang]) && ($lang != $this->langRepository->getCurrentLang($this->session))) {
			$this->langRepository->switchToLanguage($this->session, $lang);
			$this->redirect("default", [ 'lang' => $lang, 'id' => $id ]);
		} else {
			if ((empty($id) || ($id == "")) && !empty($lang) && (!isset($availableLangs[$lang]))) {
				$id = $lang;
			}
			if (empty($id) || ($id == "")) {    // try to find default
				$userBlocks[] = $this->getDefaultBlock();
			} else {
				$userBlocks = $this->blockRepository->findAddedBlockFronted($id,
					$this->langRepository->getCurrentLang($this->session));
				if (empty($userBlocks)) {
					$userBlocks[] = $this->getDefaultBlock();
				}
			}
			// because of sitemap.xml
			$allWebLinks = $this->menuRepository->findAllItems();
			$this->template->webAvailebleLangs = $availableLangs;
			$this->template->availableLinks = $allWebLinks;
			/** @var MenuEntity $menuLink */
			foreach($allWebLinks as $menuLink) {
				if ($menuLink->getLink() == $id) {
					$this->template->currentLink = $menuLink;
				}
}			}

			$this->template->userBlocks = $userBlocks;
	}

	/**
	 * returns default block
	 *
	 * @return BlockContentEntity|\App\Model\Entity\BlockEntity
	 */
	private function getDefaultBlock() {
		$id = $this->webconfigRepository->getByKey(WebconfigRepository::KEY_WEB_HOME_BLOCK,
			WebconfigRepository::KEY_LANG_FOR_COMMON);

		$blockContentEntity = new BlockContentEntity();
		if (!empty($id)) {
			$blockContentEntity = $this->blockRepository->getBlockById($this->langRepository->getCurrentLang($this->session), $id);
		}

		return $blockContentEntity;
	}
}
