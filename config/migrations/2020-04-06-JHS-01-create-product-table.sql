USE `Tracksz`;

CREATE TABLE `product` (
  `Id` int(11) NOT NULL,
  `Name` varchar(150) DEFAULT NULL,
  `Notes` text,
  `SKU` varchar(150) DEFAULT NULL,
  `ProdId` varchar(150) DEFAULT NULL,
  `BasePrice` double(5,2) DEFAULT NULL,
  `ProdCondition` varchar(150) DEFAULT NULL,
  `ProdActive` tinyint(1) DEFAULT '0',
  `InternationalShip` tinyint(1) DEFAULT '0',
  `ExpectedShip` tinyint(1) DEFAULT '0',
  `EbayTitle` varchar(150) DEFAULT NULL,
  `Qty` int(11) DEFAULT NULL,
  `Image` varchar(150) DEFAULT NULL,
  `CategoryId` int(11) DEFAULT NULL,
  `Created` datetime DEFAULT CURRENT_TIMESTAMP,
  `Updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `product`
  ADD PRIMARY KEY (`Id`);
  
ALTER TABLE `product`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
