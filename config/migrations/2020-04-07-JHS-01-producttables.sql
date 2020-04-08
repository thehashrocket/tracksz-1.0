DROP TABLE IF EXISTS `CustomerGroup`;
CREATE TABLE `CustomerGroup` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Approval` int(1) NOT NULL,
  `SortOrder` int(3) NOT NULL,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `Product`;
CREATE TABLE `Product` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `StoreProductId` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Tag` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `MetaTitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `MetaDescription` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `MetaKeyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Model` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Sku` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Upc` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Ean` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Jan` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Isbn` varchar(17) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Mpn` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Location` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Quantity` int(4) NOT NULL DEFAULT '1',
  `StockStatusId` int(11) NOT NULL DEFAULT '0',
  `ManufacturerId` int(11) NOT NULL DEFAULT '0',
  `Shipping` tinyint(1) NOT NULL DEFAULT '1',
  `Price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `Points` int(8) NOT NULL DEFAULT '0',
  `TaxClassId` int(11) NOT NULL DEFAULT '0',
  `DateAvailable` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Weight` decimal(15,8) NOT NULL DEFAULT '0.00000000',
  `WeightClassId` int(11) NOT NULL DEFAULT '0',
  `Length` decimal(15,8) NOT NULL DEFAULT '0.00000000',
  `Width` decimal(15,8) NOT NULL DEFAULT '0.00000000',
  `Height` decimal(15,8) NOT NULL DEFAULT '0.00000000',
  `LengthClassId` int(11) NOT NULL DEFAULT '0',
  `Subtract` tinyint(1) NOT NULL DEFAULT '1',
  `Minimum` int(11) NOT NULL DEFAULT '1',
  `SortOrder` int(11) NOT NULL DEFAULT '0',
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `Viewed` int(11) NOT NULL DEFAULT '0',
  `StoreId` int(11) NOT NULL DEFAULT '0',
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `StoreId` (`StoreId`),
  KEY `DateAvailabe` (`DateAvailable`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductAttribute`;
CREATE TABLE `ProductAttribute` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SortOrder` int(3) NOT NULL,
  `StoreId` int(11) NOT NULL DEFAULT '0',
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `StoreId` (`StoreId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductHasAttribute`;
CREATE TABLE `ProductHasAttribute` (
  `ProductId` int(11) NOT NULL,
  `ProductAttributeId` int(11) NOT NULL,
  `StoreId` int(11) NOT NULL DEFAULT '0',
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductId`,`ProductAttributeId`),
  KEY `StoreId` (`StoreId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductCategory`;
CREATE TABLE `ProductCategory` (
  `ProductId` int(11) NOT NULL,
  `CategoryId` int(11) NOT NULL,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductId`,`CategoryId`),
  KEY `CategoryId` (`CategoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductDiscount`;
CREATE TABLE `ProductDiscount` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductId` int(11) NOT NULL,
  `CustomerGroupId` int(11) NOT NULL DEFAULT '0',
  `Quantity` int(4) NOT NULL DEFAULT '1',
  `Priority` int(5) NOT NULL DEFAULT '1',
  `Price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `DateStart` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DateEnd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ProductId` (`ProductId`),
  KEY `CustomerGroupId` (`CustomerGroupId`),
  KEY `DateStart` (`DateStart`),
  KEY `DateEnd` (`DateEnd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `Download`;
CREATE TABLE `Download` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Filename` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Mask` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `StoreId` int(11) NOT NULL DEFAULT '0',
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `StoreId` (`StoreId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductDownload`;
CREATE TABLE `ProductDownload` (
  `ProductId` int(11) NOT NULL,
  `DownloadId` int(11) NOT NULL,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductId`,`DownloadId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductImage`;
CREATE TABLE `ProductImage` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductId` int(11) NOT NULL,
  `Image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SortOrder` int(3) NOT NULL DEFAULT '0',
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ProductId` (`ProductId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductOption`;
CREATE TABLE `ProductOption` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductId` int(11) NOT NULL,
  `OptionTypeId` int(11) NOT NULL,
  `Name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Required` tinyint(1) NOT NULL,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ProductId` (`ProductId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductOptionValue`;
CREATE TABLE `ProductOptionValue` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductOptionId` int(11) NOT NULL,
  `Name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Quantity` int(3) NOT NULL,
  `Subtract` tinyint(1) NOT NULL,
  `Price` decimal(15,4) NOT NULL,
  `PricePrefix` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Points` int(8) NOT NULL,
  `PointsPrefix` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Weight` decimal(15,8) NOT NULL,
  `WeightPrefix` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ProductOptionId` (`ProductOptionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `Recurring`;
CREATE TABLE `Recurring` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Price` decimal(10,4) NOT NULL,
  `Frequency` enum('day','week','semi_month','month','year') COLLATE utf8mb4_unicode_ci NOT NULL,
  `Duration` int(10) unsigned NOT NULL,
  `Cycle` int(10) unsigned NOT NULL,
  `TrialStatus` tinyint(4) NOT NULL,
  `TrialPrice` decimal(10,4) NOT NULL,
  `TrialFrequency` enum('day','week','semi_month','month','year') COLLATE utf8mb4_unicode_ci NOT NULL,
  `TrialDuration` int(10) unsigned NOT NULL,
  `TrialCycle` int(10) unsigned NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `SortOrder` int(11) NOT NULL,
  `StoreId` int(11) NOT NULL DEFAULT '0',
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `StoreId` (`StoreId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductRecurring`;
CREATE TABLE `ProductRecurring` (
  `ProductId` int(11) NOT NULL,
  `RecurringId` int(11) NOT NULL,
  `CustomerGroupId` int(11) NOT NULL,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductId`,`RecurringId`,`CustomerGroupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductRelated`;
CREATE TABLE `ProductRelated` (
  `ProductId` int(11) NOT NULL,
  `RelatedId` int(11) NOT NULL,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductId`,`RelatedId`),
  KEY `RelatedId` (`RelatedId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ProductSpecial`;
CREATE TABLE `ProductSpecial` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductId` int(11) NOT NULL,
  `CustomerGroupId` int(11) NOT NULL,
  `Priority` int(5) NOT NULL DEFAULT '1',
  `Price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `DateStart` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DateEnd` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ProductId` (`ProductId`),
  KEY `DateStart` (`DateStart`),
  KEY `DateEnd` (`DateEnd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `StockStatus`;
CREATE TABLE `StockStatus` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO StockStatus (`Name`) VALUES
('2-3 Days'), ('In Stock'), ('Out of Stock'), ('Pre-Order');

DROP TABLE IF EXISTS `LengthClass`;
CREATE TABLE `LengthClass` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Unit` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Value` decimal(15,8) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `LengthClass` (`Title`,`Unit`,`Value`) VALUES
('Inch','in',0.39370100),
('Centimeter','cm',1.00000000),
('Millimeter','mm',10.00000000);

DROP TABLE IF EXISTS `TaxClass`;
CREATE TABLE `TaxClass` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `TaxClass` (`Name`) VALUES
('Product'),('Service'),('Not Taxed');

DROP TABLE IF EXISTS `WeightClass`;
CREATE TABLE `WeightClass` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Unit` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Value` decimal(15,8) NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `WeightClass`
(`Title`,`Unit`,`Value`) VALUES
('Kilogram','kg',1.00000000),
('Gram','g',1000.00000000),
('Ounce','oz',35.27400000),
('Pound','lb',2.20460000);