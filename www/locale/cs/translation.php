<?php

// formulář login do administrace
define("ADMIN_LOGIN_HEADER", 'Přihlášení');
define("ADMIN_LOGIN_EMAIL", 'Email');
define("ADMIN_LOGIN_EMAIL_REQ", 'Prosím vyplňte Váš login.');
define("ADMIN_LOGIN_PASS", 'Heslo');
define("ADMIN_LOGIN_PASS_REQ", 'Prosím vyplňte heslo');
define("ADMIN_LOGIN_LANG", 'Jazyk rozhraní');
define("ADMIN_LOGIN_REMEMBER_ME", 'Zapamatuj si mě');
define("ADMIN_LOGIN_FORGOTTEN_PASSWORD", 'Zapomenuté heslo?');
define("ADMIN_LOGIN_RESET_PASSWORD", 'Obnovit heslo');
define("ADMIN_LOGIN_RESET_PASSWORD_EMAIL_FAIL", 'Email nebyl nalezen v databázi.');
define("ADMIN_LOGIN_RESET_SUCCESS", 'Heslo bylo úspěšně změněno a odesláno na email.');
define("ADMIN_LOGIN_RESET_FAILED", 'Obnova hesla se nezdařila, opakujte později.');
define("ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_SUBJECT", 'Změna hesla v systému %s');
define("ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_SUBJECT_PLAIN", 'Změna hesla v systému');
define("ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_BODY", "Dobrý den, <br />Vaše heslo do systému %s bylo úspěšně změněno. <br /><br /> Nové heslo je '%s'. <br /><br />");
define("ADMIN_LOGIN_LOGIN", 'Přihlásit');
define("ADMIN_LOGIN_EMAIL_PLACEHOLDER", 'Email');
define("ADMIN_LOGIN_PASS_PLACEHOLDER", 'Heslo');
define("ADMIN_LOGIN_SUCCESS", 'Přihlášení proběhlo v pořádku');
define("ADMIN_LOGIN_SUCCESS_NO_PRIVACY", 'Dosud jste nesouhlasil/a se zpracováním GDPR. Prosíme, zvažte tento souhlas, pro plné využití služeb genealogie.');
define("ADMIN_LOGIN_FAILED", 'Neplatné jméno nebo heslo');
define("ADMIN_LOGIN_UNLOGGED", 'Odhlášení proběhlo v pořádku.');

// položky menu
define("MENU_TITLE", 'Hlavní nabídka');
define("MENU_DASHBOARD", 'Dashboard');
define("MENU_HEADER", 'Hlavička');
define("MENU_LOGO", 'Slider');
define("MENU_MENU", 'Nabídka');
define("MENU_BLOCK", 'Bloky');
define("MENU_MENU_BLOCK", 'Nabídka vs bloky');
define("MENU_CONTACT_FORM", 'Kontaktní formulář');
define("MENU_FOOTER", 'Patička');
define("MENU_USERS", 'Uživatelé');
define("MENU_SETTINGS", 'Nastavení');
define("MENU_ENUMERATION", 'Číselníky');
define("MENU_COUNTER", 'Číselné řady');
define("MENU_VETS", 'Veterináři / Laboratoře');
define("MENU_LANG", 'Jazyková nastavení');
define("MENU_LOGOUT", 'Odhlásit');
define("MENU_FRONTEND", 'Zpět na stránky');

// admin - users list
define("USER_TITLE", 'Uživatelé');
define("USER_INFO_TEXT", "Zde je možné spravovat přístup uživatelů do systému. Je možmé uživatele přidávat, odebírat, aktivaovat nebo odebírat. <br /><br />
							Přihlašovací jméno uživatele je jeho emailová adresa, tím se zaručí jedinečnost bez duplicit uživatelů. Mějte prosím, na
							paměti, že veškeré změny jsou nevratné!");
define("USER_TABLE_HEADER_LOGIN", "Login");
define("USER_TABLE_HEADER_ROLE", "Role");
define("USER_TABLE_HEADER_ACTIVE", "Aktivní");
define("USER_TABLE_HEADER_LAST_LOGIN", "Poslední přihlášení");
define("USER_TABLE_HEADER_REGISTERED_DATE", "Datum registrace");
define("USER_DELETED", "Uživatel byl smazán");
define("USER_ADDED", "Uživatel přidán");
define("USER_EDITED", "Uživatel upraven");
define("USER_EMAIL_ALREADY_EXISTS", "Uživatel s tímto emailem je již registrován");
define("USER_DELETED_FAILED", "Uživatele se nepovedlo smazat, opakujte později.");
define("USER_DELETE", 'Odstranit uživatele');
define("USER_EDIT", 'Editovat uživatele');
define("USER_ADD_USER", 'Přidat uživatele');
define("USER_CONFIRM_DELETE_TITLE", 'Smazání uživatele');
define("USER_CONFIRM_DELETE", 'Opravdu smazat uživatele?');
define("USER_CONFIRM_DELETE_CANCEL", 'Zpět');
define("USER_CONFIRM_DELETE_OK", 'Smazat');
define("USER_ERROR_ACTIVE_SWITCH", 'Došlo k chybě při komunikaci ze serverem, opakujte prosím později.');
define("USER_CREATED_MAIL_SUBJECT", 'Registrace nového uživatele');
define("USER_CREATED_MAIL_BODY", "Dobrý den, <br /><br /> Vaše registace na serveru %s proběhla úspěšně.<br /> Vaše příhlašovací údaje jsou: <br /> jméno: %s <br /> heslo: %s <br /><br />");
define("USER_SEARCH_FIELD",  "Fulltextové vyhledávání v uživatelích");
define("USER_SEARCH_BUTTON",  "Hledat");

// admin - user edit
define("USER_EDIT_FORM_ADD", 'Přidání nového uživatele');
define("USER_EDIT_FORM_EDIT", 'Editace uživatele %s');
define("USER_EDIT_EMAIL_LABEL", 'Email');
define("USER_EDIT_EMAIL_REQ", 'Položka email je povinná!');
define("USER_EDIT_EMAIL_VALIDATION", 'Vložte platnou emailovou adresu!');
define("USER_EDIT_PASS_LABEL", 'Heslo');
define("USER_EDIT_PASS_REQ", 'Položka heslo je povinná!');
define("USER_EDIT_PASS_AGAIN_LABEL", 'Potvrzení hesla');
define("USER_EDIT_PASS_AGAIN_REQ", 'Položka potvrzení hesla je povinná!');
define("USER_EDIT_ROLE_LABEL", 'Role');
define("USER_EDIT_ACTIVE_LABEL", 'Aktivní');
define("USER_EDIT_SAVE_BTN_LABEL", 'Uložit');
define("USER_EDIT_SITEMAP_BTN_LABEL", 'Generovat soubory sitemap');
define("USER_EDIT_SITEMAP_AVAIL", 'Soubor sitemap.xml');
define("USER_EDIT_SITEMAP_GENERATION_DONE", 'Soubor sitemap.xml byl úspěšně vygenerován');
define("USER_EDIT_SITEMAP_BTN_INFO", 'Vygeneruje soubor(y) sitemap pro vyhledáváče. Souborů bude tolik kolik je jazykových mutací webu.');
define("USER_EDIT_BACK_BTN_LABEL", 'Zpět');
define("USER_EDIT_SAVE_FAILED", 'Nepovedlo se zpracovat změny, opakujte prosím později');
define("USER_EDIT_PASSWORDS_EMPTY", 'Neplatné heslo');
define("USER_EDIT_PASSWORDS_DOESNT_MATCH", 'Hesla se neshodují');
// frontend - user
define("USER_EDIT_CURRENT_PASSWORD", 'Aktuální heslo');
define("USER_EDIT_CURRENT_PASSWORD_FAILED", 'Aktuální heslo není správné');
define("USER_EDIT_CURRENT_PASSWORD_REQ", 'Položka aktuální heslo je povinná');
define("USER_EDIT_PASSWORD_CHANGED", 'Heslo bylo úspešně změněno');
// specials
define("USER_EDIT_TITLE_BEFORE_LABEL", 'Titul před');
define("USER_EDIT_NAME_LABEL", 'Jméno');
define("USER_EDIT_NAME_LABEL_VALIDATION", 'Položka jméno je povinná!');
define("USER_EDIT_SURNAME_LABEL", 'Přijímení');
define("USER_EDIT_SURNAME_LABEL_VALIDATION", 'Položka přijímení je povinná!');
define("USER_EDIT_TITLE_AFTER_LABEL", 'Titul za');
define("USER_EDIT_STREET_LABEL", 'Ulice');
define("USER_EDIT_CITY_LABEL", 'Město');
define("USER_EDIT_ZIP_LABEL", 'PSČ');
define("USER_EDIT_ZIP_VALIDATION", 'PSČ musí být číslo');
define("USER_EDIT_STATE_LABEL", 'Stát');
define("USER_EDIT_WEB_LABEL", 'WWW');
define("USER_EDIT_PHONE_LABEL", 'Telefon');
define("USER_EDIT_FAX_LABEL", 'Fax');
define("USER_EDIT_STATION_LABEL", 'Chovatelská stanice');
define("USER_EDIT_STATION_LABEL_SHORT", 'CHS');
define("USER_EDIT_BREED_LABEL", 'Plemeno');
define("USER_EDIT_SHARING_LABEL", 'Sdílení informací');
define("USER_EDIT_CLUB", 'Člen klubu');
define("USER_EDIT_CLUB_NO", 'Členské číslo');
define("USER_EDIT_NEWS", 'Souhlasím s použitím mé e-mailové adresy pro zasílání zpráv, novinek a akcí.');
define("USER_EDIT_PRIVACY", 'Souhlasím se zpracování osobních údajů v rámci GDPR.');
define("USER_EDIT_PRIVACY_VALIDATION", "Je nezbytné souhlasit se zpracováním osobních údajů v rámci GDPR.");
define("USER_EDIT_PRIVACY_URL", "Více informací.");

// user roles - select
define("USER_ROLE_LAYOUT_CHANGER", "Plná práva");
define("USER_ROLE_CONTENT_CHANGER", "Uživatel může měnit obsah");
define("USER_ROLE_GUEST", "Host");
define("USER_REGISTERED", "Registrovaný");
define("USER_OWNER", "Majitel");
define("USER_EDITOR", "Poradce chovu");
define("USER_ROLE_ADMINISTRATOR", "Administrátor");

// webconfig
define("WEBCONFIG_WEBMUTATION", "Jazyková mutace");
define("WEBCONFIG_WEBMUTATION_INFO", "Právě editované nastavení bude použito pro zvolenou jazykovou mutaci. Pokud máte více jazykových mutací
										je nutné provést toto nastavení pro všechny jazykové mutace.");
define("WEBCONFIG_TITLE", "Nastavení webu");
define("WEBCONFIG_TITLE_INFO", "Zde je možné konfigurovat rozložení webu, jeho šířku, případně vložení Google Analitics. ");
define("WEBCONFIG_WEB_NAME", "Název webu");
define("WEBCONFIG_WEB_NAME_INFO", "Tento název bude zobrazen v hlavičce prohlížeče. Je taktéž důležitý pro vyhledavače");
define("WEBCONFIG_WEB_KEYWORDS", "Klíčová slova");
define("WEBCONFIG_WEB_KEYWORDS_INFO", "Jednotlivá klíčová slova od sebe oddělujte čárkou (,).
										<b>Poznámka:</b> klíčová slova v meta tagu keywords dnešní vyhledávače ignorují, ale i tak se
										doporučuje klíčova slova použít");

define("WEBCONFIG_WEB_WIDTH", "Šířka webu");
define("WEBCONFIG_WEB_WIDTH_INFO", "Určuje šířku webu v prohlížeči. Tento údaj nemá vliv na responzivitu webu.");
define("WEBCONFIG_WEB_FAVICON", "Ikona webu");
define("WEBCONFIG_WEB_FAVICON_INFO", "Ikona se zobrazuje v adresním řádku, na panelu se stránkou a v nabídce záložek/oblíbených položek.
										Ikona musí mít, pro správné zobrazení, určitá pravidla. nejčastěji jde o ikonu 16x16 pixelů.
										Formát musí být ICO (ikona).");
define("WEBCONFIG_WEB_FAVICON_FORMAT", "Obrázek musí být ikona, formát ICO!");
define("WEBCONFIG_WEB_GOOGLE_ANALYTICS", "Google Analytics");
define("WEBCONFIG_WEB_GOOGLE_ANALYTICS_INFO", "Google Analytics Vám pomáhá identifikovat odkad přišel návštěvník Vaěich stránek,
												jak dlouho se zdržel a co ho na Vašich stránkách zajímalo nejvíce. Kód Google Analytics je
												Javascriptový kód, který se vkládá přímo do každé stránky. Více o registraci a použití se
												můžete dočíst zde:
												<a target='_blank' href='https://www.google.com/analytics/'>https://www.google.com/analytics/</a>");
define("WEBCONFIG_WEB_SAVE_SUCCESS", "Změny byly v pořádku uloženy");
define("WEBCONFIG_WEB_BACKGROUND_COLOR", "Barva pozadí");
define("WEBCONFIG_WEB_BACKGROUND_COLOR_INFO", "Zde můžete vybrat barvu pozadí pro Váš web. Barva bude použita na celý podklad webu.
												Pro bílou (žádnou barvu) smažte hodnotu z pole.");
define("WEBCONFIG_WEB_MENU_SHOW", "Zobrazit hlavní nabídku");
define("WEBCONFIG_WEB_MENU_SHOW_INFO", "Určuje zda bude zobrazena hlavní nabídka (menu).");
define("WEBCONFIG_WEB_MENU_BACKGROUND_COLOR", "Barva hlavní nabídky");
define("WEBCONFIG_WEB_MENU_BACKGROUND_COLOR_INFO", "Zde můžete vybrat barvu hlavní nabídky (menu). Pro výchozí hodnotu smažte.");
define("WEBCONFIG_WEB_MENU_LINK_COLOR", "Barva odkazů v hlavní nabídce");
define("WEBCONFIG_WEB_MENU_LINK_COLOR_INFO", "Zde můžete vybrat barvu pro odkazy v nabídce. Pro výchozí hodnotu smažte.");

define("WEBCONFIG_SETTINGS_SHOW_HOME", 'Zobrazovat odkaz domů');
define("WEBCONFIG_SETTINGS_SHOW_HOME_INFO", 'Na první pozici v menu zobrazí domeček, který bude odkazovat na úvodní stránku.');
define("WEBCONFIG_SETTINGS_SHOW_BLOCK", 'Block domovské stránky');
define("WEBCONFIG_SETTINGS_SHOW_BLOCK_INFO", 'Vyberte block který se má zobrazovat na domovské obrazovce, tedy hlavní stránka webu.');
define("WEBCONFIG_SETTINGS_LANG_DEPENDS", 'Nastavení závislé na jazyku');
define("WEBCONFIG_SETTINGS_LANG_COMMON", 'Obecné nástavení');

// system counters
define("COUNTER_TITLE", 'Číselné řady');
define("COUNTER_TITLE_INFO", 'Konfigurace číselných řad použitých v systému.');
define("COUNTER_COVERAGE_COUNTER", 'Číselné řady krycích listů');
define("COUNTER_COVERAGE_BT_COUNTER", 'Nadcházející číslo krycího listu BT');
define("COUNTER_COVERAGE_MBT_COUNTER", 'Nadcházející číslo krycího listu MBT');
define("COUNTER_LITTER_COUNTER", 'Číselné řady přihlášek k zápisu vrhu');
define("COUNTER_LITTER_BT_COUNTER", 'Nadcházející číslo přihlášky k zápisu vrhu BT');
define("COUNTER_LITTER_MBT_COUNTER", 'Nadcházející číslo přihlášky k zápisu vrhu MBT');

// modal window
define("MODAL_BUTTON_OK", 'OK');
define("MODAL_WINDOWS_WARNING_TITLE", 'Varování');

// slider
define("SLIDER_SETTINGS", "Nastavení obrázku (slider)");
define("SLIDER_SETTINGS_INFO", "Slider může být buď jeden obrázek nebo několik obrázků, které se budou náhodně střídat v záhlaví webové stránky. <br />
								<b>Důležité: </b> Pokud bude nahráváno více obrázků je <b>nutné</b>, aby byly všechny obrázky ve stejném rozlišení.");
define("SLIDER_SETTINGS_PICS", "Nahrát obrázky slideru");
define("SLIDER_SETTINGS_PICS_INFO", "Obrázky je možné nahrávat po jednom nebo po více najednou.");
define("SLIDER_SETTINGS_CURRENT_PICS", "Nynější obrázky slideru");
define("SLIDER_SETTINGS_SAVE_BTN_LABEL", 'Uložit');
define("SLIDER_SETTINGS_PIC_FORMAT", "Jsou podporovány jen obrázky (BMP, JPG, PNG)! Nahrajte prosím obrázky v tomto formátu.");
define("SLIDER_SETTINGS_CONFIRM_MODAL_DELETE_TITLE", 'Smazaní položky slideru');
define("SLIDER_SETTINGS_CONFIRM_MODAL_DELETE_MSG", 'Opravdu chcete smazat položku slideru?');
define("SLIDER_SETTINGS_CONFIRM_MODAL_OK", 'Smazat');
define("SLIDER_SETTINGS_CONFIRM_MODAL_CANCEL", 'Zpět');
define("SLIDER_SETTINGS_DELETE_TITLE", 'Smazání obrázku');
define("SLIDER_SETTINGS_SLIDER_ACTIVE_LABEL", 'Zapnout slider');
define("SLIDER_SETTINGS_SLIDER_ACTIVE_INFO", 'Zapíná nebo vypíná slider v záhlaví stránky.');
define("SLIDER_SETTINGS_SLIDER_SLIDING_LABEL", 'Zapnout slideshow');
define("SLIDER_SETTINGS_SLIDER_SLIDING_INFO", 'Pokud bude slideshow zapnuta budou se obrázky náhodně střídat ve Vámi zvoleném intervalu.
											Pokud zůstane slideshow vypnuta, bude vždy zobrazen staticky jen jeden obrázek. Ovšem pokud
											bude ve slideru nahráno více obrázku bude při každém obnovení stránky zobrazen obrázek jiný.');
define("SLIDER_SETTINGS_SLIDER_ARROWS_LABEL", 'Zobrazit ovládání slideru');
define("SLIDER_SETTINGS_SLIDER_ARROWS_INFO", 'Zobrazí šipky na krajích slideru, se kterými je možné přpínat obrázky ručně.');

define("SLIDER_SETTINGS_SLIDER_WITDH", 'Šířka slideru');
define("SLIDER_SETTINGS_SLIDER_WITDH_INFO", 'Udává hodnotu jak široký má být slider vůči obsahu. <br />
											100% = bude zabírat celou šírku těla stránek <br />
											50%  = bude zabírat polovinu šířky stránek <br />
											<b>Poznámka:</b> 100% je výchozí a nejvíce chtěné nastavení pro většinu uživatelů');
define("SLIDER_SETTINGS_TIMING", 'Časování slideru (s)');
define("SLIDER_SETTINGS_TIMING_INFO", 'Číslo určuje jak často se bude vyměnovat obrázek ve slideru. Hodnota je udávána ve vteřinách.');
define("SLIDER_SETTINGS_SAVE_OK", "Změny byly úspěšně uloženy.");


// menu
define("MENU_SETTINGS_TITLE", 'Konfigurace menu');
define("MENU_SETTINGS_INFO", '');
define("MENU_SETTINGS_ITEM_NAME", 'Název položky v menu');
define("MENU_SETTINGS_ITEM_NAME_REQ", 'Název položky v menu je povinné pole!');
define("MENU_SETTINGS_ITEM_LINK", 'Odkaz v URL');
define("MENU_SETTINGS_ITEM_LINK_REQ", 'Odkaz v URL je povinné pole!');
define("MENU_SETTINGS_ITEM_SEO", 'SEO titulek');
define("MENU_SETTINGS_ITEM_LINK_ADDED", 'Položka byla úspěšně vložena');
define("MENU_SETTINGS_ITEM_DELETED", 'Položka byla úspěšně smazána');
define("MENU_SETTINGS_ITEM_LINK_FAILED", 'Při ukládání došlo k chybě. Zkuste opakovat později. Případně ověřte, že nevkládáte duplicitní hodnodu odkazu, ta musí být jedinečná');
define("MENU_SETTINGS_ITEM_LINK_INFO", 'Název položky menu v URL. <b>DŮLEŽITÉ pro SEO</b>.');
define("MENU_SETTINGS_SUBMENU", 'Bude mít další úroveň');
define("MENU_SETTINGS_ADD", 'Přidat položku');
define("MENU_SETTINGS_TABLE_ORDER", 'Pořadí v menu');
define("MENU_SETTINGS_LINK", 'Odkaz v URL');
define("MENU_SETTINGS_IN_MENU_TITLE", 'Název položky v mennu');
define("MENU_SETTINGS_ALT", 'SEO titulek');
define("MENU_SETTINGS_MENU_TOP_DELETE", 'Smazat položku menu');
define("MENU_SETTINGS_EDIT_ITEM", 'Editovat položku menu');
define("MENU_SETTINGS_ADD_SUBITEM", 'Přidat pod položku menu');
define("MENU_SETTINGS_MOVE_ITEM_UP", 'Posunout výš');
define("MENU_SETTINGS_MOVE_ITEM_DOWN", 'Posunout níž');
define("MENU_SETTINGS_CONFIRM_MODAL_DELETE_TITLE", 'Smazaní položky menu');
define("MENU_SETTINGS_CONFIRM_MODAL_DELETE_MSG", 'Opravdu chcete smazat položku menu?');
define("MENU_SETTINGS_ITEM_MOVE_UP", 'Položka menu byla v pořádku posunuta výše.');
define("MENU_SETTINGS_ITEM_MOVE_DOWN", 'Položka menu byla v pořádku posunuta níže.');
define("MENU_SETTINGS_ITEM_MOVE_FAILED", 'Při úpravě položky menu došlo k chybě.');
define("MENU_SETTINGS_ITEM_TITLE", 'Konfigurace položky menu');
define("MENU_SETTINGS_ITEM_INFO", 'Zde uveďte požadované parametry položky menu. SEO titulek zvolte takový, který nejlépe vystihuje obsah stránky,
                                který bude asociován s obsahem. ');
define("MENU_SETTINGS_ITEM_VISIBILITY", 'Zapnuto');

// constact form
define("CONTACT_FORM_SETTING_TITLE", 'Nastavení kontakního formuláře');
define("CONTACT_FORM_SETTING_TITLE_INFO", 'Zde je možné nastavit některé vlastnosti kontakního fomuláře.');
define("CONTACT_FORM_NAME", 'Vaše jméno');
define("CONTACT_FORM_NAME_REQ", 'Pole jméno nesmí být prázdné!');
define("CONTACT_FORM_EMAIL", 'Kontaktní email');
define("CONTACT_FORM_EMAIL_REQ", 'Pole email nesmí být prázdné!');
define("CONTACT_FORM_SUBJECT", 'Předmět');
define("CONTACT_FORM_SUBJECT_REQ", 'Pole předmět nesmí být prázdné');
define("CONTACT_FORM_ATTACHMENT", 'Příloha');
define("CONTACT_FORM_ATTACHMENT_INFO", 'Umožní do kontaktního fomuláře vložit uživateli přílohu a odeslat ji spolu s emailem.');
define("CONTACT_FORM_TEXT", 'Vaše zpráva');
define("CONTACT_FORM_TEXT_REQ", 'Tělo zprávy nesmí být prázdné');
define("CONTACT_FORM_BUTTON_CONFIRM", 'Odeslat');

define("CONTACT_FORM_SETTING_BACKEND_TITLE", 'Nadpis kontaktního formuláře');
define("CONTACT_FORM_SETTING_BACKEND_CONTENT", 'Nadpis kontaktního formuláře');
define("CONTACT_FORM_SETTING_BACKGROUND_COLOR", 'Barva pozadí kontaktního formuláře');
define("CONTACT_FORM_SETTING_COLOR", 'Barva textu kontaktního formuláře');
define("CONTACT_FORM_SETTING_RECIPIENT", 'Příjemce emailu z kontaktního formuláře');
define("CONTACT_FORM_SETTING_RECIPIENT_VALIDATION", 'Email příjemce z kontaktního formuláře je povinná položka!');
define("CONTACT_FORM_SETTING_RECIPIENT_INFO", 'Na tento email bude zaslána poptávka z kontaktního formuláře. Pokud chcete zadat více emailový adres oddelte je středníkem (;).');
define("CONTACT_FORM_SETTING_ATTACHMENT", 'Umožnit poslat přílohu');
define("CONTACT_FORM_SETTING_SAVE", 'Uložit');
define("CONTACT_FORM_SETTING_COMPLETE_SAVE", 'Nastavení kontaktního formuláře bylo uloženo');
define("CONTACT_FORM_WAS_SENT", 'Váš dotaz byl úspěšně odeslán.');
define("CONTACT_FORM_SENT_FAILED", 'Nebyly vyplněny všechny povinné položky pro odeslání.');
define("CONTACT_FORM_UNSUPPORTED_FILE_FORMAT", 'Pokoušíte se přidat přílohu, která není podporována!');
define("CONTACT_FORM_EMAIL_MY_SUBJECT", 'Poptávka z webového formuláře');

// footer
define("FOOTER_CONTENT", 'Obsah patičky');
define("FOOTER_BUTTON_SAVE", "Uložit");
define("FOOTER_TITLE", "Nastavení patičky");
define("FOOTER_INFO", "Patička slouží k zobrazení určitého obsahu na každé stránce. ");
define("FOOTER_CONTACT", "Zobrazit kontaktní formulář v patičce stránek");
define("FOOTER_CONTENT_TEXT", 'Obsah patičky můžete naformátovat pomocí WYSIWYG editoru. Pokud bude chtít zobrazit v patičce zobrazit obrázky
								je nutné je prvně nahrát a následně vložit URL obrázku do editoru.');
define("FOOTER_CONTENT_PICS", 'Zde nahrajde obrázky, které byste rádi vložily do patičky.');
define("FOOTER_BACKGROUND_COLOR", 'Barva pozadí patičky');
define("FOOTER_SETTING_COLOR", 'Barva textu patičky');
define("FOOTER_SETTING_SAVED", 'Nastavení patičky bylo uloženo');
define("FOOTER_PIC_FORMAT", "Jsou podporovány jen obrázky (BMP, JPG, PNG)! Nahrajte prosím obrázky v tomto formátu.");
define("FOOTER_PIC_DELETE", 'Odstranění obrázku');
define("FOOTER_PIC_DELETED", 'Obrázek byl úspěšně odstraněn');
define("FOOTER_CONFIRM_DELETE_TITLE", 'Smazání obrázku');
define("FOOTER_CONFIRM_DELETE", 'Opravdu smazat obrázek?');
define("FOOTER_CONFIRM_DELETE_CANCEL", 'Zpět');
define("FOOTER_CONFIRM_DELETE_OK", 'Smazat');
define("FOOTER_SHOW_FOOTER", 'Zapnout patičku');
define("FOOTER_WIDTH", 'Šířka patičky');
define("FOOTER_WIDTH_INFO", 'Nastaví šíři patičky vůči celé možné šíři stránek (nepočítává se do šíře obsahu stránek)');
define("FOOTER_PIC_CONTENT", 'Obrázky, které je možné vložit do patičky');


// block
define("BLOCK_SETTING_TITLE", 'Obsahové bloky');
define("BLOCK_SETTING_INFO", 'Obsahové bloky jsou části stráńek, které budou zobrazeny na webu. Jednu stránku může tvořit pouze jeden block,
							nebo klidně více bloků. V této sekci nastavte jednotlivé bloky, prolinkování bloků s menu se nastavuje v
							samostatné sekci.');
define("BLOCK_SETTING_CONTENT", 'Obsah');
define("BLOCK_SETTING_BG_COLOR", 'Barva pozadí');
define("BLOCK_SETTING_COLOR", 'Barva textu');
define("BLOCK_SETTING_EDIT_ITEM", 'Editovat blok');
define("BLOCK_SETTING_DELETE_ITEM", 'Odstranit blok');
define("BLOCK_SETTING_PICS", 'Dostupné obrázky');
define("BLOCK_SETTING_WIDTH", 'Šířka bloku');
define("BLOCK_SETTING_PICS_INFO", 'Dostuoné obrázky, které je možné vložit do obsahu bloku. Stačí zkopírovat adresu uloženou vedle obrázku a v editoru
 								textu tuto adresu opět zadat i s rozměry obrázku. Obrázek se následně zobrazí v editoru kde bude možné donastavit obtékání
 								textu a jiná nastavení. <br />
 								<b>TIP: </b> Pro vložení obrázku, který nebyl zatím nahrán je nutné
								 obrázek nejdříve nahrát (tedy uložit blok) a poté se vrátit zpět do editace bloku.');
define("BLOCK_SETTING_ITEM_EDIT", 'Nastavení bloku');
define("BLOCK_SETTING_ITEM_EDIT_INFO", 'Zde je veškeré nastavení jednoho bloku včetně jazykový mutací. ');
define("BLOCK_SETTING_ITEM_CONTENT_LABEL", 'Obsah bloku');
define("BLOCK_SETTING_ITEM_CONTENT_CONFIRM", 'Uložit blok');
define("BLOCK_SETTING_ITEM_CONTENT_COLOR", 'Barva text v bloku');
define("BLOCK_SETTING_ITEM_CONTENT_BG_COLOR", 'Barva pozadí bloku');
define("BLOCK_SETTING_ITEM_WIDTH_INFO", 'Nastaví šíři bloku vůči šíři stránek. ');
define("BLOCK_SETTING_PIC_WILL_DELETE", 'Odstranění obrázku');
define("BLOCK_SETTING_PIC_DELETED", 'Obrázek byl úspěšně odstraněn');
define("BLOCK_SETTING_PIC_DELETE_TITLE", 'Smazání obrázku');
define("BLOCK_SETTING_PIC_DELETE", 'Opravdu smazat obrázek?');
define("BLOCK_SETTING_PIC_DELETE_CANCEL", 'Zpět');
define("BLOCK_SETTING_PIC_DELETE_OK", 'Smazat');
define("BLOCK_SETTINGS_CONFIRM_MODAL_DELETE_TITLE", 'Smazaní bloku');
define("BLOCK_SETTINGS_CONFIRM_MODAL_DELETE_MSG", 'Opravdu chcete smazat obsahový blok?');
define("BLOCK_SETTINGS_CONFIRM_MODAL_OK", 'Smazat');
define("BLOCK_SETTINGS_CONFIRM_MODAL_CANCEL", 'Zpět');
define("BLOCK_SETTINGS_ITEM_DELETED", 'Položka byla úspěšně smazána.');
define("BLOCK_SETTINGS_ITEM_DEFAULT_BLOCK", 'Tento blok nelze smazat! Je nastaven jako block domací stránky.');
define("BLOCK_SETTINGS_ITEM_DELETED_FAILED", 'Při mazání položky došlo k chybě!');
define("BLOCK_SETTINGS_ITEM_SAVED_FAILED", 'Při uložení položky došlo k chybě!');

// block and content
define("BLOCK_CONTENT_SETTINGS", 'Obsah webu');
define("BLOCK_CONTENT_SETTINGS_INFO", 'V této sekci se sestavuje obsah celého webu. Odkazy z menu se dávají dávají dohromady s bloky. Každá položka
										(podpoložka) z menu by měla mít alespoň jeden obsahový blok (může samozřejmě mít bloků více). Jako název stránky
										je použit titulek, který byl zadán do položky menu.');
define("BLOCK_CONTENT_SETTINGS_BLOCKS_IN_MENU", 'Obsah odkazu webu');
define("BLOCK_CONTENT_SETTINGS_BLOCKS_IN_CONTENT", 'Bloky zařazené v odkazu');
define("BLOCK_CONTENT_SETTINGS_BLOCKS_IN_CONTENT_INFO", 'Bloky v odkazu tvoří obsah stránky. Bloků je možné vložit do stránky několik, jejich pořadí pak
														určuje jak budou na stránce vykresleny. Každý odkaz by měl obsahovat alespoň jeden blok.');
define("BLOCK_CONTENT_SETTINGS_AVAILABLE_BLOCKS", 'Dostupné bloky');
define("BLOCK_CONTENT_SETTINGS_AVAILABLE_BLOCKS_INFO", 'Bloky, které je možné do stránky zažadit.');
define("BLOCK_CONTENT_SETTINGS_CONTACT_FORM_AS_BLOCK", 'Kontaktní formulář');
define("BLOCK_CONTENT_SETTINGS_ADD_TITLE", 'Přidat blok do odkazu');
define("BLOCK_CONTENT_SETTINGS_REMOVE_TITLE", 'Odstranit blok z odkazu');
define("BLOCK_CONTENT_SETTINGS_MOVE_BLOCK_UP", 'Posunout blok nahoru');
define("BLOCK_CONTENT_SETTINGS_MOVE_BLOCK_DOWN", 'Posunout blok dolu');
define("BLOCK_CONTENT_SETTINGS_NO_BLOCKS", '-- žádné --');

// language
define("LANG_SETTINGS", 'Jazykové mutace');
define("LANG_SETTINGS_GLOBAL", 'Nastavení jazykového proužku');
define("LANG_BG_COLOR", 'Barva pozadí jazykového proužku');
define("LANG_BG_COLOR_INFO", 'Zde vyberta požadovanou barvu pozadí.');
define("LANG_FONT_COLOR", 'Barva textu v proužku');
define("LANG_FONT_COLOR_INFO", 'Zde vyberte barvu textů (odkazů) v jazykovém proužku');
define("LANG_ITEM_FLAG", 'Vlajka jazyka');
define("LANG_ITEM_DESC", 'Popis jazyka');
define("LANG_ITEM_SHORT", 'Zkratka pro jazyk');
define("LANG_TITLE_INFO", "Zde je možné konfigurovat jazykové mutace, jejich zobrazení apod.");
define("LANG_WIDTH", 'Šířka jakykového proužku');
define("LANG_WIDTH_INFO", 'Udává se procentuální šířka webu vůčí šíři okna prohlížeče');
define("LANG_ALREADY_SAVED_LANGS", 'Uložené jazykové mutace');
define("LANG_ALREADY_NEW_LANG", 'Uložení nového jazyka');
define("LANG_CONFIRM", 'Uložit jazykové nastavení');
define("LANG_TABLE_SHORTCUT", 'Jazyk');
define("LANG_TABLE_FLAG", 'Ikona vlajky');
define("LANG_TABLE_DELETE", 'Odstarnit mutaci');
define("LANG_CONFIRM_MODAL_DELETE_TITLE", 'Smazaní jazykové mutace');
define("LANG_CONFIRM_MODAL_DELETE_MSG", 'Opravdu chcete odstranit jazykovou mutaci?');
define("LANG_CONFIRM_MODAL_OK", 'Smazat');
define("LANG_CONFIRM_MODAL_CANCEL", 'Zpět');

// header
define("HEADER_SETTING_SAVED", 'Nastavení hlavičky uloženo');
define("HEADER_SETTING_COLOR", 'Barva textu hlavičky');
define("HEADER_HEIGHT", 'Výška hlavičky');
define("HEADER_HEIGHT_INFO", 'Uveďte jak vysoká má být hlavička [pixel]');
define("HEADER_BACKGROUND_COLOR", 'Barva pozadí hlavičky');
define("HEADER_TITLE", 'Nastavení hlavičky webu');
define("HEADER_CONTENT", 'Obsah hlavičky webu');
define("HEADER_INFO", 'Zde je možné nastavit statickou hlavičku webu, která bude zobrazena na každé stránce');
define("HEADER_SHOW_HEADER", 'Zobrazit hlavičku');
define("HEADER_WIDTH", 'Šířka hlavičky');
define("HEADER_WIDTH_INFO", 'Šířka hlavičky webu vůči oknu prohlížeče');
define("HEADER_CONTENT_TEXT", 'Obsah hlavičky webu');
define("HEADER_CONTENT_PICS", 'Obrázky hlavičky');
define("HEADER_PIC_CONTENT", 'Dostupné obrázky pro hlavičku webu');
define("HEADER_CONFIRM_DELETE_TITLE", 'Smazání obrázku hlavičky');
define("HEADER_CONFIRM_DELETE", 'Opravdu smazat obrázek hlavičky?');
define("HEADER_PIC_DELETE", 'Smazání obrázku hlavičky');
define("HEADER_CONFIRM_DELETE_CANCEL", 'Zpět');
define("HEADER_CONFIRM_DELETE_OK", 'Smazat');
define("HEADER_BUTTON_SAVE", 'Uložit hlavičku');

// nastavení číselníků
define("ENUM_TITLE", 'Nastavení číselníků v systému');
define("ENUM_TITLE_DESCRIPTION", 'Zde je možné spravovat číselníky, které jsou napříč celým systémem. Správu by měl dělat jen administrátor,
								protože změny mohou mít dopad na celou aplikaci.');
define("ENUM_TABLE_ENUM_NAME", 'Název číselníku');
define("ENUM_TABLE_FIELDS_PREVIEW", 'Položky číselníku');
define("ENUM_TABLE_ADD_NEW_ENUM", 'Přidat číselník');
define("ENUM_TABLE_ENUM_EDIT", 'Editace číselníku');
define("ENUM_TABLE_ENUM_DELETE", 'Smazat číselník');
define("ENUM_TABLE_ENUM_WARNING_TITLE", 'Smazání číseníku');
define("ENUM_TABLE_ENUM_WARNING_TEXT", 'Opravdu chcete smazat číselník?');
define("ENUM_EDIT_TITLE", 'Detail číselníku');
define("ENUM_EDIT_DESC", 'Zde můžete spravovat název číselníku v jazycích systému a následně jeho jednotlivé položky. Úpravy by měl dělat jen
						administrátor, protože změny mohou mít dopad na celou aplikaci.');
define("ENUM_EDIT_NAME", 'Název číselníku');
define("ENUM_EDIT_NAME_REQ", 'Název číselníků je povinná položka');
define("ENUM_EDIT_ITEMS_LABEL", 'Položky číselníku');
define("ENUM_EDIT_ITEM_EDIT", 'Editace položky číselníku');
define("ENUM_EDIT_ITEM_DELETE", 'Smazání položky číselníku');
define("ENUM_EDIT_ITEM_ADD", 'Přidání položky číselníku');
define("ENUM_EDIT_ITEM_DELETE_MSG", 'Opravdu chcete smazat položku číselníku');

define("ENUM_EDIT_ITEM_DESC",  'Editace položky číselníku ve všech jazykových mutacích');
define("ENUM_EDIT_ITEM_TITLE", 'Položka číselníku');
define("ENUM_EDIT_ITEM_NAME", 'Hodnota položky číselníku');
define("ENUM_EDIT_ITEM_NAME_REQ", 'Hodnota položky číselníku je povinná položka');
define("ENUM_EDIT_ITEM_SAVE", 'Uložení hodnoty číselníků proběhlo v pořádku');
define("ENUM_EDIT_ITEM_FAIL", 'Během ukládání hodnoty číselníku došlo k chybě.');
define("ENUM_DELETE_SUCCESS", 'Smazání číselníku proběhlo v pořádku.');
define("ENUM_DELETE_FAIL", 'Během mazání celého číselníku došlo k chybě. Pravděpodobně je hodnota číselníku někde využívána.');
define("ENUM_DELETE_ITEM_FAIL", 'Během mazání položky číselníku došlo k chybě. Pravděpodobně je hodnota číselníku někde využívána.');

// veterináři
define("VET_TITLE", 'Veterinář / Laboratoř');
define("VET_INFO", 'Nastavení jednotlivých veterinářů, případně veterinárních stanic, které je následně možné přiřazovat v aplikaci.');
define("VET_NAME", 'Jméno / název veterináře');
define("VET_ADDRESS", 'Adresa');
define("VET_ADD_VET", 'Přidat veterináře');
define("VET_DELETE_VET", 'Smazat veterináře');
define("VET_EDIT_VET", 'Editace veteriáře');
define("VET_ADD", 'Editace veteriáře');
define("VET_EDIT", 'Přidání veteriáře');

define("VET_EDIT_NAME", 'Jméno');
define("VET_EDIT_SURNAME", 'Přijmení');
define("VET_EDIT_PREFIX", 'Titul před');
define("VET_EDIT_SUFFIX", 'Titul za');
define("VET_EDIT_STREET", 'Ulice');
define("VET_EDIT_CITY", 'Město');
define("VET_EDIT_PSC", 'PSČ');
define("VET_EDIT_SAVE", 'Uložit');
define("VET_EDIT_BACK", 'Zpět');

define("VET_CONFIRM_DELETE_TITLE", 'Smazání veterináře / laboratoře');
define("VET_CONFIRM_DELETE", 'Opravdu smazat záznam?');
define("VET_CONFIRM_DELETE_DELETE", 'Smazat');
define("VET_CONFIRM_DELETE_CANCEL", 'Zpět');
define("VET_ADDED", 'Záznam byl v pořádku uložen.');
define("VET_ADD_FAILED", 'Záznam nebyl uložen.');

// common
define("UNSUPPORTED_UPLOAD_FORMAT", "Pokoušíte se nahrát nepodporovaný formát. Podporované formáty jsou %s.");

// tabulka psi
define("DOG_TABLE_FILTER_LABEL", 'Filtr tabulky');
define("DOG_TABLE_HEADER_ADD_DOG", 'nový');
define("DOG_TABLE_FILTER_TOGGLE", 'filtr');
define("DOG_TABLE_BTN_FILTER", 'filtrovat');
define("DOG_TABLE_BTN_CLEAR_FILTER", 'zrušit filtr');
define("DOG_TABLE_HEADER_NAME", 'jméno');
define("DOG_TABLE_HEADER_BREED", 'plemeno');
define("DOG_TABLE_HEADER_COLOR", 'barva');
define("DOG_TABLE_HEADER_SEX", 'pohlaví');
define("DOG_TABLE_HEADER_BIRT", 'dat. naroz.');
define("DOG_TABLE_HEADER_BREEDING", 'chovnost');
define("DOG_TABLE_HEADER_PROB_DKK", 'DKK');
define("DOG_TABLE_HEADER_PROB_DLK", 'DLK');
define("DOG_TABLE_HEADER_HEALTH", 'zdraví');
define("DOG_TABLE_HEADER_HEALTH_TEXT", 'zdraví výsledek');
define("DOG_TABLE_HEADER_MOTHER", 'matka');
define("DOG_TABLE_HEADER_FATHER", 'otec');
define("DOG_TABLE_HEADER_LAND", 'země');
define("DOG_TABLE_HEADER_BREEDER", 'chovatel');
define("DOG_TABLE_HEADER_EXAM", 'zkouška');
define("DOG_TABLE_HEADER_HEIGHT", 'výška');
define("DOG_TABLE_HEADER_FULLTEXT", 'fullextové hledání');
define("DOG_TABLE_HEADER_WRITE_NUMBER", 'číslo zápisu');
define("ASCENDING", 'vzestupně');
define("DESCENDING", 'sestupně');
define("DOG_TABLE_DOG_DELETED", 'Pes úspěšně smazán.');
define("DOG_TABLE_DOG_DELETED_FAILED", 'Při mazání psa došlo k chybě.');
define("DOG_TABLE_DOG_DETAIL", 'Detail psa');
define("DOG_TABLE_DOG_EDIT", 'Editace psa');
define("DOG_TABLE_DOG_ADD_SIBLING", 'Založit sourozence');
define("DOG_TABLE_DOG_DELETE", 'Smazat psa');
define("DOG_TABLE_DOG_DELETATION_TITLE", 'Smazání psa');
define("DOG_TABLE_DOG_DELETATION_CONFIRM", 'Opravdu chcete smazat psa?');
define("DOG_TABLE_DOG_DELETATION_CONFIRM_YES", 'Smazat');
define("DOG_TABLE_DOG_DELETATION_CONFIRM_CANCEL", 'Zpět');
define("DOG_TABLE_DOG_ACTION_NOT_ALLOWED", 'K požadované akci nemáte dostatečná oprávnění!');
define("DOG_TABLE_DOG_YEAR_FROM", 'od');
define("DOG_TABLE_LAST_14_DAYS", 'posledních 14 dnů');
define("DOG_TABLE_RECORD_COUNT", 'Počet záznamů:');
define("DOG_TABLE_RECORD_COUNT_PAGES", 'stran/y:');

define("DOG_FORM_NAME_PREFIX", 'Tituly před jménem');
define("DOG_FORM_NAME", 'Jméno');
define("DOG_FORM_NAME_MANDATORY", 'Jméno je povinná položka');
define("DOG_FORM_NAME_SUFFIX", 'Tituly za jménem');
define("DOG_FORM_BREED", 'Plemeno');
define("DOG_FORM_FUR", 'Barva srsti');
define("DOG_FORM_FUR_COLOUR", 'Barva');
define("DOG_FORM_FUR_COM", 'Komentář');
define("DOG_FORM_SEX", 'Pohlaví');
define("DOG_FORM_BIRT", 'Narození');
define("DOG_FORM_HEIGHT", 'Výška');
define("DOG_FORM_WEIGHT", 'Váha');
define("DOG_FORM_DEAD", 'Datum úmrtí');
define("DOG_FORM_DEAD_COM", 'Úmrtí komentář');
define("DOG_FORM_NO_OF_REC", 'Číslo zápisu');
define("DOG_FORM_NO_OF_REC2", 'Další číslo zápisu');
define("DOG_FORM_NO_OF_TATTOO", 'Tetování');
define("DOG_FORM_NO_OF_CHIP", 'Čip');
define("DOG_FORM_BON", 'Svod');
define("DOG_FORM_BON_DATE", 'Datum bonitace');
define("DOG_FORM_BREEDING", 'Chovnost');
define("DOG_FORM_BREEDING_COM", 'CZDR');
define("DOG_FORM_BOLOCKS", 'Varlata');
define("DOG_FORM_CHEW", 'Skus');
define("DOG_FORM_TEETH_COM", 'Zuby');
define("DOG_FORM_HEALTH_COM", 'Poznámka administrátora');
define("DOG_FORM_PICS", 'Obrázky');
define("DOG_FORM_FILES", 'Ostatní soubory');
define("BONITACNI_POSUDEK", 'Dokument');
define("DOG_FORM_HEADER", 'Detaily psa');
define("DOG_FORM_PIC_UPLOAD", 'Nahrát obrázky');
define("DOG_FORM_PIC_UPLOAD_FILE", 'Soubory - dokumenty');
define("DOG_FORM_ADDED", 'Data byla v pořádku uložena.');
define("DOG_FORM_ADD_FAILED", 'Změny nebyly zpracovány.');
define("DOG_FORM_PIC_DEFAULT", 'Nastavit obrázek jako výchozí');
define("DOG_FORM_PIC_DELETE", 'Smazat obrázek');
define("DOG_FORM_PIC_DELETE_TITLE", 'Smazání položky na kartě psa');
define("DOG_FORM_PIC_DELETE_INFO", 'Opravdu smazat položku?');
define("DOG_FORM_FILE_DELETE", 'Smazat soubor');
define("DOG_FORM_OWNER_DELETE", 'Smazat majitele');
define("DOG_FORM_BREEDER_DELETE", 'Smazat chovatele');
define("DOG_FORM_HEALTH_COMMENT", 'Komentář');
define("DOG_FORM_HEALTH_SUMMARY", 'Výsledek');
define("DOG_FORM_HEALTH_DATE", 'Datum');
define("DOG_FORM_HEALTH_VET", 'Veterinář');
define("DOG_FORM_HEALTH", 'Zdraví');
define("DOG_FORM_HEIGHT_NUMBER", 'Výška psa musí být číslo na dvě desetinná místa (v cm), oddělovačem je tečka (.)!');
define("DOG_FORM_WEIGHT_NUMBER", 'Váha psa musí být číslo na dvě desetinná místa (v kg), oddělovačem je tečka (.)!');
define("DOG_FORM_LITTER_CHECK", 'Kontrola vrhu');

define("DOG_FORM_TITLES", 'Výstavní ocenění');    // Tituly
define("DOG_FORM_BON_TEXT", 'Bonitační posudek');
define("DOG_FORM_SHOWS_TEXT", 'Výsledky výstav');  // Výstavní ocenění
define("DOG_FORM_SHOWS_NEXT_TEXT", 'Další výstavní ocenění');
define("DOG_FORM_SHOWS_EXAMS", 'Zkoušky');
define("DOG_FORM_SHOWS_EXAMS_NEXT", 'Zkoušky složené');
define("DOG_FORM_SHOWS_RACES", 'Závody');
define("DOG_FORM_HIDE_DESCENDANTS", 'Skrýt potomky');
define("DOG_FORM_HIDE_SIBLINGS", 'Skrýt sourozence');
define("DOG_FORM_HIDE_DOG_CARD", 'Skrýt celou kartu');
define("DOG_FORM_SHOWS_NOTE", 'Poznámka');
define("DOG_FORM_BREEDER", 'Chovatel');
define("DOG_FORM_MALE", 'Otec');
define("DOG_FORM_FEMALE", 'Matka');
define("DOG_FORM_DESCENDANTS", 'Potomci');
define("DOG_FORM_SIBLINGS", 'Sourozenci');
define("DOG_FORM_PEDIGREE", 'Rodokmen');
define("DOG_FORM_PEDIGREE_COEF", 'Koeficient příbuznosti');
define("DOG_FORM_PEDIGREE_COEF_NOT_FULL", 'neúplný (méně než 5 generací)');
define("DOG_FORM_PEDIGREE_GENERATION_NO", 'generace');
define("DOG_FORM_PEDIGREE_GENERATIONS_NO", 'generací');
define("DOG_FORM_PEDIGREE_ADD_MISSING", 'Přidat psa');

define("DOG_FORM_OWNERS", 'Majitel/é');
define("DOG_FORM_PREVIOUS_OWNERS", 'Předchozí majitelé');
define("DOG_FORM_OWNERS_SELECT_TEXT", 'Vyberte možnosti');
define("DOG_FORM_OWNERS_SELECT_NO_MATCH", 'Žádná shoda');
define("DOG_FORM_MID_OID_FAILED_TITLE", 'Referenční chyba genealogie');
define("DOG_FORM_MID_OID_FAILED_MESSAGE", 'Matka nebo otec psa neodpovídají pohlaví pro matku/otce.');
define("DOG_FORM_NOT_TRUE_OWNER", 'Nemůžete editovat toho psa, nejste jeho majitelem ani chovatelem!');
define("DOG_FORM_REQUEST_NOT_EXISTS", 'Neplatný URL požadavek!');

define("MATING_FORM_CLUB", 'Krycí list');
define("MATING_FORM_FID", 'Pes');
define("MATING_FORM_MID", 'Fena');
define("MATING_FORM_SAVE", 'Hlášení vrhu');
define("MATING_FORM_SAVE1", 'Generovat krycí list');
define("MATING_FORM_SAVE2", 'Přihláška k zápisu vrhu');
define("MATING_FORM_PICK_MALE", 'Vyberte psa');
define("MATING_FORM_PICK_FEMALE", 'Vyberte fenu');
define("MATING_FORM_NO_MATCH", 'Shoda nenalezena');
define("MATING_FORM_COVERAGE", 'KRYCÍ LIST - hlášení o krytí');
define("MATING_FORM_TYPE", 'typ chovu: řízený');
define("MATING_FORM_COVERAGE_DETAIL", '- pro majitele krycího psa');
define("MATING_FORM_BON_CODE", 'Bonitační kód');
define("MATING_FORM_PLACE_DETAIL", 'Krytí bylo uskutečněno');
define("MATING_FORM_PLACE_DETAIL_DAY", 'dne');
define("MATING_FORM_PLACE_DETAIL_DAY_REPEAT", 'opakováno dne');
define("MATING_FORM_ESTIMATE_DATE", 'Datum vrhu');
define("MATING_FORM_RULES", 'Máte zájem o uvedení vrhu na webu?');
define("MATING_FORM_NOTE", 'Poznámka (inseminace, náhrada za krytí, apod.)');
define("MATING_FORM_NOTE_2", "Majitel krycího psa je povinen do 7 dnů po krytí odeslat vyplněný Krycí list – hlášení o krytí správci plemenné knihy. Při zahraničním krytí odesílá majitel feny.");
define("MATING_FORM_OWNER", "");
define("MATING_FORM_INFO_BREEDER", "Chovatelé si napíší o složky ke štěňatům paní Hradecké na email VendulaHradeck@email.cz");

define("MATING_FORM_DATE_SHORT", 'Datum');
define("MATING_FORM_DATE", 'Datum krytí');
define("MATING_FORM_PLACE", 'Místo krytí');
define("MATING_FORM_PLACE2", 'Místo');
define("MATING_FORM_INSEMINATION", 'Inseminace');
define("MATING_FORM_AGREEMENT", 'Dohoda');
define("MATING_FORM_MALE_OWNER", 'Jméno a adresa majitele psa');
define("MATING_FORM_FEMALE_OWNER", 'Jméno a adresa majitele feny');
define("DOG_FORM_NAME_FEMALE", 'Jméno feny');
define("DOG_FORM_NAME_MALE", 'Jméno psa');
define("MATING_FORM_DISCLAIMER", 'Podpisem stvrzuji, že pes je přeregistrován v Plemenné knize č.1 – ČMKU');
define("MATING_FORM_SIGNATURE", 'podpis');
define("MATING_FORM_OVERAGAIN", 'Začít znovu');
define("MATING_FORM_GENERATE", 'Generovat PDF');

// user view
define("USER_VIEW_OWNER", 'Je uveden/a jako vlastník u');
define("USER_VIEW_OWNER_PREVIOUS", 'Je uveden/a jako dřívější vlastník u');
define("USER_VIEW_BREEDER", 'Odchoval/a');

// rozhodčí
define("REFEREE_MENU", 'Rozhodčí');
define("REFEREE_TITLE", 'Rozhodčí');
define("REFEREE_INFO", 'Pořizováni a editace rozhodčích, které je možné následně používat v aplikaci.');
define("REFEREE_NAME", 'Jméno');
define("REFEREE_ADDRESS", 'Adresa');
define("REFEREE_ADD", 'Přidat rozhodčího');
define("REFEREE_EDIT", 'Editace rozhodčího');
define("REFEREE_DELETE", 'Smazání rozhodčího');
define("REFEREE_DELETE_MODAL_HEADER", 'Smazání rozhodčího');
define("REFEREE_DELETE_MODAL_QUESTION", 'Opravdu smazat rozhodčího?');
define("REFEREE_ADDED", 'Rozhodčí úspěšně vložen');
define("REFEREE_ADDED_FAILED", 'Při uložení došlo k chybě - záznam nebyl uložen');

// výstavy
define("SHOW_MENU", 'Výstavy');
define("SHOW_TITLE", 'Výstavy');
define("SHOW_INFO", 'Zde je možné založit výstavu, editovat ji nebo zapsat výsledky');
define("SHOW_TABLE_TYPE", 'Typ');
define("SHOW_TABLE_DATE", 'Datum');
define("SHOW_TABLE_NAME", 'Název');
define("SHOW_TABLE_PLACE", 'Místo');
define("SHOW_TABLE_DONE", 'Hotovo');
define("SHOW_TABLE_REFEREE", 'Rozhodčí');
define("SHOW_TABLE_ADD_SHOW", 'Přidat výstavu');
define("SHOW_TABLE_DETAIL", 'Detail výstavy');
define("SHOW_TABLE_EDIT", 'Editace výstavy');
define("SHOW_TABLE_DELETE", 'Smazání výstavy');
define("SHOW_TABLE_DELETE_SHOW_HEADER", 'Smazání výstavy');
define("SHOW_TABLE_DELETE_SHOW_INFO", 'Opravdu chcete smazat výstavu?');

define("SHOW_FORM_ADD", 'Přidání nové výstavy');
define("SHOW_FORM_EDIT", 'Editace výstavy');
define("SHOW_FORM_NEW_ADDED", 'Výstava byla přidána');
define("SHOW_FORM_NEW_FAILED", 'Při ukládání výstavy došlo k chybě');
define("SHOW_DELETED", 'Výstava smazána');
define("SHOW_DELETED_FAILED", 'Během mazání výstavy nastala chyba');
define("SHOW_TABLE_DATE_VALIDATION", 'Datum výstavy je povinná položka!');
define("SHOW_TABLE_NAME_VALIDATION", 'Název výstavy je povinná položka!');

define("SHOW_DOG_FORM_DOG", 'Pes');
define("SHOW_DOG_FORM_CLASS", 'Třída');
define("SHOW_DOG_FORM_REPUTATION", 'Ocenění');
define("SHOW_DOG_FORM_DOG_ORDER", 'Pořadí');
define("SHOW_DOG_FORM_DOG_TITLES", 'Tituly');
define("SHOW_DOG_FORM_DOG_TITLES_ADDON", 'Tituly dodatek');
define("SHOW_REFEREE_FORM_REFEREE", 'Rozhodčí');
define("SHOW_REFEREE_FORM_CLASS", 'Třída');
define("SHOW_REFEREE_FORM_BREED", 'Plemeno');

define("SHOW_DONE", 'Ano');
define("SHOW_UNDONE", 'Ne');
define("REFEREE_EMPTY_SAVE", 'Žádná data k uložení. Prosím, vyberte rozhodčího, plemeno a třídu!');
define("REFEREE_SAVED", 'Rozhodčí výstavy uložen');
define("REFEREE_DELETED", 'Položka rozhodčího smazána');
define("REFEREE_SAVED_FAILED", 'Chyba během zpracování');
define("SHOW_ITEM_DELETE_HEADER", 'Smazání položky');
define("SHOW_ITEM_DELETE_QUESTION", 'Opravdu smazat položku?');
define("SHOW_DOG_SAVED", 'Pes výstavy uložen');
define("SHOW_DOG_DELETED", 'Položka psa smazána');
define("SHOW_DOG_SAVED_FAILED", 'Chyba během zpracování');
define("DOG_EMPTY_SAVE", 'Žádná data k uložení.');
define("SHOW_FRONTEND_YEAR", 'Rok');

define("KINSHIP_VERIFICATION", "Zjišťoval jsem příbuznost");
define("KINSHIP_VERIFICATION_AND", "a");
define("KINSHIP_VERIFICATION_CROSS", "V úvahu se bere i křížení předků");

define("AWAITING_CHANGES", "Změny čekající na schválení");
define("AWAITING_CHANGES_DOG", "Pes");
define("AWAITING_CHANGES_USER", "Uživatel");
define("AWAITING_CHANGES_TIMESTAMP", "Datum požadavku");
define("AWAITING_CHANGES_ORIGINAL_VALUE", "Původní hodnota");
define("AWAITING_CHANGES_WANTED_VALUE", "Požadovaná hodnota");
define("AWAITING_CHANGES_CONFIRM", "Schválit");
define("AWAITING_CHANGES_DECLINE", "Zamítnout");
define("AWAITING_CHANGES_DECLINE_MODAL_TITLE", "Zamítnout změnu");
define("AWAITING_CHANGES_DECLINE_MODAL_BODY", "Přejete si zamítnout tuto změnu?");
define("AWAITING_CHANGES_WHAT", "Položka změny");
define("AWAITING_CHANGES_SENT_TO_APPROVAL", "Změny byly odeslány ke schválení");
define("AWAITING_EMAIL_USER_DOG_SUBJECT", "Právě jste provedl(a) změny na kartě psa");
define("AWAITING_EMAIL_USER_DOG_BODY", "Dobrý den, <br />právě byly provedeny změny na kartě psa  %s. Změny budou vidět až ve chvíli schválení administrátorem.");
define("AWAITING_EMAIL_ADMIN_DOG_SUBJECT", "Byly provedeny změny na kartě psa");
define("AWAITING_EMAIL_ADMIN_DOG_BODY", "Dobrý den, <br />na kartě psa %s byly provedeny změny. Pro schválení nebo zamítnutí změn se, prosím, přihlaste do administrace.");
define("AWAITING_CHANGE_CHANGE_ERR", "Během zpracování požadavku došlo k chybě, prosím, opakujte později.");
define("AWAITING_CHANGE_CHANGE_DECLINE", "Požadavek na změnu byl zamítnut.");
define("AWAITING_CHANGE_CHANGE_ACCEPT", "Změna byla zpracována.");
define("AWAITING_CHANGE_ACCEPTED", "Schválené změny");
define("AWAITING_CHANGE_DECLINED", "Zamítnuté změny");
define("AWAITING_CHANGE_PROCEEDED_BY", "Zpracováno od");
define("AWAITING_CHANGE_PROCEEDED_WHEN", "Schváleno");
define("AWAITING_CHANGE_PROCEEDED_OK_SUBJECT", "Váš požadavek na změnu byl schválen");
define("AWAITING_CHANGE_PROCEEDED_OK_BODY", "Dobrý den, <br />Váš požadavek na změnu byl schválen. Prosím, proveďte kontrolu zde %s.");
define("AWAITING_CHANGE_PROCEEDED_DECLINE_SUBJECT", "Váš požadavek na změnu byl zamítnut");
define("AWAITING_CHANGE_PROCEEDED_DECLINE_BODY", "Dobrý den, <br />Váš požadavek na změnu byl zamítnut. Karta psa %s.");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL", "Váš pes byl v pořádku založen. Pro jeho následné zobrazení musí být schválen administrátorem.");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_SUBJECT_USER", "Vložení nového psa");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_BODY_USER", "Dobrý den, <br /> do systému byl zaveden nový pes. Prosím vyčketje na jeho schválení.");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_SUBJECT_ADMIN", "Založení nového psa");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_BODY_ADMIN", "Dobrý den, <br />byla založena nová karta psa %s. Pro schválení nebo zamítnutí se, prosím, přihlaste do administrace.");

define("LITTER_APPLICATION", "Přihláška vrhu");
define("LITTER_APPLICATION_DETAIL_TITLE", "Klub chovatelů collií a sheltií");
define("LITTER_APPLICATION_DETAIL_APPLICATION", "PŘIHLÁŠKA K ZÁPISU VRHU ŠTĚŇAT KABT");
define("LITTER_APPLICATION_DETAIL_APPLICATION_COUNTER_PREFIX", "KL-");

define("LITTER_APPLICATION_DETAIL_STATION_TITLE", "Název chovatelské stanice");
define("LITTER_APPLICATION_DETAIL_DOG_TITLES", "výstavní ocenění a dosažené tituly");
define("LITTER_APPLICATION_DETAIL_CARD_NO", "číslo průkazu vč. zkratky plemenné knihy");
define("LITTER_APPLICATION_DETAIL_FUR_TYPE", "druh srsti");
define("LITTER_APPLICATION_DETAIL_BONITATION", "bonitace");
define("LITTER_APPLICATION_DETAIL_BREEDER_ADDRESS", "Jméno a adresa chovatele");
define("LITTER_APPLICATION_DETAIL_PUPPIES_BIRTHDAY", "Datum narození štěňat");
define("LITTER_APPLICATION_DETAIL_PUPPIES_INFO", "Údaje o počtu narozených štěňat");
define("LITTER_APPLICATION_DETAIL_PUPPIES_COUNT", "Kolik štěňat fena porodila");
define("LITTER_APPLICATION_DETAIL_PUPPIES_DEATH_COUNT", "Kolik z nich bylo mrtvých");
define("LITTER_APPLICATION_DETAIL_PUPPIES_FORCE_DEATH_COUNT", "Kolik bylo usmrceno");
define("LITTER_APPLICATION_DETAIL_PUPPIES_APPLICATION_FOR", "Přihlašuji k zápisu");
define("LITTER_APPLICATION_DETAIL_PUPPIES_TO_NURSE", "Kolik odevzdáno kojné");
define("LITTER_APPLICATION_DETAIL_PUPPIES_DEATH_BEFORE_APPLICATION", "Kolik zahynulo před zápisem");
define("LITTER_APPLICATION_DETAIL_PUPPIES_MALES", "psů");
define("LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES", "fen");
define("LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW", "Pohlaví nezjištěno");
define("LITTER_APPLICATION_DETAIL_TEXT_1", "<b>Vyplní ČMKU</b><br />Číslo zápisu<br />Tetovací číslo");
define("LITTER_APPLICATION_DETAIL_TEXT_2", "<b>Číslo mikročipu</b><br />(viz 2. strana)");
define("LITTER_APPLICATION_DETAIL_TEXT_3", "<b>Jméno štěňat</b>");
define("LITTER_APPLICATION_DETAIL_TEXT_4", "<b>Pohlaví</b>");
define("LITTER_APPLICATION_DETAIL_TEXT_5", "<b>Barva</b>");
define("LITTER_APPLICATION_DETAIL_TEXT_6", "<br /><br />
									Potvrzuji svým podpisem, že jsem chovatelem výše uvedených štěňat a že všechny údaje v přihlášce uvedené  jsou pravdivé.<br>
									*Podpisem stvrzuji souhlas s využitím uvedených údajů pro evidenci klubu v rámci genealogie Klubu.
									<br /><br />&nbsp;<br />");
define("LITTER_APPLICATION_DETAIL_TEXT_7", "....................................................................<br />Vlastnoruční  podpis  chovatele");
define("LITTER_APPLICATION_DETAIL_TEXT_8", "Použít pro nalepení samolepek s čárovým kódem čísla mikročipu.");
define("LITTER_APPLICATION_DETAIL_TEXT_9", "<b>Čipování provedl:</b><br />(razítko a podpis  pověřeného veterinárního lékaře)
									<br /><br /><br /><br /><br />
									....................................................................");
define("LITTER_APPLICATION_DETAIL_CONTROL", "Výsledek  tetování, čipování a  kontroly  vrhu");
define("LITTER_APPLICATION_DETAIL_INC", "K přihlášce připojuji");
define("LITTER_APPLICATION_DETAIL_INC_MATING_LIST", "2x krycí list");
define("LITTER_APPLICATION_DETAIL_INC_MATING_MALE_PREREG", "1x kopie PP psa s přeregistrací");
define("LITTER_APPLICATION_DETAIL_INC_MATING_FEMALE_PREREG", "1x kopie PP psa s přeregistrací");;
define("LITTER_APPLICATION_DETAIL_INC_MATING_FEES", "potvrzení o zaplacení poplatků");
define("LITTER_APPLICATION_DETAIL_INC_MATING_PHOTO", "fotokopie CHS (jen u prvního vrhu)");
define("LITTER_APPLICATION_DETAIL_INC_MATING_TITLES", "fotokopie  titulů");
define("LITTER_APPLICATION_DETAIL_IN", "V");
define("LITTER_APPLICATION_DETAIL_NO", "Číslo zápisu");
define("LITTER_APPLICATION_DETAIL_CHIP_BLISTER", "Samolepka s čárovým kódem čísla mikročipu");
define("LITTER_APPLICATION_DETAIL_LITTER_DATE_REQ", "Datum krytí je povinná položka!");
define("LITTER_APPLICATION_SAVED", "Přihláška vrhu byla v pořádku uložena");
define("LITTER_APPLICATION_SAVE_FAILED", "Během ukládání příhlášky vrhu došlo k chybě. Prosím, zkontrolujte data a akci opakujte.");
define("LITTER_APPLICATION_DOES_NOT_EXIST", "Přihláška vrhu s číslem %i neexistuje!");
define("LITTER_APPLICATION_ADD_NEW", "Zadat další přihlášku vrhu");
define("LITTER_APPLICATION_GENERATE_PDF", "Uložit PDF");
define("LITTER_APPLICATION_SAVE_DATE", "Datum vložení");
define("LITTER_APPLICATION_SAVE_REWRITTEN", "Zavedeno");
define("LITTER_APPLICATION_SAVE_UNREWRITTEN", "Nezavedeno");
define("LITTER_APPLICATION_SAVE_ALLREWRITTEN", "Vše");
define("LITTER_APPLICATION_DELETE", "Smazat");
define("LITTER_APPLICATION_DELETE_TITLE", "Smazání příhlášky vrhu");
define("LITTER_APPLICATION_DELETE_INFO", "Přejete si smazat prihlášku vrhu?");
define("LITTER_APPLICATION_DELETED", "Přihláška vrhu byla smzána");
define("LITTER_APPLICATION_DELETED_FAILED", "Přihlášku vrhu se nepodařilo smazat");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS", "Zavést potomky do databáze");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS_OK", "Potomci byly zavedeni");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS_FAILED", "Během zavádění potomků došlo k chybě");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS_ALREADY_IN", "POZOR - tato přihláška vrhu už byla zpracována, novým zavedením vzniknou duplicity!");
define("LITTER_APPLICATION_REWRITE_TATTOO_NO", "Tetovací číslo");
define("LITTER_APPLICATION_REWRITE_CHIP_NO", "Číslo mikročipu");
define("LITTER_APPLICATION_REWRITE_PUPPY_NAME", "Jméno štěňat");
define("LITTER_APPLICATION_REWRITE_PUPPIES", "Založit potomky do databáze");
define("LITTER_APPLICATION_REWRITE_PUPPIES_FUR", "Srst");
define("LITTER_APPLICATION_REWRITE_DOES_NOT_EXIST", "Číslo přihlášky vrhu neexistuje");
define("LITTER_APPLICATION_CREATE_SUBJECT", "Byla založena nová přihlášk vrhu");
define("LITTER_APPLICATION_CREATE_BODY", "Byla založena nová přihlášk vrhu. Viz přiložený formulář <br /><br /> %s");
define("LITTER_APPLICATION_FOR", "hlášení o vrhu");
define("LITTER_APPLICATION_FOR_WHO", "- pro majitele feny");
define("LITTER_APPLICATION_BREEDING", "Chovatelská stanice");
define("LITTER_APPLICATION_MALE_NAME", "Jméno krycího psa");
define("LITTER_APPLICATION_RECORD_NUM_FORM", "číslo zápisu: CMKU/");
define("LITTER_APPLICATION_RECORD_NUM", "číslo zápisu");
define("LITTER_APPLICATION_PLACE", "Krytí bylo uskutečněno");
define("LITTER_APPLICATION_BIRTH", "Datum porodu");
define("LITTER_APPLICATION_DOG_LIVE", "Počet živě narozených štěňat");
define("LITTER_APPLICATION_DOG_LIVE_MALE", "z toho psi");
define("LITTER_APPLICATION_DOG_LIVE_FEMALE", "feny");
define("LITTER_APPLICATION_DOG_DEATH", "Počet mrtvě nar., do 7 dnů po nar. uhynulých nebo utracených štěňat");
define("LITTER_APPLICATION_PUPPIES_DETAILS", "Barvy štěňat, další poznámky");
define("LITTER_APPLICATION_OWNWER_MALE", "Jméno a adresa majitele krycího psa");
define("LITTER_APPLICATION_REVIEW", "Majitel feny je povinen do 10 dnů po vrhu odeslat hlášení o vrhu poradci chovu na email poradcechovu@seznam.cz.");
define("LITTER_APPLICATION_EDIT", "Editace vrhu");
define("LITTER_APPLICATION_MID_OID_FAILED_TITLE", 'Referenční chyba genealogie. Pro psa s ID %d neodpovída pohlaví pro otce/matku!');
define("MATING_LITTER_DOG_DATE", "dat. nar.");

define("PUPPY_TABLE_TERM", "Termín");
define("PUPPY_TABLE_DETAILS", "Podrobnosti");
define("PUPPY_TABLE_CONTACT", "Kontakt");
define("PUPPY_ADD_NEW", "Nový");
define("PUPPY_ADD_OK", "Záznam byl v pořádku vložen");
define("PUPPY_ADD_FAILED", "Během ukládání záznamu došlo k chybě. Opakujte akci prosím později");
define("PUPPY_EDIT", "Ediatce");
define("PUPPY_DELETE", "Smazání");
define("PUPPY_DELETE_TITLE", "Smazání inzerátu štěňat");
define("PUPPY_DELETE_BODY", "Opravdu chcete smazat záznam?");

define("CHS_TITLE", 'Chovatelské stanice');