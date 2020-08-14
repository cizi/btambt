SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

INSERT INTO `enum_header` (`id`, `description`) VALUES
(26,	'USER ENUM');

INSERT INTO `enum_translation` (`id`, `enum_header_id`, `lang`, `description`) VALUES
(57,	26,	'cs',	'Zkoušky'),
(58,	26,	'en',	'Exams');

INSERT INTO `enum_item` (`id`, `enum_header_id`, `lang`, `item`, `order`) VALUES
(null,	26,	'cs',	'ZV- zkoušky vloh nelze zadat CACT',	222),
(null,	26,	'en',	'ZV- zkoušky vloh nelze zadat CACT',	222),
(null,	26,	'cs',	'BZ- Barvářské zkoušky',	223),
(null,	26,	'en',	'BZ- Barvářské zkoušky',	223),
(null,	26,	'cs',	'ZN- Norování pro MBT nelze zadat CACT',	224),
(null,	26,	'en',	'ZN- Norování pro MBT nelze zadat CACT',	224),
(null,	26,	'cs',	'BZH- Barvářské zkoušky honičú',	225),
(null,	26,	'en',	'BZH- Barvářské zkoušky honičú',	225),
(null,	26,	'cs',	'LZ -Lesní zkoušky',	226),
(null,	26,	'en',	'LZ -Lesní zkoušky',	226),
(null,	26,	'cs',	'VZ- Všestranné zkoušky',	227),
(null,	26,	'en',	'VZ- Všestranné zkoušky',	227),
(null,	26,	'cs',	'VP- Vodní práce- nelze zadat CACT',	228),
(null,	26,	'en',	'VP- Vodní práce- nelze zadat CACT',	228),
(null,	26,	'cs',	'HZ- Honičské zkoušky',	229),
(null,	26,	'en',	'HZ- Honičské zkoušky',	229),
(null,	26,	'cs',	'PZ- Podzimní zkoušky',	230),
(null,	26,	'en',	'PZ- Podzimní zkoušky',	230),
(null,	26,	'cs',	'ZVVZ - Zkoušky vyhánění',	231),
(null,	26,	'en',	'ZVVZ - Zkoušky vyhánění',	231);


CREATE TABLE `appdata_pes_zkousky` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `pID` int DEFAULT NULL,
  `zID` int DEFAULT NULL COMMENT 'Order z číselníku 26',
  PRIMARY KEY (`ID`),
  KEY `pID` (`pID`),
  CONSTRAINT `appdata_pes_zkousky_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `appdata_krycilist`;
CREATE TABLE `appdata_krycilist` (
  `ID` int NOT NULL AUTO_INCREMENT COMMENT 'ID záznamu',
  `uID` int DEFAULT NULL COMMENT 'ID uživatele',
  `mID` int DEFAULT NULL COMMENT 'ID feny (tabulka psů)',
  `oID1` int DEFAULT NULL COMMENT 'ID 1 psa (tabulka psů)',
  `oID2` int DEFAULT NULL COMMENT 'ID 2 psa (tabulka psů)',
  `oID3` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Volný text pro psa',
  `Datum` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Datum uživatele',
  `DatumVytvoreni` date NOT NULL COMMENT 'DatumVytvoření',
  `CisloKL` varchar(20) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Přidělené číslo KL',
  `Plemeno` int DEFAULT NULL COMMENT 'Číselník 18 - název klubu pro krycí listy',
  `Expresni` tinyint(1) DEFAULT '0' COMMENT 'Jde o expresní KL',
  PRIMARY KEY (`ID`),
  KEY `oID` (`oID1`),
  KEY `mID` (`mID`),
  KEY `Plemeno` (`Plemeno`),
  KEY `oID2` (`oID2`),
  KEY `oID3` (`oID3`),
  KEY `uID` (`uID`),
  CONSTRAINT `appdata_krycilist_ibfk_1` FOREIGN KEY (`oID1`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_krycilist_ibfk_2` FOREIGN KEY (`mID`) REFERENCES `appdata_pes` (`ID`),
  CONSTRAINT `appdata_krycilist_ibfk_4` FOREIGN KEY (`Plemeno`) REFERENCES `enum_item` (`order`),
  CONSTRAINT `appdata_krycilist_ibfk_5` FOREIGN KEY (`oID2`) REFERENCES `appdata_pes` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `appdata_krycilist_ibfk_7` FOREIGN KEY (`uID`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Data krycího listu';


CREATE TABLE `appdata_krycilist_prilohy` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `kID` int DEFAULT NULL,
  `cesta` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `kID` (`kID`),
  CONSTRAINT `appdata_krycilist_prilohy_ibfk_1` FOREIGN KEY (`kID`) REFERENCES `appdata_krycilist` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `appdata_pes`
ADD `SkrytSvod` tinyint NULL DEFAULT '0';

SET foreign_key_checks = 1;