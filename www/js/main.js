$(function(){

});

function langChangeRedir(url) {
	var newLang = $("#languageSwitcher").val();
	location.assign(url + "/" + newLang);
}

// Vytvoreni linku z jmena stranky - misto #doplnte#
link_changed = false;
// zajistuje autovyplneni pole link-name po vyplneni name
function generateURL(el) {
	if(!link_changed) {
		var forElement = $(el).attr("validation-for");
		str = truncateString(trim($(el).val()));
		$("#" + forElement).val(str);
	}
}

function linkChanged() {
	link_changed = true;
}

/**
 * Funkce ma jako vstup unicode retezec a jako vystup vyhodi ten samy, ale malymi pismeny
 * bez diakritiky, nealfanumericky znaky nahrazeny "_"
 * Pokud je mode == 1, pak ponechava i tecky a orizne delku na 31 znaku
 * Vystupem by tedy mel byt validni link-name
 *
 */
function truncateString(str) {
	// UTF8 "ěščřžýáíéťúůóď�?ľĺ"
	convFromL = String.fromCharCode(283,353,269,345,382,253,225,237,233,357,367,250,243,271,328,318,314);
	// UTF8 "escrzyaietuuodnll"
	convToL = String.fromCharCode(101,115,99,114,122,121,97,105,101,116,117,117,111,100,110,108,108);

	// zmenseni a odstraneni diakritiky
	str = str.toLowerCase();
	str = strtr(str,convFromL,convToL);

	// jakykoliv nealfanumericky znak (nepouzit \W ci \w, protoze jinak tam necha treba "ďż˝")
	preg = /[^0-9A-Za-z]{1,}?/g;

	// odstraneni nealfanumerickych znaku (pripadne je tolerovana tecka)
	str = trim(str.replace(preg, ' '));
	str = str.replace(/[\s]+/g, '-');

	return str;
}

/**
 * Funkce strtr odpovida teto funkci z PHP
 */
function strtr(s, from, to) {
	out = new String();
	// slow but simple :^)
	top:
		for(i=0; i < s.length; i++) {
			for(j=0; j < from.length; j++) {
				if(s.charAt(i) == from.charAt(j)) {
					out += to.charAt(j);
					continue top;
				}
			}
			out += s.charAt(i);
		}
	return out;
}

function trim(string) {
	//var re= /^\s|\s$/g;
	var re= /^\s*|\s*$/g;
	return string.replace(re,"");
}
