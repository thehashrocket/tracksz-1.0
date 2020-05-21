ALTER TABLE `ordersettings` ADD `DontSendCopy` TINYINT(1) NOT NULL DEFAULT '0' AFTER `DeferEmail`;
ALTER TABLE `ordersettings` ADD `NoAdditionalOrder` TEXT NULL DEFAULT NULL AFTER `DontSendCopy`;