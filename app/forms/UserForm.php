<?php

namespace App\Forms;

use App\Enum\StateEnum;
use App\Enum\UserRoleEnum;
use App\Model\EnumerationRepository;
use Nette;
use Nette\Application\UI\Form;

class UserForm {

    use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var EnumerationRepository */
	private $enumerationRepository;

	/**
	 * @param FormFactory $factory
	 * @param EnumerationRepository $enumerationRepository
	 */
	public function __construct(FormFactory $factory, EnumerationRepository $enumerationRepository) {
		$this->factory = $factory;
		$this->enumerationRepository = $enumerationRepository;
	}

	/**
	 * @return Form
	 */
	public function create($linkBack, $langCurrent) {
		$form = $this->factory->create();
		$form->getElementPrototype()->addAttributes(["onsubmit" => "return requiredFields();"]);

		$form->addText("email", USER_EDIT_EMAIL_LABEL)
			->setAttribute("type","email")
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_EMAIL_LABEL)
			->setAttribute("validation", USER_EDIT_EMAIL_VALIDATION)
			->setAttribute("tabindex", "1");

		$form->addPassword("password", USER_EDIT_PASS_LABEL)
			->setAttribute("type","password")
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_PASS_LABEL)
			->setAttribute("validation", USER_EDIT_PASS_REQ)
			->setAttribute("tabindex", "2");

		$form->addPassword("passwordConfirm", USER_EDIT_PASS_AGAIN_LABEL)
			->setAttribute("type","password")
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_PASS_AGAIN_LABEL)
			->setAttribute("validation", USER_EDIT_PASS_AGAIN_REQ)
			->setAttribute("tabindex", "3");

		$userRole = new UserRoleEnum();
		$form->addSelect("role", USER_EDIT_ROLE_LABEL, $userRole->translatedForSelect())
			->setAttribute("class", "form-control")
			->setAttribute("readonly", "readonly")
			->setAttribute("tabindex", "4");;

		$form->addCheckbox('active')
			->setAttribute("data-toggle", "toggle")
			->setAttribute("data-height", "25")
			->setAttribute("data-width", "50")
			->setDefaultValue("checked")
			->setAttribute("tabindex", "5");

		$form->addText("title_before", USER_EDIT_TITLE_BEFORE_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_TITLE_BEFORE_LABEL)
			->setAttribute("tabindex", "6");

		$form->addText("name", USER_EDIT_NAME_LABEL)
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_NAME_LABEL)
			->setAttribute("validation", USER_EDIT_NAME_LABEL_VALIDATION)
			->setAttribute("tabindex", "7");

		$form->addText("surname", USER_EDIT_SURNAME_LABEL)
			->setAttribute("class", "tinym_required_field form-control")
			->setAttribute("placeholder", USER_EDIT_SURNAME_LABEL)
			->setAttribute("validation", USER_EDIT_SURNAME_LABEL_VALIDATION)
			->setAttribute("tabindex", "8");

		$form->addText("title_after", USER_EDIT_TITLE_AFTER_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_TITLE_AFTER_LABEL)
			->setAttribute("tabindex", "9");

		$form->addText("street", USER_EDIT_STREET_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_STREET_LABEL)
			->setAttribute("tabindex", "10");

		$form->addText("city", USER_EDIT_CITY_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_CITY_LABEL)
			->setAttribute("tabindex", "11");

		$form->addText("zip", USER_EDIT_ZIP_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_ZIP_LABEL)
			->setAttribute("tabindex", "12");

		$states = new StateEnum();
		$form->addSelect("state", USER_EDIT_STATE_LABEL, $states->arrayKeyValue())
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_STATE_LABEL)
			->setDefaultValue("CZECH_REPUBLIC")
			->setAttribute("tabindex", "13");

		$form->addText("web", USER_EDIT_WEB_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_WEB_LABEL)
			->setAttribute("tabindex", "14");

		$form->addText("phone", USER_EDIT_PHONE_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_PHONE_LABEL)
			->setAttribute("tabindex", "15");

		$form->addText("fax", USER_EDIT_FAX_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_FAX_LABEL)
			->setAttribute("tabindex", "16");

		$form->addText("station", USER_EDIT_STATION_LABEL)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_STATION_LABEL)
			->setAttribute("tabindex", "17");

		$form->addMultiSelect("breed", USER_EDIT_BREED_LABEL, $this->enumerationRepository->findEnumItemsForSelectIgnoreEmpty($langCurrent, 7))
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_STATE_LABEL)
			->setAttribute("tabindex", "18");

		$sharing = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, 9);
		reset($sharing);
		$first_key = key($sharing);
		$form->addRadioList("sharing", USER_EDIT_SHARING_LABEL, $sharing)
			->setAttribute("class", "form-check-input margin10")
			->setDefaultValue($first_key)
			->setAttribute("placeholder", USER_EDIT_SHARING_LABEL)
			->setAttribute("tabindex", "19");

		$clubs = $this->enumerationRepository->findEnumItemsForSelect($langCurrent, 17);
		$form->addSelect("club", USER_EDIT_CLUB, $clubs)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_CLUB)
			->setAttribute("tabindex", "20");

		$form->addText("clubNo", USER_EDIT_CLUB_NO)
			->setAttribute("class", "form-control")
			->setAttribute("placeholder", USER_EDIT_CLUB_NO)
			->setAttribute("tabindex", "21");

		$form->addCheckbox("news", "")
			->setAttribute("tabindex", "22");

		$form->addCheckbox("privacy", "")
			->setAttribute("tabindex", "23");

		$form->addHidden("privacy_tries_count", 0);

		$form->addSubmit("confirm", USER_EDIT_SAVE_BTN_LABEL)
			->setAttribute("class","btn btn-primary")
			->setAttribute("tabindex", "24");

		$form->addButton("back", USER_EDIT_BACK_BTN_LABEL)
			->setAttribute("class", "btn btn-secondary")
			->setAttribute("onclick", "location.assign('". $linkBack ."')")
			->setAttribute("tabindex", "25");

		return $form;
	}
}
