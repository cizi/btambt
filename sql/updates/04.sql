ALTER TABLE `appdata_krycilist`
    CHANGE `oID3` `Poznamka` varchar(255) COLLATE 'utf8_czech_ci' NULL COMMENT 'Volný text pro psa' AFTER `oID2`;

ALTER TABLE `appdata_krycilist`
    ADD `oID3` int NULL AFTER `oID2`;

ALTER TABLE `appdata_krycilist`
    CHANGE `oID3` `oID3` int NULL COMMENT 'ID 3 psa (tabulka psů)' AFTER `oID2`;

ALTER TABLE `appdata_krycilist`
    ADD FOREIGN KEY (`oID3`) REFERENCES `appdata_pes` (`ID`)

