CREATE TABLE `zzTestMigrate` (
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;