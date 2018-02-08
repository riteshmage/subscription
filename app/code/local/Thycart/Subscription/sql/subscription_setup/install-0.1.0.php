<?php
$installer = $this;
$installer->startSetup();

$installer->run("CREATE TABLE `subscription_master` (
  `subscription_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Subscription Id',
  `subscription_name` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL COMMENT 'Product Id',
  `product_sku` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `max_billing_cycle` int(10) unsigned NOT NULL COMMENT 'Max Billing Cycle',
  `show_start_date` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Show Start Date',
  `discount_type` varchar(255) NOT NULL DEFAULT 'fixed' COMMENT '1= fixed, 2 = percentage',
  `discount_value` int(11) NOT NULL DEFAULT '0' COMMENT 'Discount value',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Is active',
  `created_by` int(10) unsigned NOT NULL COMMENT 'Created By',
  `updated_by` int(10) unsigned DEFAULT NULL COMMENT 'Updated By',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created Time',
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated Time',
  PRIMARY KEY (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='subscription_master'

");

$installer->run("CREATE TABLE `unit_master` (
  `unit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_unit` varchar(200) NOT NULL,
  `active` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`unit_id`),
  UNIQUE KEY `subscription_unit` (`subscription_unit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
");

$installer->run("CREATE TABLE `unit_product_mapping` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id_unit_id_subscription_id_active` (`product_id`,`unit_id`,`subscription_id`,`active`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
");

$installer->run("CREATE TABLE `subcription_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `last_date` date NOT NULL,
  `unit_selected` varchar(255) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `number_of_orders_placed` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1
");

$installer->endSetup();