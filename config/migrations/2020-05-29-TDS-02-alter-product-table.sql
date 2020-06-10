ALTER TABLE `product` ADD `MarketPlaceId` INT(11) NULL AFTER `AddtionalData`, ADD `StoreId` INT(11) NULL AFTER `MarketPlaceId`;
ALTER TABLE `product` ADD `Location` VARCHAR(255) NULL AFTER `StoreId`;