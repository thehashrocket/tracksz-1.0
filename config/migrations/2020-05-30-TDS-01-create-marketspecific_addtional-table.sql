CREATE TABLE `marketspecific_addtional` (
  `Id` int(11) NOT NULL,
  `ProductId` int(11) DEFAULT NULL,
  `Cost` double(5,2) NOT NULL DEFAULT '0.00',
  `Location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Language` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AdditionalUIEE` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ManufacturerPartNumber` varchar(255) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  `MarketPlaceId` int(11) DEFAULT NULL,
  `StoreId` int(11) DEFAULT NULL,
  `Created` datetime DEFAULT CURRENT_TIMESTAMP,
  `Updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `marketspecific_addtional`
  ADD PRIMARY KEY (`Id`);
ALTER TABLE `marketspecific_addtional`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;