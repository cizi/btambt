ALTER TABLE `user`
ADD `privacy` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Flag zda uživatel souhlasil s GDPR',
ADD `privacy_tries_count` int NOT NULL DEFAULT '0' COMMENT 'Počet kolikrát jsme se snažili ho souhlasit s GDPR' AFTER `privacy`;