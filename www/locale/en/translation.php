<?php

// formulář login do administrace
define("ADMIN_LOGIN_HEADER", 'Login');
define("ADMIN_LOGIN_EMAIL", 'Email');
define("ADMIN_LOGIN_EMAIL_REQ", 'Please fill your login.');
define("ADMIN_LOGIN_PASS", 'Password');
define("ADMIN_LOGIN_PASS_REQ", 'Please fill your password');
define("ADMIN_LOGIN_LANG", 'Language of inteface');
define("ADMIN_LOGIN_REMEMBER_ME", 'Remember me');
define("ADMIN_LOGIN_FORGOTTEN_PASSWORD", 'Forgotten password?');
define("ADMIN_LOGIN_RESET_PASSWORD", 'Reset password');
define("ADMIN_LOGIN_RESET_PASSWORD_EMAIL_FAIL", 'Email does not exist in database.');
define("ADMIN_LOGIN_RESET_SUCCESS", 'Password has benn successfully changed and send to your email.');
define("ADMIN_LOGIN_RESET_FAILED", 'Password reset failed. Try again later.');
define("ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_SUBJECT", 'Password changed in %s');
define("ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_SUBJECT_PLAIN", 'Password changed');
define("ADMIN_LOGIN_PASSWORD_CHANGED_EMAIL_BODY", "Hello, <br /> your password has been changed in %s. <br /><br /> New password is '%s'.<br /><br />");
define("ADMIN_LOGIN_LOGIN", 'Login');
define("ADMIN_LOGIN_EMAIL_PLACEHOLDER", 'Email');
define("ADMIN_LOGIN_PASS_PLACEHOLDER", 'Password');
define("ADMIN_LOGIN_SUCCESS", 'You are successfully logged in');
define("ADMIN_LOGIN_SUCCESS_NO_PRIVACY", 'Still has not been agreed with GRPR regulation. Please, consider this agreement for full service of this website.');
define("ADMIN_LOGIN_FAILED", 'Incorrect login name or password');
define("ADMIN_LOGIN_UNLOGGED", 'You have been successfully logged out.');

// položky menu
define("MENU_TITLE", 'Main menu');
define("MENU_DASHBOARD", 'Dashboard');
define("MENU_HEADER", 'Page header');
define("MENU_LOGO", 'Slider');
define("MENU_MENU", 'Page menu');
define("MENU_BLOCK", 'Page content blocks');
define("MENU_MENU_BLOCK", 'Menu vs blocks');
define("MENU_CONTACT_FORM", 'Contact info / form');
define("MENU_FOOTER", 'Page footer');
define("MENU_USERS", 'Users');
define("MENU_SETTINGS", 'Page setting');
define("MENU_ENUMERATION", 'Enumerations');
define("MENU_Coverage", 'Coverage applications');
define("MENU_VETS", 'Vets / Labs');
define("MENU_LANG", 'Language setting');
define("MENU_LOGOUT", 'Logout');
define("MENU_FRONTEND", 'Back to web');

// admin - users list
define("USER_TITLE", 'Users');
define("USER_INFO_TEXT", "Here you can manage users and their roles. Login name is their email addresses. It assures the unique user name (login).
						Please remember that these changes are undone!");
define("USER_TABLE_HEADER_LOGIN", "Login");
define("USER_TABLE_HEADER_ROLE", "Role");
define("USER_TABLE_HEADER_ACTIVE", "Active");
define("USER_TABLE_HEADER_LAST_LOGIN", "Last login");
define("USER_TABLE_HEADER_REGISTERED_DATE", "Date of registration");
define("USER_DELETED", "User has been deleted");
define("USER_ADDED", "User added");
define("USER_EDITED", "User data updated");
define("USER_EMAIL_ALREADY_EXISTS", "User email already exists in the database");
define("USER_DELETED_FAILED", "Users has not been deleted.");
define("USER_DELETE", 'Delete user');
define("USER_EDIT", 'Edit user');
define("USER_ADD_USER", 'Add new user');
define("USER_CONFIRM_DELETE_TITLE", 'User deletions');
define("USER_CONFIRM_DELETE", 'Really delete user?');
define("USER_CONFIRM_DELETE_CANCEL", 'Cancel');
define("USER_CONFIRM_DELETE_OK", 'Delete');
define("USER_ERROR_ACTIVE_SWITCH", 'Communication with server broken, please repeat action later.');
define("USER_CREATED_MAIL_SUBJECT", 'New user registration');
define("USER_CREATED_MAIL_BODY", "Hello there, <br /><br /> your registration on server %s has been successful.<br /> Your credentials are: <br /> login: %s <br /> password: %s <br /><br />");
define("USER_SEARCH_FIELD", "Fulltext searching in users");
define("USER_SEARCH_BUTTON", "Search");
define("USER_DELETE_FAIL_BREEDER",  "User is as a breeder with these dogs: ");
define("USER_DELETE_FAIL_OWNER",  "User is as a owner with these dogs: ");
define("USER_REFERENCES", 'User references');
define("USER_REFERENCES_INFO",  "Here are all references to the database where user is in use. For user deletation you need do discard (delete) all references. <strong>ATTENTION</strong> can't be undone!");
define("USER_REFERENCES_OWNER",  "Here are listed dogs where is user use as <strong>owner</strong>");
define("USER_REFERENCES_TEMP_OWNER",  "Here are listed dogs where is user use as <strong>temporary owner</strong>");
define("USER_REFERENCES_BREEDER",  "Here are listed dogs where is user use as <strong>breeder</strong>");
define("USER_REFERENCES_TEMP_BREEDER",  "Here are listed dogs where is user use as <strong>temporary breeder</strong>");
define("USER_REFERENCES_PUPPY",  "User has these adds listed");
define("USER_REFERENCES_LITTER_APP",  "Here is user listed as <strong>'dog's female owner'</strong>");
define("USER_REFERENCES_REQUESTED_CHANGES",  "Requested user changes");
define("USER_REFERENCES_REQUESTED_CHANGES_STATE",  "State");
define("USER_REFERENCES_CORRUPT_DOG_REF",  "<i>Invalid dog reference</i>");

// admin - user edit
define("USER_EDIT_FORM_ADD", ' Addition the new user');
define("USER_EDIT_FORM_EDIT", 'Editing of user %s');
define("USER_EDIT_EMAIL_LABEL", 'Email');
define("USER_EDIT_EMAIL_REQ", 'Email field is mandatory!');
define("USER_EDIT_EMAIL_VALIDATION", 'Please fill the valid email address!');
define("USER_EDIT_PASS_LABEL", 'Password');
define("USER_EDIT_PASS_REQ", 'Password field is mandatory!');
define("USER_EDIT_PASS_AGAIN_LABEL", 'Password confirmation');
define("USER_EDIT_PASS_AGAIN_REQ", 'Password confirmation is mandatory!');
define("USER_EDIT_ROLE_LABEL", 'Role');
define("USER_EDIT_ACTIVE_LABEL", 'Active');
define("USER_EDIT_SAVE_BTN_LABEL", 'Save');
define("USER_EDIT_SITEMAP_BTN_LABEL", 'Generate sitemap files');
define("USER_EDIT_SITEMAP_AVAIL", 'Available sitemap.xml file');
define("USER_EDIT_SITEMAP_GENERATION_DONE", 'Sitemap.xml file has been successfully generated');
define("USER_EDIT_SITEMAP_BTN_INFO", 'It will generate file(s) sitemap for search engines. For each language mutation will be generate one sitemap file.');
define("USER_EDIT_BACK_BTN_LABEL", 'Cancel');
define("USER_EDIT_SAVE_FAILED", 'Changes has not been processed, please repeat action later');
// specials
define("USER_EDIT_TITLE_BEFORE_LABEL", 'Title before');
define("USER_EDIT_NAME_LABEL", 'Name');
define("USER_EDIT_NAME_LABEL_VALIDATION", 'Name is mandatory!');
define("USER_EDIT_SURNAME_LABEL", 'Surname');
define("USER_EDIT_SURNAME_LABEL_VALIDATION", 'Surname is mandatory!');
define("USER_EDIT_TITLE_AFTER_LABEL", 'Title after');
define("USER_EDIT_STREET_LABEL", 'Street');
define("USER_EDIT_CITY_LABEL", 'City');
define("USER_EDIT_ZIP_LABEL", 'Zip');
define("USER_EDIT_ZIP_VALIDATION", 'Zip should be a number.');
define("USER_EDIT_STATE_LABEL", 'State');
define("USER_EDIT_WEB_LABEL", 'WWW');
define("USER_EDIT_WEB_LABEL_DET", '(complete URL begins with http)');
define("USER_EDIT_PHONE_LABEL", 'Phone');
define("USER_EDIT_FAX_LABEL", 'Fax');
define("USER_EDIT_STATION_LABEL", 'Cattery');
define("USER_EDIT_STATION_LABEL_SHORT", 'Cattery');
define("USER_EDIT_BREED_LABEL", 'Breed');
define("USER_EDIT_SHARING_LABEL", 'Sharing');
define("USER_EDIT_CLUB", 'Club');
define("USER_EDIT_CLUB_NO", 'Club member number');
define("USER_EDIT_NEWS", 'I agree that my email address can be used for newsletter.');
define("USER_EDIT_PRIVACY", 'I agree with the processing of personal data within the GDPR.');
define("USER_EDIT_PRIVACY_VALIDATION", "It is neccasary to agree with GDPR's data processing.");
define("USER_EDIT_PRIVACY_URL", "More information.");

// user roles - select
define("USER_ROLE_LAYOUT_CHANGER", "Full access");
define("USER_ROLE_CONTENT_CHANGER", "User can change content");
define("USER_ROLE_GUEST", "Host");
define("USER_REGISTERED", "Registered");
define("USER_OWNER", "Owner");
define("USER_EDITOR", "Poradce chovu");
define("USER_ROLE_ADMINISTRATOR", "Administrator");

// webconfig
define("WEBCONFIG_WEBMUTATION", "Language mutation");
define("WEBCONFIG_WEBMUTATION_INFO", "Current edition will be used for current language mutation. If you have more language mutations is necessary to to do it for all mutations.");
define("WEBCONFIG_TITLE", "Page config");
define("WEBCONFIG_TITLE_INFO", "Here you can configure the web layout, width or Google analytics code.");
define("WEBCONFIG_WEB_NAME", "Web title");
define("WEBCONFIG_WEB_NAME_INFO", "This title will be displayed in the main window in browser. Is important for search engines.");
define("WEBCONFIG_WEB_KEYWORDS", "Keywords");
define("WEBCONFIG_WEB_KEYWORDS_INFO", "Each keyword separate with comma (,).
										<b>Note:</b> keywords in tag keywords are ignorated by robots but even though is better to fill them.");

define("WEBCONFIG_WEB_WIDTH", "Page width");
define("WEBCONFIG_WEB_WIDTH_INFO", "It sets page width in the browser. This item does not have impact on web responsivity.");
define("WEBCONFIG_WEB_FAVICON", "Page icon");
define("WEBCONFIG_WEB_FAVICON_INFO", "Icon will be displayed on address bar of web browser or favorite list.
										Icon has to have special rules. Type of image should be ico and should have 16x16 pixel as resolution.");
define("WEBCONFIG_WEB_FAVICON_FORMAT", "Icon ust be ico (format ICO)!");
define("WEBCONFIG_WEB_GOOGLE_ANALYTICS", "Google Analytics");
define("WEBCONFIG_WEB_GOOGLE_ANALYTICS_INFO", "Google Analytics helps you to monitor page visits your pages,
												count time how long was the visitor on the page etc. Google Analytics is javascript code which is pasted on each page.
												More information you can find here: <a target='_blank' href='https://www.google.com/analytics/'>https://www.google.com/analytics/</a>");
define("WEBCONFIG_WEB_SAVE_SUCCESS", "Changes have benn successfully saved");
define("WEBCONFIG_WEB_BACKGROUND_COLOR", "Background colour");
define("WEBCONFIG_WEB_BACKGROUND_COLOR_INFO", "Here you can choose the background colour of your pages. Color will be used for the all background.");
define("WEBCONFIG_WEB_MENU_SHOW", "Show menu");
define("WEBCONFIG_WEB_MENU_SHOW_INFO", "It sets if the menu will be visible or not. ");
define("WEBCONFIG_WEB_MENU_BACKGROUND_COLOR", "Background colour of the menu");
define("WEBCONFIG_WEB_MENU_BACKGROUND_COLOR_INFO", "It sets colour of the menu bar.");
define("WEBCONFIG_WEB_MENU_LINK_COLOR", "Font colour in the menu");
define("WEBCONFIG_WEB_MENU_LINK_COLOR_INFO", "It sets link colour in the menu bar.");

define("WEBCONFIG_SETTINGS_SHOW_HOME", 'Show home link');
define("WEBCONFIG_SETTINGS_SHOW_HOME_INFO", 'On the first position in the menu bar will be showed small house which will be the link to homepage.');
define("WEBCONFIG_SETTINGS_SHOW_BLOCK", 'Block of the hamopage');
define("WEBCONFIG_SETTINGS_SHOW_BLOCK_INFO", 'Choose the content block which will be showed on the homepage.');
define("WEBCONFIG_SETTINGS_LANG_DEPENDS", 'Settings depend on the language');
define("WEBCONFIG_SETTINGS_LANG_COMMON", 'General settings');

// counters
define("COUNTER_TITLE", 'System counters');
define("COUNTER_TITLE_INFO", 'Configuration of used counters in the system');
define("COUNTER_COVERAGE_COUNTER", 'System counters of coverage lists');
define("COUNTER_COVERAGE_BT_COUNTER", 'Next number of the coverage list for bull terriers');
define("COUNTER_COVERAGE_MBT_COUNTER", 'Next number of the coverage list for mini bull terriers');
define("COUNTER_LITTER_NO_FROM_COVERAGE", 'Number from coverage application');
define("COUNTER_LITTER_NO_FROM_COVERAGE_REQ", 'Number from coverage application is mandatory!');
define("COUNTER_LITTER_NO", 'NO coverage application');

// modal window
define("MODAL_BUTTON_OK", 'OK');
define("MODAL_WINDOWS_WARNING_TITLE", 'Warning');

// slider
define("SLIDER_SETTINGS", "Picture setting (slider)");
define("SLIDER_SETTINGS_INFO", "Slider can have one or more pictures which will be randomly changing in the top of the pages. <br />
								<b>Important: </b> If will saved more then one picture is <b>necessary</b> to have the sema resolution for each picture.");
define("SLIDER_SETTINGS_PICS", "Save slider image");
define("SLIDER_SETTINGS_PICS_INFO", "Images are possible to save by one by one or more in one batch.");
define("SLIDER_SETTINGS_CURRENT_PICS", "Current slider images");
define("SLIDER_SETTINGS_SAVE_BTN_LABEL", 'Save');
define("SLIDER_SETTINGS_PIC_FORMAT", "BMP, JPG, PNG images are supported. Please load just these formats.");
define("SLIDER_SETTINGS_CONFIRM_MODAL_DELETE_TITLE", 'Delete slider image');
define("SLIDER_SETTINGS_CONFIRM_MODAL_DELETE_MSG", 'Are you really want to delete slider image?');
define("SLIDER_SETTINGS_CONFIRM_MODAL_OK", 'Delete');
define("SLIDER_SETTINGS_CONFIRM_MODAL_CANCEL", 'Cancel');
define("SLIDER_SETTINGS_DELETE_TITLE", 'Image deletions');
define("SLIDER_SETTINGS_SLIDER_ACTIVE_LABEL", 'Turn-on slider');
define("SLIDER_SETTINGS_SLIDER_ACTIVE_INFO", 'Switches the slider in the top of the pages.');
define("SLIDER_SETTINGS_SLIDER_SLIDING_LABEL", 'Turn-on slide show');
define("SLIDER_SETTINGS_SLIDER_SLIDING_INFO", 'If will be the slide show turned on images will be changing in the time interval you want.
 											If the slide show stays off, will be displayed statically one image. But if will be in slider saved (loead)
 											more then one image it causes that after page reloed will be display another image.');
define("SLIDER_SETTINGS_SLIDER_ARROWS_LABEL", 'Show slider control');
define("SLIDER_SETTINGS_SLIDER_ARROWS_INFO", 'Show arrows in the slider which can be used for swithcing images in the slider.');

define("SLIDER_SETTINGS_SLIDER_WITDH", 'Slider width');
define("SLIDER_SETTINGS_SLIDER_WITDH_INFO", 'It sets width of the slider to page content. <br />
											100% = means all the page (browser) width<br />
											50%  = means half of the page (browser) width <br />
											<b>Note:</b> 100% is default is recommanded for most users');
define("SLIDER_SETTINGS_TIMING", 'Slider time interval (s)');
define("SLIDER_SETTINGS_TIMING_INFO", 'It means number of second before each slide.');
define("SLIDER_SETTINGS_SAVE_OK", "Changes have been successfully saved.");


// menu
define("MENU_SETTINGS_TITLE", 'Menu configuration');
define("MENU_SETTINGS_INFO", '');
define("MENU_SETTINGS_ITEM_NAME", 'Item title');
define("MENU_SETTINGS_ITEM_NAME_REQ", 'Item title is mandatory!');
define("MENU_SETTINGS_ITEM_LINK", 'URL link');
define("MENU_SETTINGS_ITEM_LINK_REQ", 'URL link is mandatory!');
define("MENU_SETTINGS_ITEM_SEO", 'SEO title');
define("MENU_SETTINGS_ITEM_LINK_ADDED", 'Item has been successfully added');
define("MENU_SETTINGS_ITEM_DELETED", 'Item has been successfully deleted');
define("MENU_SETTINGS_ITEM_LINK_FAILED", 'During saving appeared a problem. Please repeat it later');
define("MENU_SETTINGS_ITEM_LINK_INFO", 'Title in menu URL item . <b>IMPORTANT for SEO</b>.');
define("MENU_SETTINGS_SUBMENU", 'Has subitems');
define("MENU_SETTINGS_ADD", 'Add item');
define("MENU_SETTINGS_TABLE_ORDER", 'Order in menu');
define("MENU_SETTINGS_LINK", 'Link name in URL');
define("MENU_SETTINGS_IN_MENU_TITLE", 'Title in menu');
define("MENU_SETTINGS_ALT", 'SEO title');
define("MENU_SETTINGS_MENU_TOP_DELETE", 'Delete menu item');
define("MENU_SETTINGS_EDIT_ITEM", 'Edit menu item');
define("MENU_SETTINGS_ADD_SUBITEM", 'Add subitem in menu item');
define("MENU_SETTINGS_MOVE_ITEM_UP", 'Move up');
define("MENU_SETTINGS_MOVE_ITEM_DOWN", 'Move down');
define("MENU_SETTINGS_CONFIRM_MODAL_DELETE_TITLE", 'Menu item deletions');
define("MENU_SETTINGS_CONFIRM_MODAL_DELETE_MSG", 'Are you really want to delete the item?');
define("MENU_SETTINGS_ITEM_MOVE_UP", 'Menu item has benn successfully moved up.');
define("MENU_SETTINGS_ITEM_MOVE_DOWN", 'Menu item has benn successfully moved down.');
define("MENU_SETTINGS_ITEM_MOVE_FAILED", 'Error during menu item edition, please try it again later.');
define("MENU_SETTINGS_ITEM_TITLE", 'Menu item configuration');
define("MENU_SETTINGS_ITEM_INFO", 'Please fill the parameters for menu item. SEO title should have been apt for the given link. ');
define("MENU_SETTINGS_ITEM_VISIBILITY", 'Visible');

// constact form
define("CONTACT_FORM_SETTING_TITLE", 'Contact form setting');
define("CONTACT_FORM_SETTING_TITLE_INFO", 'Here you can set basics for contact form.');
define("CONTACT_FORM_NAME", 'Your name');
define("CONTACT_FORM_NAME_REQ", 'Field name is mandatory!');
define("CONTACT_FORM_EMAIL", 'Contact email');
define("CONTACT_FORM_EMAIL_REQ", 'Email field is mandatory!');
define("CONTACT_FORM_SUBJECT", 'Subject');
define("CONTACT_FORM_SUBJECT_REQ", 'Field subject is mandatory');
define("CONTACT_FORM_ATTACHMENT", 'Attachment');
define("CONTACT_FORM_ATTACHMENT_INFO", 'Sender can attache the attachment to the contact message.');
define("CONTACT_FORM_TEXT", 'Your message');
define("CONTACT_FORM_TEXT_REQ", 'Message body is mandatory');
define("CONTACT_FORM_BUTTON_CONFIRM", 'Send');

define("CONTACT_FORM_SETTING_BACKEND_TITLE", 'Title of the contact form');
define("CONTACT_FORM_SETTING_BACKEND_CONTENT", 'Content of the contact form');
define("CONTACT_FORM_SETTING_BACKGROUND_COLOR", 'Color of the contact form background');
define("CONTACT_FORM_SETTING_COLOR", 'Font colour in the contact form');
define("CONTACT_FORM_SETTING_RECIPIENT", 'An email recipient from contact form.');
define("CONTACT_FORM_SETTING_RECIPIENT_VALIDATION", 'An email address of an email recipient is mandatory field!');
define("CONTACT_FORM_SETTING_RECIPIENT_INFO", 'On this email address will be send a request from contact form. This email will also used as a sender email. More addresses devide with semicolon (;).');
define("CONTACT_FORM_SETTING_ATTACHMENT", 'Enabled attachment');
define("CONTACT_FORM_SETTING_SAVE", 'Save');
define("CONTACT_FORM_SETTING_COMPLETE_SAVE", 'Setting of the contact form has been saved');
define("CONTACT_FORM_WAS_SENT", 'Your question has been successfully sent.');
define("CONTACT_FORM_SENT_FAILED", 'Mandatory fields have not been filled.');
define("CONTACT_FORM_UNSUPPORTED_FILE_FORMAT", 'You are trying to send unsupported attachment!');
define("CONTACT_FORM_EMAIL_MY_SUBJECT", 'Inquiry from web contact form');
define("CONTACT_FORM_BREEDING_CONSULTANT_EMAIL", 'Breeding consultant form');

// footer
define("FOOTER_CONTENT", 'Footer content');
define("FOOTER_BUTTON_SAVE", "Save");
define("FOOTER_TITLE", "Footer settings");
define("FOOTER_INFO", "Footer is piece of content which will be showed in the bottom of each page.");
define("FOOTER_CONTACT", "Show contact form in page footer");
define("FOOTER_CONTENT_TEXT", 'Footer content you can format with text editor. If you want to show picture in the footer is necessary to load the picture first
								and then paste the URL link of picture to the editor with picture wizard.');
define("FOOTER_CONTENT_PICS", 'Here load a picture which you want to have in the footer content.');
define("FOOTER_BACKGROUND_COLOR", 'Footer background colour');
define("FOOTER_SETTING_COLOR", 'Footer text colour');
define("FOOTER_SETTING_SAVED", 'Footer settings have been saved');
define("FOOTER_PIC_FORMAT", "Supported image formats are BMP, JPG, PNG! Please load just these formats.");
define("FOOTER_PIC_DELETE", 'Image deletions');
define("FOOTER_PIC_DELETED", 'Image has been successfully removed');
define("FOOTER_CONFIRM_DELETE_TITLE", 'Picture deletions');
define("FOOTER_CONFIRM_DELETE", 'Are you really want to delete this image?');
define("FOOTER_CONFIRM_DELETE_CANCEL", 'Cancel');
define("FOOTER_CONFIRM_DELETE_OK", 'Delete');
define("FOOTER_SHOW_FOOTER", 'Turn on the footer');
define("FOOTER_WIDTH", 'Width of the footer');
define("FOOTER_WIDTH_INFO", 'Footer width setting toward the whole width of the browser window');
define("FOOTER_PIC_CONTENT", 'Images which is possible to paste into footer content');


// block
define("BLOCK_SETTING_TITLE", 'Contents block');
define("BLOCK_SETTING_INFO", 'Content block are pieces which is possible to assembly to whole content of the menu link. One page (menu link) can
							creates just one or more page blocks. In this section you can sets this block (page contents).');
define("BLOCK_SETTING_CONTENT", 'Content');
define("BLOCK_SETTING_BG_COLOR", 'Background colour');
define("BLOCK_SETTING_COLOR", 'Font colour');
define("BLOCK_SETTING_EDIT_ITEM", 'Block editions');
define("BLOCK_SETTING_DELETE_ITEM", 'Block deletions');
define("BLOCK_SETTING_PICS", 'Available images');
define("BLOCK_SETTING_WIDTH", 'Width of block');
define("BLOCK_SETTING_PICS_INFO", 'Available images is possible to pasto to the page block. Just copy the url of the image and paste it in the text editor image wizard
								then select resolution of this image and save the block content. <br />
 								<b>NOTE: </b> If you want to paste new image is necessary to save the image first and then you wil be able to paste them in to the content.');
define("BLOCK_SETTING_ITEM_EDIT", 'Block settings');
define("BLOCK_SETTING_ITEM_EDIT_INFO", 'Here you can set the whole block setting including its language(s).');
define("BLOCK_SETTING_ITEM_CONTENT_LABEL", 'Block content');
define("BLOCK_SETTING_ITEM_CONTENT_CONFIRM", 'Save the block');
define("BLOCK_SETTING_ITEM_CONTENT_COLOR", 'Font colour');
define("BLOCK_SETTING_ITEM_CONTENT_BG_COLOR", 'Background block colour');
define("BLOCK_SETTING_ITEM_WIDTH_INFO", 'Block width setting toward width of the browser width.');
define("BLOCK_SETTING_PIC_WILL_DELETE", 'Image deletions');
define("BLOCK_SETTING_PIC_DELETED", 'Image has been successfully deleted');
define("BLOCK_SETTING_PIC_DELETE_TITLE", 'Image deletions');
define("BLOCK_SETTING_PIC_DELETE", 'Are you really want to delete this picture?');
define("BLOCK_SETTING_PIC_DELETE_CANCEL", 'Cancel');
define("BLOCK_SETTING_PIC_DELETE_OK", 'Delete');
define("BLOCK_SETTINGS_CONFIRM_MODAL_DELETE_TITLE", 'Block deletions');
define("BLOCK_SETTINGS_CONFIRM_MODAL_DELETE_MSG", 'Are you really wnat to delete block of content?');
define("BLOCK_SETTINGS_CONFIRM_MODAL_OK", 'Delete');
define("BLOCK_SETTINGS_CONFIRM_MODAL_CANCEL", 'Cancel');
define("BLOCK_SETTINGS_ITEM_DELETED", 'Item has been successfully deleted.');
define("BLOCK_SETTINGS_ITEM_DEFAULT_BLOCK", 'This block cannot be deleted bacause it is default home block!');
define("BLOCK_SETTINGS_ITEM_DELETED_FAILED", 'During the deletions error occurred, please try it again later!');
define("BLOCK_SETTINGS_ITEM_SAVED_FAILED", 'During saving the item, error occurred, please try again later!');

// block and content
define("BLOCK_CONTENT_SETTINGS", 'Web page content');
define("BLOCK_CONTENT_SETTINGS_INFO", 'In this section you are able to assembly content. Links from menu are putting together with block. Each item
										(subitem) from menu should have at least one block of content. As pagew title is used the one who is added in menu item.');
define("BLOCK_CONTENT_SETTINGS_BLOCKS_IN_MENU", 'Content of the page link');
define("BLOCK_CONTENT_SETTINGS_BLOCKS_IN_CONTENT", 'Blocks added to link');
define("BLOCK_CONTENT_SETTINGS_BLOCKS_IN_CONTENT_INFO", 'Block in the links create a content of the page (link). You are able to add more then one blocks to the link.
														The ordering means the order the blocks in the page. One link should have at least one block.');
define("BLOCK_CONTENT_SETTINGS_AVAILABLE_BLOCKS", 'Available blocks');
define("BLOCK_CONTENT_SETTINGS_AVAILABLE_BLOCKS_INFO", 'Blocks which is possible to add to the link (page).');
define("BLOCK_CONTENT_SETTINGS_CONTACT_FORM_AS_BLOCK", 'Contact form');
define("BLOCK_CONTENT_SETTINGS_ADD_TITLE", 'Add block to the link');
define("BLOCK_CONTENT_SETTINGS_REMOVE_TITLE", 'Remove block from the link');
define("BLOCK_CONTENT_SETTINGS_MOVE_BLOCK_UP", 'Move the block up');
define("BLOCK_CONTENT_SETTINGS_MOVE_BLOCK_DOWN", 'Move the block down');
define("BLOCK_CONTENT_SETTINGS_NO_BLOCKS", '-- empty --');

// language
define("LANG_SETTINGS", 'Language mutations');
define("LANG_SETTINGS_GLOBAL", 'Language bar setting');
define("LANG_BG_COLOR", 'Background colour of the menu bar');
define("LANG_BG_COLOR_INFO", 'Please choose desired color for the background.');
define("LANG_FONT_COLOR", 'Colour of the link in language strap');
define("LANG_FONT_COLOR_INFO", 'Please choose colour for the text in language bar');
define("LANG_ITEM_FLAG", 'Flag of the language');
define("LANG_ITEM_DESC", 'Language title');
define("LANG_ITEM_SHORT", 'Language code');
define("LANG_TITLE_INFO", "Here you can configure language mutation and its display.");
define("LANG_WIDTH", 'Width of the language bar');
define("LANG_WIDTH_INFO", 'Percent width of the browser window');
define("LANG_ALREADY_SAVED_LANGS", 'Available language mutations');
define("LANG_ALREADY_NEW_LANG", 'New language mutation saving');
define("LANG_CONFIRM", 'Save language setting');
define("LANG_TABLE_SHORTCUT", 'Language');
define("LANG_TABLE_FLAG", 'Flag icon');
define("LANG_TABLE_DELETE", 'Remove the language mutation');
define("LANG_CONFIRM_MODAL_DELETE_TITLE", 'Deletion of the language mutation');
define("LANG_CONFIRM_MODAL_DELETE_MSG", 'Are you really want to delete language mutation?');
define("LANG_CONFIRM_MODAL_OK", 'Delete');
define("LANG_CONFIRM_MODAL_CANCEL", 'Cancel');

// header
define("HEADER_SETTING_SAVED", 'Header settings have been successfully saved');
define("HEADER_SETTING_COLOR", 'Font colour');
define("HEADER_HEIGHT", 'Height of the header');
define("HEADER_HEIGHT_INFO", 'Fill height of the header i pixels');
define("HEADER_BACKGROUND_COLOR", 'Header background colour');
define("HEADER_TITLE", 'Page header setting');
define("HEADER_CONTENT", 'Content of the page header');
define("HEADER_INFO", 'Here you can set static header properties which will be showed in each page');
define("HEADER_SHOW_HEADER", 'Show header');
define("HEADER_WIDTH", 'Width of the page header');
define("HEADER_WIDTH_INFO", 'Width of the page header towards web browser');
define("HEADER_CONTENT_TEXT", 'Content of the page header');
define("HEADER_CONTENT_PICS", 'Images of the page header');
define("HEADER_PIC_CONTENT", 'Available images for page header');
define("HEADER_CONFIRM_DELETE_TITLE", 'Page header image deletions');
define("HEADER_CONFIRM_DELETE", 'Are you really want to delete image of the web header?');
define("HEADER_PIC_DELETE", 'Page header image deletions');
define("HEADER_CONFIRM_DELETE_CANCEL", 'Cancel');
define("HEADER_CONFIRM_DELETE_OK", 'Delete');
define("HEADER_BUTTON_SAVE", 'Save header setting');

// číselníky
define("ENUM_TITLE", 'Enumeration setting');
define("ENUM_TITLE_DESCRIPTION", 'Zde je možné spravovat číselníky, které jsou napříč celým systémem. Správu by měl dělat jen administrátor,
								protože změny mohou mít dopad na celou aplikaci');
define("ENUM_TABLE_ENUM_NAME", 'Enumeration title');
define("ENUM_TABLE_FIELDS_PREVIEW", 'Enumeration items');
define("ENUM_TABLE_ADD_NEW_ENUM", 'Add enumeration');
define("ENUM_TABLE_ENUM_EDIT", 'Enumeration edit');
define("ENUM_TABLE_ENUM_DELETE", 'Enumeration delete');
define("ENUM_TABLE_ENUM_WARNING_TITLE", 'Deleting enumeration');
define("ENUM_TABLE_ENUM_WARNING_TEXT", 'Are really want to delete whole enumeration item?');

define("ENUM_EDIT_TITLE", 'Enumeration detail');
define("ENUM_EDIT_DESC", 'Enumeration detail area, please take care what are you doing');
define("ENUM_EDIT_NAME", 'Enumeration title');
define("ENUM_EDIT_NAME_REQ", 'Title of enumeration is mandatory');
define("ENUM_EDIT_ITEMS_LABEL", 'Enumeration items');
define("ENUM_EDIT_ITEM_EDIT", 'Editation of enumeration item');
define("ENUM_EDIT_ITEM_DELETE", 'Enum item delete');
define("ENUM_EDIT_ITEM_ADD", 'Add enumeration item');
define("ENUM_EDIT_ITEM_DELETE_MSG", 'Are you really want to delete enumeration item');

define("ENUM_EDIT_ITEM_DESC",  'Edit of enumeration item in all langugages mutations.');
define("ENUM_EDIT_ITEM_TITLE", 'Item of enumeration');
define("ENUM_EDIT_ITEM_NAME", 'Value of enumeration item');
define("ENUM_EDIT_ITEM_NAME_REQ", 'Value of enumeration item is mandatory.');
define("ENUM_EDIT_ITEM_SAVE", 'Value of enumeration item has been successfully saved.');
define("ENUM_EDIT_ITEM_FAIL", 'Saving enumeration item failed.');
define("ENUM_DELETE_SUCCESS", 'Whole enumeration has benn deleted.');
define("ENUM_DELETE_FAIL", 'During whole enumeration failed. Probably any value of enumeration item is in used. ');
define("ENUM_DELETE_ITEM_FAIL", 'Enumeration item deletation failed. Probably the value is in use. ');

// veterináři
define("VET_TITLE", 'Vet / Lab');
define("VET_INFO", 'Setting of each vet or lab which can be used in application.');
define("VET_NAME", 'Name / Title of vet');
define("VET_ADDRESS", 'Address');
define("VET_ADD_VET", 'Add vet');
define("VET_DELETE_VET", 'Delete vet');
define("VET_EDIT_VET", 'Edit vet');
define("VET_ADD", 'Vet edit');
define("VET_EDIT", 'Add vet');

define("VET_EDIT_NAME", 'Name');
define("VET_EDIT_SURNAME", 'Surname');
define("VET_EDIT_PREFIX", 'Title before');
define("VET_EDIT_SUFFIX", 'Title after');
define("VET_EDIT_STREET", 'Street');
define("VET_EDIT_CITY", 'City');
define("VET_EDIT_PSC", 'Zip');
define("VET_EDIT_SAVE", 'Save');
define("VET_EDIT_BACK", 'Back');

define("VET_CONFIRM_DELETE_TITLE", 'Delete vet / lab');
define("VET_CONFIRM_DELETE", 'Are you really want to delete the record?');
define("VET_CONFIRM_DELETE_DELETE", 'Delete');
define("VET_CONFIRM_DELETE_CANCEL", 'Back');
define("VET_ADDED", 'Successfully saved.');
define("VET_ADD_FAILED", 'Saved failed, try again later.');

// common
define("UNSUPPORTED_UPLOAD_FORMAT", " You are trying to upload unsupported format. Supported formats are %s.");

// tabulka psi
define("DOG_TABLE_FILTER_LABEL", 'Filter');
define("DOG_TABLE_HEADER_ADD_DOG", 'new');
define("DOG_TABLE_FILTER_TOGGLE", 'filter');
define("DOG_TABLE_BTN_FILTER", 'filter');
define("DOG_TABLE_BTN_CLEAR_FILTER", 'clear filter');
define("DOG_TABLE_HEADER_NAME", 'name');
define("DOG_TABLE_HEADER_BREED", 'breed');
define("DOG_TABLE_HEADER_COLOR", 'colour');
define("DOG_TABLE_HEADER_SEX", 'sex');
define("DOG_TABLE_HEADER_BIRT", 'birt. date');
define("DOG_TABLE_HEADER_BREEDING", 'breeding');
define("DOG_TABLE_HEADER_PROB_DKK", 'DKK');
define("DOG_TABLE_HEADER_PROB_DLK", 'DLK');
define("DOG_TABLE_HEADER_HEALTH", 'health');
define("DOG_TABLE_HEADER_HEALTH_TEXT", 'health result');
define("DOG_TABLE_HEADER_MOTHER", 'mother');
define("DOG_TABLE_HEADER_FATHER", 'father');
define("DOG_TABLE_HEADER_LAND", 'land');
define("DOG_TABLE_HEADER_BREEDER", 'breeder');
define("DOG_TABLE_HEADER_EXAM", 'exam');
define("DOG_TABLE_HEADER_HEIGHT", 'height');
define("DOG_TABLE_HEADER_FULLTEXT", 'searching');
define("DOG_TABLE_HEADER_WRITE_NUMBER", 'num.of registration');
define("ASCENDING", 'ascending');
define("DESCENDING", 'descending');
define("DOG_TABLE_DOG_DELETED", 'Dog successfully deleted.');
define("DOG_TABLE_DOG_DELETED_FAILED", 'An error occurred during dog deleting, try again later.');
define("DOG_TABLE_DOG_DETAIL", 'Detail of the dog');
define("DOG_TABLE_DOG_EDIT", 'Edit dog');
define("DOG_TABLE_DOG_ADD_SIBLING", 'Add sibling');
define("DOG_TABLE_DOG_DELETE", 'Delete dog');
define("DOG_TABLE_DOG_DELETATION_TITLE", 'Dog erase');
define("DOG_TABLE_DOG_DELETATION_CONFIRM", 'Are you really want to delete the dog?');
define("DOG_TABLE_DOG_DELETATION_CONFIRM_YES", 'Delete');
define("DOG_TABLE_DOG_DELETATION_CONFIRM_CANCEL", 'Cancel');
define("DOG_TABLE_DOG_ACTION_NOT_ALLOWED", 'This action requires administration privilege!');
define("DOG_TABLE_DOG_YEAR_FROM", 'from');
define("DOG_TABLE_LAST_14_DAYS", 'last 14 days');
define("DOG_TABLE_RECORD_COUNT", 'Record count:');
define("DOG_TABLE_RECORD_COUNT_PAGES", 'page/s:');

define("DOG_FORM_NAME_PREFIX", 'Titles before name');
define("DOG_FORM_NAME", 'Name');
define("DOG_FORM_NAME_MANDATORY", 'Name is mandatory field');
define("DOG_FORM_NAME_SUFFIX", 'Titles behind name');
define("DOG_FORM_BREED", 'Breed');
define("DOG_FORM_FUR", 'Colour of the fur');
define("DOG_FORM_FUR_COLOUR", 'Colour');
define("DOG_FORM_FUR_COM", 'Comment');
define("DOG_FORM_SEX", 'Sex');
define("DOG_FORM_BIRT", 'Birth');
define("DOG_FORM_HEIGHT", 'Height');
define("DOG_FORM_WEIGHT", 'Weight');
define("DOG_FORM_DEAD", 'Date of death');
define("DOG_FORM_DEAD_COM", 'Death comment');
define("DOG_FORM_NO_OF_REC", 'Number of registration');
define("DOG_FORM_NO_OF_REC2", 'Other number of registration');
define("DOG_FORM_NO_OF_TATTOO", 'Tattoo');
define("DOG_FORM_NO_OF_CHIP", 'Chip');
define("DOG_FORM_BON", 'Date of lead');
define("DOG_FORM_BON_DATE", 'Date of bonitative');
define("DOG_FORM_BREEDING", 'Breeding');
define("DOG_FORM_BREEDING_COM", 'CZDR');
define("DOG_FORM_BOLOCKS", 'Testicles');
define("DOG_FORM_CHEW", 'Bite');
define("DOG_FORM_TEETH_COM", 'Teeth');
define("DOG_FORM_HEALTH_COM", 'Admin comment');
define("DOG_FORM_PICS", 'Pictures');
define("DOG_FORM_FILES", 'Other dog files');
define("BONITACNI_POSUDEK", 'Document');
define("DOG_FORM_HEADER", 'Dog details');
define("DOG_FORM_PIC_UPLOAD", 'Upload pictures');
define("DOG_FORM_PIC_UPLOAD_FILE", 'Files - docs');
define("DOG_FORM_ADDED", 'Date has been successfully saved.');
define("DOG_FORM_ADD_FAILED", 'Changes has been successfully saved.');
define("DOG_FORM_PIC_DEFAULT", 'Set picture as default');
define("DOG_FORM_PIC_DELETE", 'Delete picture');
define("DOG_FORM_PIC_DELETE_TITLE", 'Delete item');
define("DOG_FORM_PIC_DELETE_INFO", 'Are you really want to delete the item?');
define("DOG_FORM_FILE_DELETE", 'Delete file');
define("DOG_FORM_OWNER_DELETE", 'Delete owner');
define("DOG_FORM_BREEDER_DELETE", 'Delete breeder');
define("DOG_FORM_HEALTH_COMMENT", 'Comment');
define("DOG_FORM_HEALTH_SUMMARY", 'Summary');
define("DOG_FORM_HEALTH_DATE", 'Date');
define("DOG_FORM_HEALTH_VET", 'Vet');
define("DOG_FORM_HEALTH", 'Health');
define("DOG_FORM_HEIGHT_NUMBER", 'Height of the dog must be a valid number with two decimals (in cm), separator is point (.)!');
define("DOG_FORM_WEIGHT_NUMBER", 'Weight of the dog must be a valid number with two decimals (in kg), separotor is point (.)!');
define("DOG_FORM_LITTER_CHECK", 'Litter check');

define("DOG_FORM_TITLES", 'Exhibition awards');    // Titles
define("DOG_FORM_BON_TEXT", 'Lead code');
define("DOG_FORM_SHOWS_TEXT", 'Exhibition results');    // Show result
define("DOG_FORM_SHOWS_NEXT_TEXT", 'Other show result');
define("DOG_FORM_SHOWS_EXAMS", 'Exams');
define("DOG_FORM_SHOWS_EXAMS_NEXT", 'Passed tests');
define("DOG_FORM_SHOWS_RACES", 'Races');
define("DOG_FORM_HIDE_DESCENDANTS", 'Hide descendats');
define("DOG_FORM_HIDE_SIBLINGS", 'Hide siblings');
define("DOG_FORM_HIDE_DOG_CARD", 'Hide whole card');
define("DOG_FORM_HIDE_DOG_LEAD", 'Hide lead');
define("DOG_FORM_SHOWS_NOTE", 'Note');
define("DOG_FORM_BREEDER", 'Breeder');
define("DOG_FORM_MALE", 'Father');
define("DOG_FORM_FEMALE", 'Mother');
define("DOG_FORM_DESCENDANTS", 'Descendants');
define("DOG_FORM_SIBLINGS", 'Siblings');
define("DOG_FORM_PEDIGREE", 'Pedigree');
define("DOG_FORM_PEDIGREE_COEF", 'Affinity factor');
define("DOG_FORM_PEDIGREE_COEF_NOT_FULL", 'incomplete (less than 5 generations)');
define("DOG_FORM_PEDIGREE_GENERATION_NO", 'generation');
define("DOG_FORM_PEDIGREE_GENERATIONS_NO", 'generations');
define("DOG_FORM_PEDIGREE_ADD_MISSING", 'Add dog');

define("DOG_FORM_OWNERS", 'Owner/s');
define("DOG_FORM_PREVIOUS_OWNERS", 'Previous owners');
define("DOG_FORM_OWNERS_SELECT_TEXT", 'Select some options');
define("DOG_FORM_OWNERS_SELECT_NO_MATCH", 'No resut match');
define("DOG_FORM_MID_OID_FAILED_TITLE", 'Reference genealogy error');
define("DOG_FORM_MID_OID_FAILED_MESSAGE", 'Mother or father of this dog does not match sex of mother/father.');
define("DOG_FORM_NOT_TRUE_OWNER", 'You are not owner neither breeder of this dog!');
define("DOG_FORM_REQUEST_NOT_EXISTS", 'Invalid URL request!');

define("MATING_FORM_CLUB", 'Mating list');
define("MATING_FORM_FID", 'Male');
define("MATING_FORM_MID", 'Female');
define("MATING_FORM_SAVE", 'Litter report');
define("MATING_FORM_SAVE1", 'Send coverage report');
define("MATING_FORM_SAVE2", 'Litter application');
define("MATING_FORM_PICK_MALE", 'Choose a male');
define("MATING_FORM_PICK_FEMALE", 'Choose a female');
define("MATING_FORM_NO_MATCH", 'No match found');
define("MATING_FORM_COVERAGE", 'MATING FORM - coverage report');
define("MATING_FORM_TYPE", 'breed type: controlled');
define("MATING_FORM_COVERAGE_DETAIL", '- for the owner of the cover dog');
define("MATING_FORM_BON_CODE", 'Bonitation code');
define("MATING_FORM_PLACE_DETAIL", 'Covering was carried out');
define("MATING_FORM_PLACE_DETAIL_DAY", 'date');
define("MATING_FORM_PLACE_DETAIL_DAY_REPEAT", 'repeat on day');
define("MATING_FORM_ESTIMATE_DATE", 'Date of litter');
define("MATING_FORM_RULES", 'Do you want to add the mating on the web?');
define("MATING_FORM_NOTE", 'Note (insemination, cover for cover, etc.)');
define("MATING_FORM_NOTE_2", "The owner of the cover dog is obliged to send within 7 days after coverage the completed cover sheet - a report on the coverage of the herd book manager. In case of foreign cover, the owner sends the bitch.");
define("MATING_FORM_OWNER", "");
define("MATING_FORM_INFO_BREEDER", "Breeder will write for folders for puppies to Mrs. Hradecká to email VendulaHradeck@email.cz");
define("MATING_MAX_FILE_SIZE", "<small style='color: red; font-weight: bold;'>Files over size %s kB won't be upload!</small>");
define("MATING_MAX_FILE_SIZE_EXCEEDED", "File size exeeded %s. ");
define("MATING_MAIL_BODY", "In genealogy has been created new litter report");
define("MATING_MAIL_SUBJECT", "Attached you can find new litter report");
define("MATING_PROCEED_OK", "Litter report has been send to process");
define("COVERAGE_EXPRESS", "Express coverage sheet");
define("COVERAGE_SAVED_OK", "Covere sheet has been successfully saved and sent to process");
define("COVERAGE_MAIL_SUBJECT", "New coverage application has been created");
define("COVERAGE_MAIL_BODY", "New coverage application has been created with ID %s");
define("COVERAGE_MAIL_BODY_TO_USER", "Your coverage application has been updated, please see the attachment.");
define("COVERAGE_MAIL_SEE", "See");
define("COVERAGE_MAIL_SEND", "Send to user");
define("COVERAGE_EDIT", "Edit");
define("COVERAGE_NOT_EXISTS", "Coverage application %s not exists!");
define("COVERAGE_MAIL_USER_SUCCESS", "Coverage application has been sent");
define("COVERAGE_DATE_WRONG_FORMAT", "Wrong date format: %s - should be YYYY-MM-DD");

define("MATING_FORM_DATE_SHORT", 'Date');
define("MATING_FORM_DATE", 'Mating date');
define("MATING_FORM_PLACE", 'Mating place');
define("MATING_FORM_PLACE2", 'Place');
define("MATING_FORM_INSEMINATION", 'Insemination');
define("MATING_FORM_AGREEMENT", 'Agreement');
define("MATING_FORM_MALE_OWNER", 'Name of the male dog owner');
define("MATING_FORM_FEMALE_OWNER", 'Name of the female owner');
define("DOG_FORM_NAME_FEMALE", 'Female name');
define("DOG_FORM_NAME_MALE", 'Male name');
define("MATING_FORM_DISCLAIMER", 'Signature confirmation, that the dog registered in the breeder book no. 1 – CMKU');
define("MATING_FORM_SIGNATURE", 'signature');
define("MATING_FORM_OVERAGAIN", 'Start again');
define("MATING_FORM_GENERATE", 'Send to process');

define("USER_VIEW_OWNER", 'User is owner for');
define("USER_VIEW_OWNER_PREVIOUS", 'User is previous owner for');
define("USER_VIEW_BREEDER", 'Breeder');

// referees
define("REFEREE_MENU", 'Referees');
define("REFEREE_TITLE", 'Referees');
define("REFEREE_INFO", 'Here you are able to manage details of referee used in the application.');
define("REFEREE_NAME", 'Name');
define("REFEREE_ADDRESS", 'Address');
define("REFEREE_ADD", 'Add referee');
define("REFEREE_EDIT", 'Edit referee');
define("REFEREE_DELETE", 'Delete referee');
define("REFEREE_DELETE_MODAL_HEADER", 'Referee delete');
define("REFEREE_DELETE_MODAL_QUESTION", 'Are you really want to delete the referee?');
define("REFEREE_ADDED", 'Referee successfully added');
define("REFEREE_ADDED_FAILED", 'An error occurred, the data haven not been proceed');

// shows
define("SHOW_MENU", 'Shows');
define("SHOW_TITLE", 'Shows');
define("SHOW_INFO", 'Here you can add, edit, delete or manage show activity in the applicaion.');
define("SHOW_TABLE_TYPE", 'Type');
define("SHOW_TABLE_DATE", 'Date');
define("SHOW_TABLE_NAME", 'Title');
define("SHOW_TABLE_PLACE", 'Place');
define("SHOW_TABLE_DONE", 'Done');
define("SHOW_TABLE_REFEREE", 'Rozhodčí');
define("SHOW_TABLE_ADD_SHOW", 'Add show');
define("SHOW_TABLE_DETAIL", 'Show detail');
define("SHOW_TABLE_EDIT", 'Edit show');
define("SHOW_TABLE_DELETE", 'Delete show');
define("SHOW_TABLE_DELETE_SHOW_HEADER", 'Delete show');
define("SHOW_TABLE_DELETE_SHOW_INFO", 'Are you really want to delete the show?');

define("SHOW_FORM_ADD", 'Addition of a new show');
define("SHOW_FORM_EDIT", 'Show edit');
define("SHOW_FORM_NEW_ADDED", 'Show has been added');
define("SHOW_FORM_NEW_FAILED", 'Show saving failed');
define("SHOW_DELETED", 'Show deleted');
define("SHOW_DELETED_FAILED", 'An error occurred during show show delete');
define("SHOW_TABLE_DATE_VALIDATION", 'Date of the show is mandatory!');
define("SHOW_TABLE_NAME_VALIDATION", 'Title of the show is mandatory!');

define("SHOW_DOG_FORM_DOG", 'Dog');
define("SHOW_DOG_FORM_CLASS", 'Class');
define("SHOW_DOG_FORM_REPUTATION", 'Evaluation');
define("SHOW_DOG_FORM_DOG_ORDER", 'Placing');
define("SHOW_DOG_FORM_DOG_TITLES", 'Titles');
define("SHOW_DOG_FORM_DOG_TITLES_ADDON", 'Additions');
define("SHOW_REFEREE_FORM_REFEREE", 'Referee');
define("SHOW_REFEREE_FORM_CLASS", 'Class');
define("SHOW_REFEREE_FORM_BREED", 'Breed');

define("SHOW_DONE", 'Yes');
define("SHOW_UNDONE", 'No');
define("REFEREE_EMPTY_SAVE", 'No data to save. Please, choose at least a referee, a breed and a class!');
define("REFEREE_SAVED", 'Referee of the show has been saved');
define("REFEREE_DELETED", 'Item of the referee has been deleted');
define("REFEREE_SAVED_FAILED", 'An error occurred during request');
define("SHOW_ITEM_DELETE_HEADER", 'Delete item');
define("SHOW_ITEM_DELETE_QUESTION", 'Are you really want to delete the item?');
define("SHOW_DOG_SAVED", 'Dog saved in the show');
define("SHOW_DOG_DELETED", 'Item of the dog has been deleted');
define("SHOW_DOG_SAVED_FAILED", 'An error eccurred during operation');
define("DOG_EMPTY_SAVE", 'No data to save.');
define("SHOW_FRONTEND_YEAR", 'Year');

define("KINSHIP_VERIFICATION", "Inbreeding checking");
define("KINSHIP_VERIFICATION_AND", "and");
define("KINSHIP_VERIFICATION_CROSS", "Crossing of ancestors is taking in consideration");

define("AWAITING_CHANGES", "Awaiting changes");
define("AWAITING_CHANGES_DOG", "Dog");
define("AWAITING_CHANGES_USER", "User");
define("AWAITING_CHANGES_TIMESTAMP", "Date of request");
define("AWAITING_CHANGES_ORIGINAL_VALUE", "Original value");
define("AWAITING_CHANGES_WANTED_VALUE", "Requested value");
define("AWAITING_CHANGES_CONFIRM", "Confirm");
define("AWAITING_CHANGES_DECLINE", "Decline");
define("AWAITING_CHANGES_DECLINE_MODAL_TITLE", "Decline change");
define("AWAITING_CHANGES_DECLINE_MODAL_BODY", "Are you really want to decline the change?");
define("AWAITING_CHANGES_WHAT", "Change item");
define("AWAITING_CHANGES_SENT_TO_APPROVAL", "Changes has been sand for approval");
define("AWAITING_EMAIL_USER_DOG_SUBJECT", "You have made a change on the dog card");
define("AWAITING_EMAIL_USER_DOG_BODY", "Hello, <br />changes have been made on the dog card '%s'. Changes will be visible after administration approval.");
define("AWAITING_EMAIL_ADMIN_DOG_SUBJECT", "Changes have been made on the dog's card");
define("AWAITING_EMAIL_ADMIN_DOG_BODY", "Hello, <br />on the dog card '%s' have been made changes. For approval or decline, please, log in to administraion section. ");
define("AWAITING_CHANGE_CHANGE_ERR", "An error occurred during saving, please repeat later.");
define("AWAITING_CHANGE_CHANGE_DECLINE", "Request for the change has been declined.");
define("AWAITING_CHANGE_CHANGE_ACCEPT", "Change have been approved.");
define("AWAITING_CHANGE_ACCEPTED", "Approved changes");
define("AWAITING_CHANGE_DECLINED", "Declined changes");
define("AWAITING_CHANGE_PROCEEDED_BY", "Processed from");
define("AWAITING_CHANGE_PROCEEDED_WHEN", "Approved");
define("AWAITING_CHANGE_PROCEEDED_OK_SUBJECT", "Your change request has been apporved");
define("AWAITING_CHANGE_PROCEEDED_OK_BODY", "Hello, <br />your change request has been approved. Please, take a look here %s.");
define("AWAITING_CHANGE_PROCEEDED_DECLINE_SUBJECT", "Your change request has been declined");
define("AWAITING_CHANGE_PROCEEDED_DECLINE_BODY", "Hello, <br />your change request has been declined. Dog's card %s.");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL", "Your dog has been successfully created. The needs to be approved by administrator.");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_SUBJECT_USER", "New dog inserted");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_BODY_USER", "Hello, <br /> in the system has been inserted a new dog . PLease, wait for approval by administrator.");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_SUBJECT_ADMIN", "New dog inserted");
define("AWAITING_CHANGE_NEW_DOG_NEED_APPROVAL_BODY_ADMIN", "Hello, <br />a new dog card has been created %s. Please, log in for approval or decline the request.");
define("LITTER_APPLICATION", "Litter application");
define("LITTER_APPLICATION_DETAIL_TITLE", "Collie and sheltie breeders club");
define("LITTER_APPLICATION_DETAIL_APPLICATION", "Litter application form KABT");
define("LITTER_APPLICATION_DETAIL_APPLICATION_COUNTER_PREFIX", "KL-");
define("LITTER_APPLICATION_DETAIL_STATION_TITLE", "Name of kennel");
define("LITTER_APPLICATION_DETAIL_DOG_TITLES", "exhibition awards and achievements");
define("LITTER_APPLICATION_DETAIL_CARD_NO", "card number incl. Abbreviations of the stud book");
define("LITTER_APPLICATION_DETAIL_FUR_TYPE", "fur type");
define("LITTER_APPLICATION_DETAIL_BONITATION", "bonitations");
define("LITTER_APPLICATION_DETAIL_BREEDER_ADDRESS", "Breeder");
define("LITTER_APPLICATION_DETAIL_BREEDER_ADDRESS_DET", "(female owner, name, address, ZIP, phone, e-mail)");
define("LITTER_APPLICATION_DETAIL_PUPPIES_BIRTHDAY", "Date of birth of puppies");
define("LITTER_APPLICATION_DETAIL_PUPPIES_INFO", "Data on the number of puppies born");
define("LITTER_APPLICATION_DETAIL_PUPPIES_COUNT", "How many puppies gave birth to");
define("LITTER_APPLICATION_DETAIL_PUPPIES_DEATH_COUNT", "How many of them were dead");
define("LITTER_APPLICATION_DETAIL_PUPPIES_FORCE_DEATH_COUNT", "How much was killed");
define("LITTER_APPLICATION_DETAIL_PUPPIES_APPLICATION_FOR", "I sign up for a listing");
define("LITTER_APPLICATION_DETAIL_PUPPIES_TO_NURSE", "How much surrendered to nursing");
define("LITTER_APPLICATION_DETAIL_PUPPIES_DEATH_BEFORE_APPLICATION", "How much he died before writing");
define("LITTER_APPLICATION_DETAIL_PUPPIES_MALES", "males");
define("LITTER_APPLICATION_DETAIL_PUPPIES_FEMALES", "females");
define("LITTER_APPLICATION_DETAIL_PUPPIES_SEX_UNKNOW", "Sex unknow");
define("LITTER_APPLICATION_DETAIL_TEXT_1", "<b>Fill ČMKU</b><br />NO record<br />Tatto number");
define("LITTER_APPLICATION_DETAIL_TEXT_2", "<b>Chip number</b><br />(see 2. page)");
define("LITTER_APPLICATION_DETAIL_TEXT_3", "<b>Name of puppy</b>");
define("LITTER_APPLICATION_DETAIL_TEXT_4", "<b>Sex</b>");
define("LITTER_APPLICATION_DETAIL_TEXT_5", "<b>Colour kind</b>");
define("LITTER_APPLICATION_DETAIL_TEXT_6", "<br /><br />
									I confirm with my signature that I am the breeder of the above puppies and that all the data in the application are true.
									* By signing, I confirm my consent to the use of this data for the club's records within the Club's genealogy.
									<br /><br />&nbsp;<br />");
define("LITTER_APPLICATION_DETAIL_TEXT_7", "....................................................................<br />Owner's signature of the breeder");
define("LITTER_APPLICATION_DETAIL_TEXT_8", "Apply barcode stickers to stick stickers.");
define("LITTER_APPLICATION_DETAIL_TEXT_9", "<b>Chiping done by:</b><br />(stamp and signature of the authorized veterinarian)
									<br /><br /><br /><br /><br />
									....................................................................");
define("LITTER_APPLICATION_DETAIL_CONTROL", "The result of tattooing, chipping and litter control");
define("LITTER_APPLICATION_DETAIL_INC", "I attach the application");
define("LITTER_APPLICATION_DETAIL_INC_MATING_LIST", "2x cover sheet");
define("LITTER_APPLICATION_DETAIL_INC_MATING_MALE_PREREG", "1x copy of PP male dog with re-registration");
define("LITTER_APPLICATION_DETAIL_INC_MATING_FEMALE_PREREG", "1x copy of PP female dog with re-registration");;
define("LITTER_APPLICATION_DETAIL_INC_MATING_FEES", "confirmation of payment of fees");
define("LITTER_APPLICATION_DETAIL_INC_MATING_PHOTO", "CHS photocopy (first litter only)");
define("LITTER_APPLICATION_DETAIL_INC_MATING_TITLES", "Photocopy of titles");
define("LITTER_APPLICATION_DETAIL_IN", "In");
define("LITTER_APPLICATION_DETAIL_NO", "Record number");
define("LITTER_APPLICATION_DETAIL_CHIP_BLISTER", "Sticker with barcode number of microchip");
define("LITTER_APPLICATION_DETAIL_LITTER_DATE_REQ", "Litter date is mandatory field!");
define("LITTER_APPLICATION_SAVED", "Litter application has been succussfully saved");
define("LITTER_APPLICATION_SAVE_FAILED", "An error occured during savnig. Please double check inserted data and try again.");
define("LITTER_APPLICATION_DOES_NOT_EXIST", "Litter apoplication number %i does not exist!");
define("LITTER_APPLICATION_ADD_NEW", "Add new litter apúplication");
define("LITTER_APPLICATION_GENERATE_PDF", "Save PDF");
define("LITTER_APPLICATION_UNHIDE", "Hide from public");
define("LITTER_APPLICATION_HIDE", "Show in public");
define("LITTER_APPLICATION_HIDE_OK", "Litter application is now private");
define("LITTER_APPLICATION_UNHIDE_OK", "Litter application is now public");
define("LITTER_APPLICATION_HIDE_GENERAL_FAIL", "Privacy saving fails");
define("LITTER_APPLICATION_SAVE_DATE", "Date of insertion");
define("LITTER_APPLICATION_SAVE_REWRITTEN", "Saved");
define("LITTER_APPLICATION_SAVE_UNREWRITTEN", "Not saved");
define("LITTER_APPLICATION_SAVE_ALLREWRITTEN", "All");
define("LITTER_APPLICATION_DELETE", "Delete");
define("LITTER_APPLICATION_DELETE_TITLE", "Delete of litter application");
define("LITTER_APPLICATION_DELETE_INFO", "Are you really want to delete the litter application?");
define("LITTER_APPLICATION_DELETED", "The litter application has been deleted");
define("LITTER_APPLICATION_DELETED_FAILED", "An error occurred. Please try again later");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS", "Introduce descendants into the database");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS_OK", "The descendants were introduced");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS_FAILED", "There was an error while saving the descendants");
define("LITTER_APPLICATION_REWRITE_DESCENDANTS_ALREADY_IN", "WARNING - this litter application has already been processed, duplicates will be created by new introduction!");
define("LITTER_APPLICATION_REWRITE_TATTOO_NO", "Tattoo number");
define("LITTER_APPLICATION_REWRITE_CHIP_NO", "Chip number");
define("LITTER_APPLICATION_REWRITE_PUPPY_NAME", "Puppy name");
define("LITTER_APPLICATION_REWRITE_PUPPIES", "Create descendants into a database");
define("LITTER_APPLICATION_REWRITE_PUPPIES_FUR", "Fur");
define("LITTER_APPLICATION_REWRITE_DOES_NOT_EXIST", "The litter number does not exist");
define("LITTER_APPLICATION_CREATE_SUBJECT", "New litter application has been created");
define("LITTER_APPLICATION_CREATE_BODY", "New liiter application has been created. See details in included form <br /><br /> %s");
define("LITTER_APPLICATION_FOR", "litter report");
define("LITTER_APPLICATION_FOR_WHO", "- for the owner of the bitch");
define("LITTER_APPLICATION_BREEDING", "Kennel");
define("LITTER_APPLICATION_MALE_NAME", "Cover dog name");
define("LITTER_APPLICATION_RECORD_NUM_FORM", "registration number: CMKU/");
define("LITTER_APPLICATION_RECORD_NUM", "registration number");
define("LITTER_APPLICATION_PLACE", "Covering was carried out");
define("LITTER_APPLICATION_BIRTH", "Date of birth");
define("LITTER_APPLICATION_DOG_LIVE", "Number of live puppies");
define("LITTER_APPLICATION_DOG_LIVE_MALE", "of which dogs");
define("LITTER_APPLICATION_DOG_LIVE_FEMALE", "females");
define("LITTER_APPLICATION_DOG_DEATH", "Number of dead births, up to 7 days after birth. dead or killed puppies");
define("LITTER_APPLICATION_PUPPIES_DETAILS", "Birth notes");
define("LITTER_APPLICATION_OWNWER_MALE", "Name and address of cover dog owner");
define("LITTER_APPLICATION_REVIEW", "The owner of the bitch is obliged to send the completed litter report to the herd book manager within 10 days after the litter via email poradcechovu@seznam.cz");
define("LITTER_APPLICATION_EDIT", "Litter application edit");
define("LITTER_APPLICATION_MID_OID_FAILED_TITLE", 'Integrity error. Dog with ID %d does not match for male/female!');
define("MATING_LITTER_DOG_DATE", "dat. of bir.");

define("PUPPY_TABLE_TERM", "Term");
define("PUPPY_TABLE_DETAILS", "Details");
define("PUPPY_TABLE_CONTACT", "Contact");
define("PUPPY_ADD_NEW", "Add");
define("PUPPY_ADD_OK", "Record has been inserted");
define("PUPPY_ADD_FAILED", "En error occured during saving, please, try again");
define("PUPPY_EDIT", "Edit");
define("PUPPY_DELETE", "Delete");
define("PUPPY_DELETE_TITLE", "Delete record");
define("PUPPY_DELETE_BODY", "Are you really want to delete the record?");

define("CHS_TITLE", 'Catteries');
