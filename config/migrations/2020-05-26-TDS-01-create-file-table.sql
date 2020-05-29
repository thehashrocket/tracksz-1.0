CREATE TABLE `confirmation_file` (
  `Id` int(11) NOT NULL,
  `OrderId` varchar(50) NOT NULL,
  `FileId` int(20) NOT NULL,
  `FileName` varchar(100) NOT NULL,
  `UploadDate` datetime NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `confirmation_file`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `confirmation_file`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;