<?php

namespace App\AdminModule\Presenters;

use App\FrontendModule\Presenters\BasePresenter;
use App\Model\Entity\MenuEntity;
use App\Model\MenuRepository;

class WebPublicUtilsPresenter extends BasePresenter {

	/**
	 * generates sitemap.xml in the root of the web
	 *
	 * @param string|false $isRobot
	 * for robot: http://localhost/tynim/www/admin/web-public-utils/generate-site-map/true
	 */
	public function actionGenerateSiteMap($id = "false") {
		$allWebLinks = $this->menuRepository->findAllItems();
		if (count($allWebLinks)) {
			$fileContent = [];
			$fileContent[] = '<?xml version="1.0" encoding="UTF-8"?>';
			$fileContent[] = '<urlset xmlns:xhtml="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
			$lastOrder = "";
			$base = $this->getHttpRequest()->getUrl()->getBaseUrl();
			/** @var MenuEntity $link */
			foreach($allWebLinks as $link) {
				if ($lastOrder != $link->getOrder()) {
					if ($lastOrder != "") {		// if previous order is not empty it means something were already generated
						$fileContent[] = '</url>';
					}
					$fileContent[] = '<url>';
					$fileContent[] = '<loc>' . $base . $link->getLang() . '/' . $link->getLink() . '</loc>';
				}
				$fileContent[] = '<xhtml:link rel="alternate" hreflang="' . $link->getLang() . '" href="' . $base . $link->getLang() . '/' . $link->getLink() . '" />';
				$lastOrder = $link->getOrder();
			}
			$fileContent[] = '</url></urlset>';
			file_put_contents(SITEMAP_PATH . 'sitemap.xml', $fileContent);
			if ($id == "false") {
				$this->flashMessage(USER_EDIT_SITEMAP_GENERATION_DONE, "alert-success");
				$this->redirect("Webconfig:default");
			} else {
				$this->terminate();
			}
		}
	}
}