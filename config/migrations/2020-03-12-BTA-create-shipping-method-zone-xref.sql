USE `Tracksz`;

CREATE TABLE `ShippingMethodToZone` (
  `Id` int(11) UNSIGNED NOT NULL,
  `MethodId` int(11) NOT NULL,
  `ZoneId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `ShippingMethodToZone`
  ADD PRIMARY KEY (`Id`),
  ADD KEY (`MethodId`, `ZoneId`);