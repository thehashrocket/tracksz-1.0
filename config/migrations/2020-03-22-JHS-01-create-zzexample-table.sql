CREATE TABLE `zzExample` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ExampleName` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `name` (`ExampleName`)
) ENGINE=InnoDB AUTO_INCREMENT=1043 DEFAULT CHARSET=utf8;