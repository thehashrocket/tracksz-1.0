USE `Tracksz`;

CREATE TABLE `marketplace` (
  `Id` int(11) NOT NULL,
  `MarketName` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EmailAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SellerID` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FtpUserId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FtpPassword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PrependVenue` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `AppendVenue` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IncreaseMinMarket` double(8,5) DEFAULT NULL,
  `FileFormat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FtpAppendVenue` int(11) DEFAULT NULL,
  `SuspendExport` tinyint(1) DEFAULT NULL,
  `SendDeletes` tinyint(1) DEFAULT NULL,
  `MarketAcceptPrice` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MarketAcceptPriceVal` double(8,5) DEFAULT NULL,
  `MarketAcceptPriceValMulti` double(8,5) DEFAULT NULL,
  `MarketSpecificPrice` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MarketAcceptPriceVal2` double(8,5) DEFAULT NULL,
  `MarketAcceptPriceValMulti2` double(8,5) DEFAULT NULL,
  `Updated` datetime DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;