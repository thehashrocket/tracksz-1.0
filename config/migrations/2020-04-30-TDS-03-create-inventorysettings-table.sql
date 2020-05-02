USE `Tracksz`;

CREATE TABLE `InventorySettings` (
  `Id` int(11) NOT NULL,
  `FileType` varchar(255) DEFAULT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Updated` datetime DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventorysettings`
--
ALTER TABLE `InventorySettings`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventorysettings`
--
ALTER TABLE `InventorySettings`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
