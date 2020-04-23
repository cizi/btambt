<?php

namespace App\Forms;

use App\Enum\WebWidthEnum;
use App\Model\BlockRepository;
use App\Model\LangRepository;
use Nette;
use Nette\Application\UI\Form;

class BlockForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var LangRepository */
	private $langRepository;

	/**
	 * @param FormFactory $factory
	 * @param LangRepository $langRepository
	 */
	public function __construct(FormFactory $factory, LangRepository $langRepository) {
		$this->factory = $factory;
		$this->langRepository = $langRepository;
	}

	/**
	 * @return Form
	 */
	public function create() {
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$widthSelect = new WebWidthEnum();
		$defaultValue = $widthSelect->arrayKeyValue();
		end($defaultValue);
		$form->addSelect(BlockRepository::KEY_WIDTH, BLOCK_SETTING_WIDTH, $widthSelect->arrayKeyValue())
			->setAttribute("class", "form-control menuItem")
			->setAttribute("tabindex", "1")
			->setDefaultValue(key($defaultValue));

		$form->addText(BlockRepository::KEY_COLOR, BLOCK_SETTING_ITEM_CONTENT_COLOR)
			->setAttribute("id", "footerBackgroundColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", "2");

		$form->addText(BlockRepository::KEY_BACKGROUND_COLOR, BLOCK_SETTING_ITEM_CONTENT_BG_COLOR)
			->setAttribute("id", "footerColor")
			->setAttribute("class", "form-control minicolors-input")
			->setAttribute("tabindex", "3");

		$languages = $this->langRepository->findLanguages();
		$i = 4;
		foreach ($languages as $lang) {
			$container = $form->addContainer($lang);

			$container->addText(BlockRepository::KEY_CONTENT_LANG)
				->setAttribute("class", "form-control menuItem langDivider")
				->setAttribute("tabindex", "-1")
				->setAttribute("readonly", "readonly")
				->setValue($lang);

			$container->addTextArea(BlockRepository::KEY_CONTENT, BLOCK_SETTING_ITEM_CONTENT_LABEL)
				->setAttribute("class", "form-control menuItem mceBlockContent")
				->setAttribute("placeholder", BLOCK_SETTING_ITEM_CONTENT_LABEL)
				->setAttribute("tabindex", $i);
			$i++;
		}

		$form->addMultiUpload("pics")
			->setAttribute("class", "form-control menuItem")
			->setAttribute("tabindex", $i+1);

		$form->addHidden("id");

		$form->addSubmit("confirm", BLOCK_SETTING_ITEM_CONTENT_CONFIRM)
			->setAttribute("class","btn btn-primary menuItem alignRight")
			->setAttribute("tabindex", $i+2);

		return $form;
	}

}