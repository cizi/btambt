<?php

namespace App\AdminModule\Presenters;

use App\Controller\FileController;
use App\Enum\UserRoleEnum;
use App\Forms\CounterForm;
use App\Model\LangRepository;
use App\Model\WebconfigRepository;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

class CounterPresenter extends SignPresenter {

	/** @var CounterForm */
	private $counterForm;


	/**
	 * @param CounterForm $counterForm
	 */
	public function __construct(CounterForm $counterForm) {
		$this->counterForm = $counterForm;
	}

	/**
	 * Pokud nejsem admin tak tady nemám co dělat
	 */
	public function startup() {
		parent::startup();
		if (($this->getUser()->getRoles()[0] == UserRoleEnum::USER_EDITOR)) {
            $this->flashMessage(DOG_TABLE_DOG_ACTION_NOT_ALLOWED, "alert-danger");
            $this->redirect("Dashboard:Default");
		}
	}

    /**
     * 
     */
	public function actionDefault() {
		$defaultsCounters = $this->webconfigRepository->loadCounters(WebconfigRepository::KEY_LANG_FOR_COMMON);
		$this['counterForm']->setDefaults($defaultsCounters);
	}

	/**
	 * @return Form
	 */
	public function createComponentCounterForm() {
		$form = $this->counterForm->create($this->presenter);
		$form->onSuccess[] = [$this, 'saveValue'];

		return $form;
	}

	/**
	 * @param $form
	 * @param $values
	 */
	public function saveValue($form, $values) {
        try {
            foreach ($values as $key => $value) {
                $this->webconfigRepository->save($key, $value, WebconfigRepository::KEY_LANG_FOR_COMMON);
            }
            $this->flashMessage(WEBCONFIG_WEB_SAVE_SUCCESS, "alert-success");
        } catch (\Exception $e) {
            $this->flashMessage(BLOCK_SETTINGS_ITEM_SAVED_FAILED, "alert-danger");
        }

		$this->redirect("default");
    }
    
}