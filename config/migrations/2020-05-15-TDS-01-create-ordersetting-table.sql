CREATE TABLE `ordersettings` ( `Id` int(11) NOT NULL,
         `ConfirmEmail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
         `CancelEmail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
         `DeferEmail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
         `UserId` int(11) DEFAULT NULL,
         `Updated` datetime DEFAULT CURRENT_TIMESTAMP,
         `Created` datetime DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1; ALTER TABLE `ordersettings` ADD PRIMARY KEY (`Id`);ALTER TABLE `ordersettings` MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT; COMMIT;