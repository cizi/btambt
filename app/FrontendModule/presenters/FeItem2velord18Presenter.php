<?php

namespace App\FrontendModule\Presenters;

class FeItem2velord18Presenter extends BasePresenter {

	/**
	 * Odhlášení
	 */
	public function actionDefault() {
		$this->getUser()->logout();
		$this->flashMessage(ADMIN_LOGIN_UNLOGGED, "alert-success");
		$this->redirect('Homepage:default');
	}
}