-- Adminer 4.7.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

SET FOREIGN_KEY_CHECKS=0;
SET GLOBAL sql_mode = '';

CREATE DATABASE `btambt` DEFAULT CHARACTER SET utf8;
USE `btambt`;

DELIMITER ;;

DROP FUNCTION IF EXISTS `SPLIT_STR`;;
CREATE FUNCTION `SPLIT_STR`(`x` VARCHAR(255), `delim` VARCHAR(12), `pos` INT) RETURNS varchar(255) CHARSET utf8
BEGIN
    RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
       LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
       delim, '');
END;;

DELIMITER ;

CREATE TABLE `appdata_chovatel` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK identifikátor',
  `uID` int(11) DEFAULT NULL COMMENT 'ID uživatele',
  `pID` int(11) DEFAULT NULL COMMENT 'ID psa',
  PRIMARY KEY (`ID`),
  KEY `uID` (`uID`,`pID`),
  KEY `pID` (`pID`),
  CONSTRAINT `appdata_chovatel_ibfk_1` FOREIGN KEY (`uID`) REFERENCES `user` (`id`),
  CONSTRAINT `appdata_chovatel_ibfk_2` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_krycilist` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `Datum` date NOT NULL COMMENT 'Datum založení záznamu',
  `oID` int(11) DEFAULT NULL COMMENT 'ID psa (tabulka psů)',
  `mID` int(11) DEFAULT NULL COMMENT 'ID feny (tabulka psů)',
  `DatumKryti` date NOT NULL COMMENT 'Datum krytí',
  `Data` longtext COLLATE utf8_czech_ci NOT NULL COMMENT 'Post data formuláře',
  `Formular` longtext COLLATE utf8_czech_ci NOT NULL COMMENT 'HTML data formuláře',
  `Zavedeno` tinyint(4) NOT NULL COMMENT 'Flag stavu ',
  `Plemeno` int(11) DEFAULT NULL COMMENT 'Číselník 7',
  `Klub` varchar(20) COLLATE utf8_czech_ci NOT NULL COMMENT 'Klub textově',
  `MajitelFeny` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'ID uživatele',
  PRIMARY KEY (`ID`),
  KEY `oID` (`oID`),
  KEY `mID` (`mID`),
  KEY `MajitelFeny` (`MajitelFeny`),
  KEY `Plemeno` (`Plemeno`),
  CONSTRAINT `appdata_krycilist_ibfk_1` FOREIGN KEY (`oID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_krycilist_ibfk_2` FOREIGN KEY (`mID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_krycilist_ibfk_4` FOREIGN KEY (`Plemeno`) REFERENCES `enum_item` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Data krycího listu';


CREATE TABLE `appdata_majitel` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK identifikátor',
  `uID` int(11) DEFAULT NULL COMMENT 'ID uživatele',
  `pID` int(11) DEFAULT NULL COMMENT 'ID psa',
  `Soucasny` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag zdali jde o současného majitele',
  PRIMARY KEY (`ID`),
  KEY `uID` (`uID`,`pID`),
  KEY `pID` (`pID`),
  CONSTRAINT `appdata_majitel_ibfk_1` FOREIGN KEY (`uID`) REFERENCES `user` (`id`),
  CONSTRAINT `appdata_majitel_ibfk_2` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_pes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TitulyPredJmenem` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `TitulyZaJmenem` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `Jmeno` varchar(100) COLLATE utf8_czech_ci DEFAULT NULL,
  `DatNarozeni` date DEFAULT '0000-00-00',
  `DatUmrti` date DEFAULT '0000-00-00',
  `UmrtiKomentar` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `Pohlavi` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 8 (tabulka enum_item)',
  `Plemeno` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 7 (tabulka enum_item)',
  `Barva` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 4 (tabulka enum_item)',
  `Srst` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 11 (tabulka enum_item)',
  `BarvaKomentar` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `CisloZapisu` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `PCisloZapisu` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `Cip` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `Tetovani` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `ZdravotniKomentar` text COLLATE utf8_czech_ci,
  `Varlata` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 12 (tabulka enum_item)',
  `Skus` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 10 (tabulka enum_item)',
  `Zuby` int(11) DEFAULT NULL COMMENT 'TOHLE SE ASI NEPOUZIVA',
  `ZubyKomentar` text COLLATE utf8_czech_ci,
  `Chovnost` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 5 (tabulka enum_item)',
  `ChovnyKomentar` text COLLATE utf8_czech_ci,
  `Posudek` text COLLATE utf8_czech_ci,
  `Zkousky` text COLLATE utf8_czech_ci,
  `ZkouskySlozene` text COLLATE utf8_czech_ci,
  `TitulyKomentar` text COLLATE utf8_czech_ci,
  `Oceneni` text COLLATE utf8_czech_ci,
  `Zavody` text COLLATE utf8_czech_ci,
  `oID` int(11) DEFAULT NULL COMMENT 'Odkaz do téže tabulky (otec ID)',
  `mID` int(11) DEFAULT NULL COMMENT 'Odkaz do téže tabulky (matka ID)',
  `Komentar` text COLLATE utf8_czech_ci,
  `PosledniZmena` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Vyska` decimal(10,2) DEFAULT '0.00',
  `Vaha` decimal(10,2) DEFAULT '0.00',
  `Bonitace` varchar(100) COLLATE utf8_czech_ci DEFAULT '',
  `ImpFrom` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  `ImpID` int(11) DEFAULT NULL,
  `oIDupdate` tinyint(4) DEFAULT '0',
  `mIDupdate` tinyint(4) DEFAULT '0',
  `Stav` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Status psa (0-nekativní, nutno schválit; 1-aktivní; 2-smazaný)',
  `KontrolaVrhu` longtext COLLATE utf8_czech_ci COMMENT 'Poznámka u psa v době propsání do vrhu do DB',
  `Zeme` int(11) DEFAULT NULL,
  `SkrytPotomky` tinyint(4) DEFAULT '0',
  `SkrytSourozence` tinyint(4) DEFAULT '0',
  `SkrytCelouKartu` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Jmeno` (`Jmeno`,`oID`,`mID`),
  KEY `posledniZmena` (`PosledniZmena`),
  KEY `ImpFrom` (`ImpFrom`),
  KEY `oID` (`oID`),
  KEY `mID` (`mID`),
  KEY `Jmeno_2` (`Jmeno`),
  KEY `PCisloZapisu` (`PCisloZapisu`),
  KEY `oIDupdate` (`oIDupdate`),
  KEY `mIDupdate` (`mIDupdate`),
  KEY `Pohlavi` (`Pohlavi`),
  KEY `Plemeno` (`Plemeno`),
  KEY `Barva` (`Barva`),
  KEY `Srst` (`Srst`),
  KEY `Varlata` (`Varlata`),
  KEY `Skus` (`Skus`),
  KEY `Chovnost` (`Chovnost`),
  CONSTRAINT `appdata_pes_ibfk_1` FOREIGN KEY (`Pohlavi`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_pes_ibfk_2` FOREIGN KEY (`Plemeno`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_pes_ibfk_3` FOREIGN KEY (`Barva`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_pes_ibfk_4` FOREIGN KEY (`Srst`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_pes_ibfk_5` FOREIGN KEY (`Varlata`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_pes_ibfk_6` FOREIGN KEY (`Skus`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_pes_ibfk_7` FOREIGN KEY (`Chovnost`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_pes_ibfk_8` FOREIGN KEY (`oID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_pes_ibfk_9` FOREIGN KEY (`mID`) REFERENCES `appdata_pes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_pes_obrazky` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK - identifikátor',
  `pID` int(11) NOT NULL COMMENT 'ID psa',
  `cesta` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'cesta k obrázku',
  `vychozi` tinyint(1) DEFAULT '0' COMMENT 'Jde o výchozí obrázek (0-ne; 1-ano)',
  PRIMARY KEY (`id`),
  KEY `pID` (`pID`),
  CONSTRAINT `appdata_pes_obrazky_ibfk_2` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_pes_soubory` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK - identifikátor',
  `pID` int(11) NOT NULL COMMENT 'ID psa',
  `cesta` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'cesta k obrázku',
  `typ` tinyint(2) DEFAULT '0' COMMENT 'Typ souboru',
  PRIMARY KEY (`id`),
  KEY `pID` (`pID`),
  CONSTRAINT `appdata_pes_soubory_ibfk_2` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_prihlaska` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `Datum` date DEFAULT NULL COMMENT 'Datum založení záznamu',
  `oID` int(11) DEFAULT NULL COMMENT 'Otec pes ID',
  `mID` int(11) DEFAULT NULL COMMENT 'Matka pes ID',
  `DatumNarozeni` date DEFAULT NULL COMMENT 'Datum narození',
  `Data` longtext COLLATE utf8_czech_ci NOT NULL COMMENT 'Data - base64_encode(gzdeflate(serialize($_POST)))',
  `Formular` longtext COLLATE utf8_czech_ci NOT NULL COMMENT 'Formular = base64_encode(gzdeflate($contents))',
  `Zavedeno` tinyint(4) NOT NULL DEFAULT '0',
  `Plemeno` int(11) DEFAULT NULL COMMENT 'Uživatelský číselník 7 (tabulka enum_item)',
  `Klub` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Klub textově',
  `MajitelFeny` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT '	ID uživatele',
  PRIMARY KEY (`ID`),
  KEY `oID` (`oID`),
  KEY `mID` (`mID`),
  KEY `Plemeno` (`Plemeno`),
  CONSTRAINT `appdata_prihlaska_ibfk_1` FOREIGN KEY (`oID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_prihlaska_ibfk_2` FOREIGN KEY (`mID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_prihlaska_ibfk_3` FOREIGN KEY (`Plemeno`) REFERENCES `enum_item` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_rozhodci` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `Jmeno` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Jméno',
  `Prijmeni` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Přijímení',
  `TitulyPrefix` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Titul před',
  `TitulySuffix` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Titul za',
  `Ulice` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Ulice ',
  `Mesto` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Město',
  `PSC` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'PSČ',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_stenata` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `Plemeno` int(11) DEFAULT NULL COMMENT 'Plemeno',
  `mID` int(11) NOT NULL COMMENT 'ID matky',
  `oID` int(11) NOT NULL COMMENT 'ID otce',
  `uID` int(11) DEFAULT NULL COMMENT 'ID uživatel',
  `Termin` date DEFAULT NULL COMMENT 'Termín kdy budou k dispozici',
  `Podrobnosti` longtext COLLATE utf8_czech_ci COMMENT 'Text, detaily',
  `Kontakt` longtext COLLATE utf8_czech_ci,
  PRIMARY KEY (`ID`),
  KEY `mID` (`mID`),
  KEY `oID` (`oID`),
  KEY `Plemeno` (`Plemeno`),
  KEY `uID` (`uID`),
  CONSTRAINT `appdata_stenata_ibfk_1` FOREIGN KEY (`mID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_stenata_ibfk_2` FOREIGN KEY (`oID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_stenata_ibfk_3` FOREIGN KEY (`Plemeno`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_stenata_ibfk_4` FOREIGN KEY (`uID`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka inzerce štěňat';


CREATE TABLE `appdata_veterinar` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `Jmeno` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Jméno veterináře',
  `Prijmeni` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Přijímení (název) veterináře',
  `TitulyPrefix` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Titul před',
  `TitulySuffix` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Titul za',
  `Ulice` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Ulice ',
  `Mesto` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Město',
  `PSC` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'PSČ',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_vystava` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `Typ` int(11) NOT NULL COMMENT 'Odkaz do číselníku 19',
  `Datum` date NOT NULL DEFAULT '0000-00-00',
  `Nazev` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '' COMMENT 'Název výstavy',
  `Misto` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '' COMMENT 'Místo výstavy',
  `Hotovo` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Flag zda je výstava již hotova',
  `Rozhodci` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Dříve odkaz do rozhodnčíchm nyní jejich seznam oddělený tildou',
  PRIMARY KEY (`ID`),
  KEY `Typ` (`Typ`),
  KEY `Rozhodci` (`Rozhodci`),
  CONSTRAINT `appdata_vystava_ibfk_1` FOREIGN KEY (`Typ`) REFERENCES `enum_item` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_vystava_pes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `vID` int(11) DEFAULT NULL COMMENT 'ID výstavy',
  `pID` int(11) DEFAULT NULL COMMENT 'ID psa',
  `Trida` int(11) DEFAULT NULL COMMENT 'Třída - číselník 20',
  `Oceneni` int(11) DEFAULT NULL COMMENT 'Ocenění - číselník 21',
  `Poradi` int(11) DEFAULT NULL COMMENT 'Pořadí - číselník 22',
  `Titul` int(11) DEFAULT NULL COMMENT 'Titul - číselník 23',
  `TitulyDodatky` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Textový dodatek k titulu',
  PRIMARY KEY (`ID`),
  KEY `vID` (`vID`),
  KEY `pID` (`pID`),
  KEY `Trida` (`Trida`),
  KEY `Oceneni` (`Oceneni`),
  KEY `Poradi` (`Poradi`),
  KEY `Titul` (`Titul`),
  CONSTRAINT `appdata_vystava_pes_ibfk_1` FOREIGN KEY (`vID`) REFERENCES `appdata_vystava` (`ID`),
  CONSTRAINT `appdata_vystava_pes_ibfk_2` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_vystava_pes_ibfk_3` FOREIGN KEY (`Trida`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_vystava_pes_ibfk_4` FOREIGN KEY (`Oceneni`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_vystava_pes_ibfk_5` FOREIGN KEY (`Poradi`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_vystava_pes_ibfk_6` FOREIGN KEY (`Titul`) REFERENCES `enum_item` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Vztah výstavy k psům';


CREATE TABLE `appdata_vystava_rozhodci` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifikátor',
  `vID` int(11) DEFAULT NULL COMMENT 'ID výstavy',
  `rID` int(11) DEFAULT NULL COMMENT 'ID rozhodčího',
  `Trida` int(11) DEFAULT NULL COMMENT 'Třída - číselník 20',
  `Plemeno` int(11) DEFAULT NULL COMMENT 'Plemeno - číselník 7',
  PRIMARY KEY (`ID`),
  KEY `Trida` (`Trida`),
  KEY `Plemeno` (`Plemeno`),
  KEY `vID` (`vID`),
  KEY `rID` (`rID`),
  CONSTRAINT `appdata_vystava_rozhodci_ibfk_3` FOREIGN KEY (`Trida`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_vystava_rozhodci_ibfk_4` FOREIGN KEY (`Plemeno`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_vystava_rozhodci_ibfk_5` FOREIGN KEY (`vID`) REFERENCES `appdata_vystava` (`ID`),
  CONSTRAINT `appdata_vystava_rozhodci_ibfk_6` FOREIGN KEY (`rID`) REFERENCES `appdata_rozhodci` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Vztah výstavy k soudci';


CREATE TABLE `appdata_zdravi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `pID` int(11) NOT NULL COMMENT 'ID psa',
  `Typ` int(11) NOT NULL COMMENT 'Uživatelský číselník 14 (tabulka enum_item)',
  `Vysledek` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Výsledek vyšetření',
  `Komentar` text COLLATE utf8_czech_ci NOT NULL COMMENT 'Komentář',
  `Datum` date DEFAULT '0000-00-00' COMMENT 'Datum vyšetření',
  `Veterinar` int(11) DEFAULT NULL COMMENT 'Odkaz do tabulky veterinařů',
  PRIMARY KEY (`ID`),
  KEY `pID` (`pID`),
  KEY `Typ` (`Typ`),
  KEY `Veterinar` (`Veterinar`),
  CONSTRAINT `appdata_zdravi_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_zdravi_ibfk_2` FOREIGN KEY (`Typ`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_zdravi_ibfk_3` FOREIGN KEY (`Veterinar`) REFERENCES `appdata_veterinar` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `appdata_zmeny` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pID` int(11) DEFAULT NULL COMMENT 'Pes ID',
  `uID` int(11) DEFAULT NULL COMMENT 'ID uživatele požadující změnu',
  `datimVlozeno` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp vložení požadavku',
  `aktualniHodnota` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Hodnota aktuální',
  `pozadovanaHodnota` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Požadovaná nová honota',
  `uIDKdoSchvalil` int(11) DEFAULT NULL COMMENT 'ID uživatele, který odpoveděl na žádost',
  `datimZpracovani` timestamp NULL DEFAULT NULL COMMENT 'Kdy byla žádost zpracována',
  `stav` tinyint(2) NOT NULL COMMENT 'Stav požadavku (0-nový; 1-schválený; 2-odmítnutý)',
  `tabulka` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Tabulka do které změny promítnout',
  `sloupec` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Sloupec změny',
  `cID` int(11) DEFAULT NULL COMMENT 'Číslo číselníku',
  `zID` int(11) DEFAULT NULL COMMENT 'ID záznamu v jiné tabulce',
  PRIMARY KEY (`ID`),
  KEY `pID` (`pID`),
  KEY `uID` (`uID`),
  KEY `uIDKdoSchvalil` (`uIDKdoSchvalil`),
  CONSTRAINT `appdata_zmeny_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_zmeny_ibfk_2` FOREIGN KEY (`uID`) REFERENCES `user` (`id`),
  CONSTRAINT `appdata_zmeny_ibfk_3` FOREIGN KEY (`uIDKdoSchvalil`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Tabulka pro požadavky změn uživatelů na psech';


CREATE TABLE `block` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Block ID',
  `background_color` varchar(50) COLLATE utf8_czech_ci NOT NULL COMMENT 'Content background color',
  `color` varchar(50) COLLATE utf8_czech_ci NOT NULL COMMENT 'Font color',
  `width` varchar(50) COLLATE utf8_czech_ci NOT NULL COMMENT 'Width of block',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `block_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of record',
  `block_id` int(11) NOT NULL COMMENT 'ID of block',
  `lang` varchar(5) COLLATE utf8_czech_ci NOT NULL COMMENT 'Lang of content',
  `content` longtext COLLATE utf8_czech_ci NOT NULL COMMENT 'Text content',
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_id_lang` (`block_id`,`lang`),
  CONSTRAINT `block_content_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `block` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `enum_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `description` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT 'USER ENUM' COMMENT 'Popis (nevyužíváno)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `enum_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `enum_header_id` int(11) NOT NULL COMMENT 'ID číselníku',
  `lang` varchar(5) COLLATE utf8_czech_ci NOT NULL COMMENT 'Jazyk položky',
  `item` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Hodnota položky číselníku',
  `order` int(11) NOT NULL COMMENT 'ID společných položek',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_order` (`lang`,`order`),
  KEY `enum_header_id` (`enum_header_id`),
  KEY `order` (`order`),
  CONSTRAINT `enum_item_ibfk_1` FOREIGN KEY (`enum_header_id`) REFERENCES `enum_header` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `enum_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `enum_header_id` int(11) NOT NULL COMMENT 'ID číselníku',
  `lang` varchar(5) COLLATE utf8_czech_ci NOT NULL COMMENT 'Jazyk ',
  `description` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Popis číselníku v odpovídající jazyce',
  PRIMARY KEY (`id`),
  KEY `enum_header_id` (`enum_header_id`),
  CONSTRAINT `enum_translation_ibfk_1` FOREIGN KEY (`enum_header_id`) REFERENCES `enum_header` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of record (needed in subitems)',
  `lang` varchar(5) COLLATE utf8_czech_ci NOT NULL COMMENT 'Language shortcut',
  `link` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Link to web',
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Frontend title',
  `alt` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Alt on hover',
  `level` int(11) NOT NULL COMMENT 'Level nesting',
  `order` int(11) NOT NULL COMMENT 'Order in menu',
  `submenu` int(11) NOT NULL COMMENT 'ID of this menu item',
  `visible` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Item visible on frontend',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_link` (`lang`,`link`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `page_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `menu_item_id` int(11) NOT NULL COMMENT 'ID of men item',
  `block_id` int(11) NOT NULL COMMENT 'ID of block',
  `order` int(11) NOT NULL COMMENT 'Order of item in',
  PRIMARY KEY (`id`),
  KEY `menu_item_id` (`menu_item_id`),
  KEY `block_id` (`block_id`),
  CONSTRAINT `page_content_ibfk_1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `shared_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `path` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Cesta k souboru',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `slider_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `path` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Cesta k souboru',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `slider_setting` (
  `id` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'ID položky (inputu)',
  `value` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Uložená hodnota',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `email` varchar(255) NOT NULL COMMENT 'Přihlašovací jméno (email)',
  `password` char(255) NOT NULL COMMENT 'Heslo',
  `role` int(2) NOT NULL COMMENT 'Role v číselném vyjádření',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Je uživatel aktivní?',
  `register_timestamp` datetime NOT NULL COMMENT 'Časová známka registrace',
  `last_login` datetime NOT NULL COMMENT 'Poslední přihlášení',
  `title_before` varchar(255) DEFAULT NULL COMMENT 'Titul před jménem',
  `name` varchar(255) NOT NULL COMMENT 'Jméno',
  `surname` varchar(255) NOT NULL COMMENT 'Přijmení',
  `title_after` varchar(255) DEFAULT NULL COMMENT 'Titul za jménem',
  `street` varchar(255) DEFAULT NULL COMMENT 'Ulice',
  `city` varchar(255) DEFAULT NULL COMMENT 'Město',
  `zip` int(11) DEFAULT NULL COMMENT 'PSČ',
  `state` varchar(100) DEFAULT NULL COMMENT 'Stát číselník PHP',
  `web` varchar(255) DEFAULT NULL COMMENT 'Webovky',
  `phone` varchar(100) DEFAULT NULL COMMENT 'Telefon',
  `fax` varchar(100) DEFAULT NULL COMMENT 'Fax',
  `station` varchar(255) DEFAULT NULL COMMENT 'Ch. stanice',
  `sharing` int(11) DEFAULT NULL COMMENT 'Informace pro sdíelní (user enum 9)',
  `breed` varchar(255) DEFAULT NULL COMMENT 'Plemena oddělená tildou (bývalo Plemeno (user enum 7))',
  `club` int(11) DEFAULT NULL COMMENT 'Chovatelský kub (user enum 17)',
  `clubNo` varchar(255) DEFAULT NULL COMMENT 'Členské číslo',
  `news` tinyint(1) DEFAULT NULL COMMENT 'Flag zda novinky emailem',
  `deleted` tinyint(4) DEFAULT '0' COMMENT 'Flag zde je uživatel smazán',
  `privacy` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Flag zda uživatel souhlasil s GDPR',
  `privacy_tries_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Počet kolikrát jsme se snažili ho souhlasit s GDPR',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`email`),
  KEY `breed` (`breed`),
  KEY `sharing` (`sharing`),
  KEY `club` (`club`),
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`sharing`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `user_ibfk_3` FOREIGN KEY (`club`) REFERENCES `enum_item` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `web_config` (
  `id` varchar(255) COLLATE utf8_czech_ci NOT NULL COMMENT 'Identifikace položky (název inputu)',
  `lang` varchar(5) COLLATE utf8_czech_ci NOT NULL COMMENT 'Identifikace jazyka',
  `value` text COLLATE utf8_czech_ci NOT NULL COMMENT 'Uložená hodnota',
  UNIQUE KEY `lang_id` (`lang`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

SET FOREIGN_KEY_CHECKS=1;
-- 2020-05-07 11:18:04