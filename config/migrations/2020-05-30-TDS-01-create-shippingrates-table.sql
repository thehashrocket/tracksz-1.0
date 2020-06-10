CREATE TABLE `ebay_shipping_rates` ( `Id` int(11) NOT NULL, `ProductId` int(11) DEFAULT NULL, `Domestic` double(5,2) NOT NULL DEFAULT '0.00', `International` double(5,2) NOT NULL DEFAULT '0.00',
         `UserId` int(11) DEFAULT NULL,
         `MarketPlaceId` int(11) DEFAULT NULL,
         `StoreId` int(11) DEFAULT NULL,
         `Created` datetime DEFAULT CURRENT_TIMESTAMP,
         `Updated` datetime DEFAULT CURRENT_TIMESTAMP ) ENGINE=InnoDB DEFAULT CHARSET=latin1; ALTER TABLE `ebay_shipping_rates` ADD PRIMARY KEY (`Id`); ALTER TABLE `ebay_shipping_rates` MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT; COMMIT;