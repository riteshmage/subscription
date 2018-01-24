<?php
$installer = $this;
$installer->startSetup();

$installer->run("CREATE TABLE `subscription_master` (
	`subscription_id` int UNSIGNED NOT NULL auto_increment COMMENT 'Subscription Id' ,
	`product_id` int UNSIGNED NOT NULL COMMENT 'Product Id' ,
	`max_billing_cycle` int UNSIGNED NOT NULL COMMENT 'Max Billing Cycle' ,
	`show_start_date` smallint UNSIGNED NOT NULL default '1' COMMENT 'Show Start Date' ,
	`discount_type` varchar(255) NOT NULL default 'fixed' COMMENT 'Discount Type' ,
	`discount_value` int NOT NULL default '0' COMMENT 'Discount value' ,
	`active` smallint UNSIGNED NOT NULL default '0' COMMENT 'Is active' ,
	`created_by` int UNSIGNED NOT NULL COMMENT 'Created By' ,
	`updated_by` int UNSIGNED NOT NULL COMMENT 'Updated By' ,
	`created_time` datetime default CURRENT_TIMESTAMP NOT NULL  COMMENT 'Created Time' ,
	`updated_time` datetime  default CURRENT_TIMESTAMP on UPDATE CURRENT_TIMESTAMP NOT NULL COMMENT 'Updated Time' ,
	PRIMARY KEY (`subscription_id`)
) COMMENT='subscription_master' ENGINE=INNODB charset=utf8 COLLATE=utf8_general_ci");

$installer->endSetup();