CREATE TABLE `MarketPlace` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `MarketName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `WebSite` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ConnectionType` enum('API','FTP','Other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FTP',
  `TestEndPoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `LiveEndPoint` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `CredentialsRequired` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Supported` tinyint(1) NOT NULL DEFAULT '0',
  `FormFileName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `Updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `MarketName` (`MarketName`),
  KEY `Supported` (`Supported`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO MarketPlace
(`MarketName`,`WebSite`,`CredentialsRequired`,`Supported`,`FormFileName`) VALUES
("Abebooks","Abebooks.com","EmailAddress,FtpUserId,FtpPassword",1,"abebooks_fields"),
("Alibris","Alibris.com","UserId,Password",1,"alibris_fields"),
("Amazon (US)","Amazon.com","MerchantId",1,"amazon_fields"),
("Amazon (MX)","Amazon.com.mx","MerchantId",1,"amazon_fields"),
("Amazon (UK)","Amazon.co.uk","MerchantId",1,"amazon_fields"),
("Barnes & Noble","Bn.com","EmailAddress,Password,FtpUserId,FtpPassword",1,"barnesnoble_fields"),
("Biblio","Biblio.com","FtpUserId,FtpPassword",1,"ftp_fields"),
("ChrisLands","ChrisLands.com","FtpUserId,FtpPassword",1,"ftp_fields"),
("eBay","eBay.com","UserId,Password",1,"ebay_fields"),
("eCampus","eCampus.com","FtpUserId,FtpPassword",1,"ftp_fields"),
("ValoreBooks","ValoreBooks.com","FtpUserId,FtpPassword",1,"ftp_fields");

CREATE TABLE StoreHasMarketPlace (
  StoreId  int(11) unsigned NOT NULL,
  MarketPlaceId int(11) unsigned NOT NULL,
  Credentials text COLLATE utf8mb4_unicode_ci NOT NULL,
  PrependVenue varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  AppendVenue varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  IncreaseMinMarket double(7,2) DEFAULT 0.00,
  FileFormat varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'UIEE',
  OrderRetrievalInterval double(3,2) DEFAULT NULL,
  SuspendExport tinyint(1) DEFAULT NULL DEFAULT 0,
  SendDeletes tinyint(1) DEFAULT NULL DEFAULT 0,
  SkipMedia tinyint(1) DEFAULT NULL DEAFULT 0,
  ConfirmOrderInProcess tinyint(1) DEFAULT 0,
  MarketAcceptCurrency varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,


  FtpAppendVenue int(11) DEFAULT NULL,

  MarketAcceptPriceVal double(5,2) DEFAULT NULL,
  MarketAcceptPriceValMulti double(5,2) DEFAULT NULL,
  MarketSpecificPrice varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  MarketAcceptPriceVal2 double(5,2) DEFAULT NULL,
  MarketAcceptPriceValMulti2 double(5,2) DEFAULT NULL,
  Updated datetime DEFAULT CURRENT_TIMESTAMP,
  Created datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;