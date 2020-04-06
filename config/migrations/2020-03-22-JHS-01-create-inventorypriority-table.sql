USE `Tracksz`;

CREATE TABLE `inventorypriority` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `IsUpdating` tinyint(1) NOT NULL DEFAULT '0',
  `Updated` datetime DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

