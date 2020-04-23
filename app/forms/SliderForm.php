<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\SliderPicRepository;
use App\Model\SliderSettingRepository;
use Nette;
use Nette\Application\UI\Form;

class SliderForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/**
	 * @param FormFactory $factory
	 */
	public function __construct(FormFactory $factory) {
		$this->factory = $factory;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();
		$form->addMultiUpload(SliderPicRepository::KEY_SLIDER_FILES_UPLOAD, SLIDER_SETTINGS_PICS)
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "1");

		$form->addCheckbox(SliderSettingRepository::KEY_SLIDER_ON)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", "2");

		$form->addCheckbox(SliderSettingRepository::KEY_SLIDER_SLIDE_SHOW)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", "3");

		$form->addCheckbox(SliderSettingRepository::KEY_SLIDER_CONTROLS)
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", "3");

		$widthSelect = new WebWidthEnum();
		$defaultValue = $widthSelect->arrayKeyValue();
		end($defaultValue);
		$form->addSelect(SliderSettingRepository::KEY_SLIDER_WIDTH, SLIDER_SETTINGS_SLIDER_WITDH, $widthSelect->arrayKeyValue())
			->setAttribute("class", "form-control")
			->setAttribute("tabindex", "4")
			->setDefaultValue(key($defaultValue));

		$form->addText(SliderSettingRepository::KEY_SLIDER_TIMING, SLIDER_SETTINGS_TIMING)
			->setAttribute("class", "form-control")
			->setAttribute("id", "sliderTiming")
			->setAttribute("tabindex", "5");

		$form->addSubmit("confirm", SLIDER_SETTINGS_SAVE_BTN_LABEL)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", "6");

		return $form;
	}
}