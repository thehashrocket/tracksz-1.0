USE `Tracksz`;

CREATE TABLE `inventorypriority` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `IsUpdating` tinyint(1) NOT NULL DEFAULT '0',
  `Updated` datetime DEFAULT CURRENT_TIMESTAMP,
  `Created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventorypriority`
--

INSERT INTO `inventorypriority` (`Id`, `UserId`, `IsUpdating`, `Updated`, `Created`) VALUES
(1, NULL, 0, '2020-04-02 10:46:37', '2020-04-02 10:46:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventorypriority`
--
ALTER TABLE `inventorypriority`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventorypriority`
--
ALTER TABLE `inventorypriority`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;