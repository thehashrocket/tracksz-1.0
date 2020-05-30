CREATE TABLE `marketprice_master` (
  `Id` int(11) NOT NULL,
  `ProductId` int(11) DEFAULT NULL,
  `AbeBooks` double(5,2) NOT NULL DEFAULT '0.00',
  `Alibris` double(5,2) NOT NULL DEFAULT '0.00',
  `Amazon` double(5,2) NOT NULL DEFAULT '0.00',
  `AmazonEurope` double(5,2) NOT NULL DEFAULT '0.00',
  `BarnesAndNoble` double(5,2) NOT NULL DEFAULT '0.00',
  `Biblio` double(5,2) NOT NULL DEFAULT '0.00',
  `Chrislands` double(5,2) NOT NULL DEFAULT '0.00',
  `eBay` double(5,2) NOT NULL DEFAULT '0.00',
  `eCampus` double(5,2) NOT NULL DEFAULT '0.00',
  `TextbookRush` double(5,2) NOT NULL DEFAULT '0.00',
  `TextbookX` double(5,2) NOT NULL DEFAULT '0.00',
  `Valore` double(5,2) NOT NULL DEFAULT '0.00',
  `UserId` int(11) DEFAULT NULL,
  `MarketPlaceId` int(11) DEFAULT NULL,
  `StoreId` int(11) DEFAULT NULL,
  `Created` datetime DEFAULT CURRENT_TIMESTAMP,
  `Updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `marketprice_master`
  ADD PRIMARY KEY (`Id`);
  ALTER TABLE `marketprice_master`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;