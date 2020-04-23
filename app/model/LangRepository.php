<?php

namespace App\Model;

use Dibi\Exception;
use Nette\Application\AbortException;
use Nette\Http\Session;

class LangRepository {

	/** @const string for session key code storage */
	const KEY_SESSION_LANG = "webLang";

	/** @const string width key */
	const KEY_LANG_WIDTH = "LANG_WIDTH";

	/** @const string background color key */
	const KEY_LANG_BG_COLOR = "LANG_BG_COLOR";

	/** @const string font color key */
	const KEY_LANG_FONT_COLOR = "LANG_FONT_COLOR";

	/** @const string lang flag key */
	const KEY_LANG_ITEM_FLAG = "LANG_ITEM_FLAG";

	/** @const string lang description key */
	const KEY_LANG_ITEM_DESC = "LANG_ITEM_DESC";

	/** @const string lang shortcut */
	const KEY_LANG_ITEM_SHORT = "LANG_ITEM_SHORT";

	/** @const file name for language flag*/
	const FLAG_FILENAME = "flag.png";

	/**
	 * Returns all languages mutations by lang config files
	 * @return array
	 */
	public function findLanguages() {
		$result = [];
		$languages = scandir(LANG_PATH);
		foreach ($languages as $index => $value ) {
			if ($value != "." && $value != ".." && $value != self::FLAG_FILENAME)  {
				$result[$value] = $value;
			}
		}

		return $result;
	}

	/**
	 * Finds languages with all the settings
	 * @return array
	 */
	public function findLanguagesWithFlags() {
		$result = [];
		$languages = scandir(LANG_PATH);
		foreach ($languages as $index => $value ) {
			if ($value != "." && $value != ".." && $value != self::FLAG_FILENAME)  {
				$path = DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . self::FLAG_FILENAME;
				if (file_exists(LANG_PATH . $path)) {
					$linkPath = DIRECTORY_SEPARATOR . "locale" . $path;
				} else {
					$linkPath = DIRECTORY_SEPARATOR . "locale" . DIRECTORY_SEPARATOR . self::FLAG_FILENAME;
				}
				$result[$value] = [ self::KEY_LANG_ITEM_FLAG => $linkPath];
			}
		}

		return $result;
	}

	/**
	 * @param Session $sessionSection
	 * @return string
	 */
	public function getCurrentLang(Session $sessionSection) {
		$langSession = $sessionSection->getSection(self::KEY_SESSION_LANG);
		return $langSession->langId;
	}

	/**
	 * Will switch to the another language
	 *
	 * @param Session $sessionSection
	 * @param string $lang
	 */
	public function switchToLanguage(Session $sessionSection, $lang) {
		if ($this->languagesExists($lang)) {
			$langSession = $sessionSection->getSection(self::KEY_SESSION_LANG);
			$langSession->langId = $lang;
		}
	}

	/**
	 * Loads language file
	 *
	 * @param string $langCode
	 */
	public function loadLanguageMutation($langCode) {
		$translation = LANG_PATH . $langCode . DIRECTORY_SEPARATOR . 'translation.php';
		if (file_exists($translation)) {
			require_once $translation;
		} else {
			throw new Exception("Missing language translate file!");
		}
	}

	/**
	 * Finds out if the language code exists in file system by its code (language folder name)
	 *
	 * @param string $lang
	 * @return bool (true = exists; false = does not exist)
	 */
	private function languagesExists($lang) {
		$availableLangs = $this->findLanguages();
		return (isset($availableLangs[$lang]));
	}
}