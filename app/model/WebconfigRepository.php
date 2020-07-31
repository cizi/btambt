<?php

namespace App\Model;

class WebconfigRepository extends BaseRepository{

	/** @const for showing menu input */
	const KEY_WEB_SHOW_HOME = "WEB_SHOW_HOME";

	/** @const for showing menu input */
	const KEY_WEB_HOME_BLOCK = "WEB_HOME_BLOCK";

	/** @const for show menu */
	const KEY_WEB_SHOW_MENU = "WEB_SHOW_MENU";

	/** @const for bg color for menu */
	const KEY_WEB_MENU_BG = "WEB_MENU_BG";

	/** @const for background color for menu links */
	const KEY_WEB_MENU_LINK_COLOR = "WEB_MENU_LINK_COLOR";

	/** @const for site map button */
	const KEY_WEB_SETTING_SITEMAP_BUTTON = "SITEMAP_BUTTON";

	/** @const for web width */
	const KEY_WEB_WIDTH = "WEB_WIDTH";

	/** @const for favicon */
	const KEY_FAVICON = "WEB_FAVICON";

	/** @const for background color */
	const KEY_BODY_BACKGROUND_COLOR = "WEB_CONFIG_BACKGROUND_COLOR";

	/** @const for contact form title color */
	const KEY_CONTACT_FORM_TITLE = "CONTACT_FORM_TITLE";

	/** @const for contact form content color */
	const KEY_CONTACT_FORM_CONTENT = "CONTACT_FORM_CONTENT";

	/** @const for contact form background color */
	const KEY_CONTACT_FORM_BACKGROUND_COLOR = "CONTACT_FORM_BACKGROUND_COLOR";

	/** @const for contact form font color */
	const KEY_CONTACT_FORM_COLOR = "CONTACT_FORM_COLOR";

	/** @const for recipient email address */
	const KEY_CONTACT_FORM_RECIPIENT = "CONTACT_FORM_RECIPIENT";

	/** @const for recipient email address */
    const KEY_CONTACT_FORM_BREEDER_CONSULTANT_EMAIL = "CONTACT_FORM_BREEDER_CONSULTANT_EMAIL";
    
	/** @const for contact form attachment  */
	const KEY_CONTACT_FORM_ATTACHMENT = "CONTACT_FORM_ATTACHMENT";

	/** @const for enabling/disabling footer */
	const KEY_SHOW_FOOTER = "SHOW_FOOTER";

	/** @const for footer width in page */
	const KEY_FOOTER_WIDTH = "FOOTER_WIDTH";

	/** @const for showing contact form in footer */
	const KEY_SHOW_CONTACT_FORM_IN_FOOTER = "SHOW_CONTACT_FORM_IN_FOOTER";

	/** @const for footer content */
	const KEY_FOOTER_CONTENT = "FOOTER_CONTENT";

	/** @const for footer files */
	const KEY_FOOTER_FILES = "FOOTER_FILES";

	/** @const for footer background color */
	const KEY_FOOTER_BACKGROUND_COLOR = "FOOTER_BACKGROUND_COLOR";

	/** @const for footer font color */
	const KEY_FOOTER_COLOR = "FOOTER_COLOR";

	/** @const for enabling/disabling header */
	const KEY_SHOW_HEADER = "SHOW_HEADER";

	/** @const for header in page */
	const KEY_HEADER_WIDTH = "HEADER_WIDTH";

	/** @const for header content */
	const KEY_HEADER_CONTENT = "HEADER_CONTENT";

	/** @const for header files */
	const KEY_HEADER_FILES = "HEADER_FILES";

	/** @const for header background color */
	const KEY_HEADER_BACKGROUND_COLOR = "HEADER_BACKGROUND_COLOR";

	/** @const for header height */
	const KEY_HEADER_HEIGHT = "HEADER_HEIGHT";

	/** @const for header font color */
	const KEY_HEADER_COLOR = "HEADER_COLOR";

	// -- language depends --
	/** @const for key in common parameters */
	const KEY_LANG_FOR_COMMON = '';

	/** @const for title */
	const KEY_WEB_TITLE = "WEB_TITLE";

	/** @const for google analytics */
	const KEY_WEB_GOOGLE_ANALYTICS = "WEG_GOOGLE_ANALYTICS";

	/** @const for web keywords */
	const KEY_WEB_KEYWORDS = "WEB_KEYWORDS";

	/** @const for web language */
    const KEY_WEB_MUTATION = "WEB_MUTATION";
    
    /** @const for counters section */
    const KEY_COUNTER_COVERAGE_BT = "COUNTER_COVERAGE_BT";
    const KEY_COUNTER_COVERAGE_MBT = "COUNTER_COVERAGE_MBT";

	/** @var array cache for values => less queris to DB */
	private $cache = [];

	/**
	 * @return array
	 */
	public function load($lang) {
		$query = ["select * from web_config where lang = %s", $lang];
		$result = $this->connection->query($query)->fetchAll();

		$ret = [];
		foreach($result as $line) {
			$ret[$line->id] = $line->value;
			$cache[$line->id] = $line->value;
		}

		return $ret;
    }

	/**
	 * @param string $key
	 * @param string $value
	 * @param string $lang
	 * @return \Dibi\Result|int
	 */
	public function save($key, $value, $lang) {
		$query = ["select * from web_config where id = %s and lang = %s", $key, $lang];
		if ($this->connection->query($query)->fetch()) { // update
			$query = ["update web_config set value = %s where id = %s and lang = %s", $value, $key, $lang];
		} else {	// insert
			$query = ["insert into web_config values (%s, %s ,%s)", $key, $lang, $value];
		}

		return $this->connection->query($query);
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getByKey($key, $lang = "cs") {
		// fill the cache
		if (count($this->cache) == 0 || !isset($this->cache[$key])) {
			$this->load($lang);
		}

		$ret = isset($this->cache[$key]) ? $this->cache[$key] : "";
		if (empty($ret)) {	// last chance to find it directly
			$query = ["select * from web_config where id = %s and lang = %s", $key, $lang];
			$result = $this->connection->query($query)->fetch();
			if ($result) {
				$ret = $result->value;
			}
		}

		return $ret;
	}
}