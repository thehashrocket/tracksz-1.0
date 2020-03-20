USE `Tracksz`;

CREATE TABLE `ShippingZoneToRegion` (
  `Id` int(11) UNSIGNED AUTO_INCREMENT NOT NULL,
  `ZoneId` int(11) UNSIGNED NOT NULL,
  `CountryId` int(11) UNSIGNED NOT NULL,
  `StateId` int(11) UNSIGNED DEFAULT NULL,
  `ZipCodeMin` mediumint(5) UNSIGNED DEFAULT NULL,
  `ZipCodeMax` mediumint(5) UNSIGNED DEFAULT NULL,
   PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `ShippingZoneToRegion`
ADD KEY (`ZoneId`, `CountryId`, `StateId`, `ZipCodeMin`, `ZipCodeMax`);