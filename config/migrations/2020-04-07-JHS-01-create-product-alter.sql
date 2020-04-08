USE `Tracksz`;

ALTER TABLE `product` ADD `Status` TINYINT(1) NOT NULL DEFAULT '0' AFTER `CategoryId`;


