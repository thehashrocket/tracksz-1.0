CREATE TABLE `parent_category` (
  `Id` int(11) NOT NULL,
  `Level` tinyint(11) DEFAULT NULL,
  `Name` varchar(200) NOT NULL,
  `Description` text NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  `Updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `parent_category`
  ADD PRIMARY KEY (`Id`);
  ALTER TABLE `parent_category`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;