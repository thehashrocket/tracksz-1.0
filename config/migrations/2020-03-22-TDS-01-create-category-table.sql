-- USE `Tracksz`;


-- CREATE TABLE `category` (
--   `Id` int(11) NOT NULL,
--   `ParentId` int(11) NOT NULL DEFAULT '0',
--   `Level` tinyint(4) NOT NULL DEFAULT '0',
--   `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
--   `Description` text COLLATE utf8mb4_unicode_ci NOT NULL,
--   `Image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
--   `Status` tinyint(1) NOT NULL DEFAULT '0',
--   `UserId` int(11) DEFAULT NULL,
--   `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
--   `Updated` datetime DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;