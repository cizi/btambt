INSERT INTO `enum_header` (`id`, `description`) VALUES
(26,	'USER ENUM');

INSERT INTO `enum_translation` (`id`, `enum_header_id`, `lang`, `description`) VALUES
(57,	26,	'cs',	'Zkoušky'),
(58,	26,	'en',	'Exams');

INSERT INTO `enum_item` (`id`, `enum_header_id`, `lang`, `item`, `order`) VALUES
(484,	26,	'cs',	'ZV- zkoušky vloh nelze zadat CACT',	222),
(485,	26,	'en',	'ZV- zkoušky vloh nelze zadat CACT',	222),
(486,	26,	'cs',	'BZ- Barvářské zkoušky',	223),
(487,	26,	'en',	'BZ- Barvářské zkoušky',	223),
(488,	26,	'cs',	'ZN- Norování pro MBT nelze zadat CACT',	224),
(489,	26,	'en',	'ZN- Norování pro MBT nelze zadat CACT',	224),
(490,	26,	'cs',	'BZH- Barvářské zkoušky honičú',	225),
(491,	26,	'en',	'BZH- Barvářské zkoušky honičú',	225),
(492,	26,	'cs',	'LZ -Lesní zkoušky',	226),
(493,	26,	'en',	'LZ -Lesní zkoušky',	226),
(494,	26,	'cs',	'VZ- Všestranné zkoušky',	227),
(495,	26,	'en',	'VZ- Všestranné zkoušky',	227),
(496,	26,	'cs',	'VP- Vodní práce- nelze zadat CACT',	228),
(497,	26,	'en',	'VP- Vodní práce- nelze zadat CACT',	228),
(498,	26,	'cs',	'HZ- Honičské zkoušky',	229),
(499,	26,	'en',	'HZ- Honičské zkoušky',	229),
(500,	26,	'cs',	'PZ- Podzimní zkoušky',	230),
(501,	26,	'en',	'PZ- Podzimní zkoušky',	230),
(502,	26,	'cs',	'ZVVZ - Zkoušky vyhánění',	231),
(503,	26,	'en',	'ZVVZ - Zkoušky vyhánění',	231);


DROP TABLE IF EXISTS `appdata_pes_zkousky`;
CREATE TABLE `appdata_pes_zkousky` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `pID` int DEFAULT NULL,
  `zID` int DEFAULT NULL COMMENT 'Order z číselníku 26',
  PRIMARY KEY (`ID`),
  KEY `pID` (`pID`),
  CONSTRAINT `appdata_pes_zkousky_ibfk_1` FOREIGN KEY (`pID`) REFERENCES `appdata_pes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


