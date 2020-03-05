USE `Tracksz`;

DROP TABLE IF EXISTS `ShippingZoneFee`;
CREATE TABLE `ShippingZoneFee` (
	`Id` INT(11) UNSIGNED AUTO_INCREMENT,
	`ZoneId` INT(11) NOT NULL,
	`ShippingMethodId` INT(11) NOT NULL,
	INDEX(`ZoneId`, `ShippingMethodId`),
	PRIMARY KEY(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;