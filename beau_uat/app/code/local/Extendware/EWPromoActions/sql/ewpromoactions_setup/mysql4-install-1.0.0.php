<?php
Mage::helper('ewcore/cache')->clean();
$installer = $this;
$installer->startSetup();

$sql = sprintf('SHOW COLUMNS FROM `%s`', $this->getTable('salesrule/rule'));
$columns = $this->getConnection()->fetchCol($sql);

$command = '';
if (in_array('extendware_product_skus', $columns) === false) {
	$command .= 'ALTER TABLE `salesrule` ADD `extendware_product_skus` TEXT NOT NULL;';
}

if (in_array('extendware_category_ids', $columns) === false) {
	$command .= 'ALTER TABLE `salesrule` ADD `extendware_category_ids` TEXT NOT NULL;';
}

if (in_array('extendware_max_applications', $columns) === false) {
	$command .= 'ALTER TABLE `salesrule` ADD `extendware_max_applications` INTEGER UNSIGNED NOT NULL;';
}

if (in_array('extendware_stop_trigger_exceptions', $columns) === false) {
	$command .= 'ALTER TABLE `salesrule` ADD `extendware_stop_trigger_exceptions` TEXT NOT NULL;';
}

if (in_array('extendware_stop_exceptions', $columns) === false) {
	$command .= 'ALTER TABLE `salesrule` ADD `extendware_stop_exceptions` TEXT NOT NULL;';
}
if (in_array('extendware_product_add_type', $columns) === false) {
	$command .= 'ALTER TABLE `salesrule` ADD `extendware_product_add_type` VARCHAR(255) NOT NULL;';
}

// [[if normal]]// [[if normal]]
if (Mage::helper('ewcore/environment')->isDemoServer() === true) {
	$command .= "
		INSERT IGNORE INTO `salesrule` (`rule_id`, `name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `coupon_type`, `use_auto_generation`, `uses_per_coupon`, `extendware_product_skus`, `extendware_category_ids`, `extendware_max_applications`, `extendware_stop_trigger_exceptions`, `extendware_stop_exceptions`, `extendware_product_add_type`) VALUES (1,'Buy X and Y receives discount example','Purchase a product from the \"Cameras -> Digital Cameras\" category (X) and receive a 30% discount on a product from the \"Camera -> Assessories\" category (Y). \r\n\r\nThis rule is configured to only process once so purchasing multiple cameras will not allow multiple discounts of camera assessory products.',NULL,NULL,0,1,'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}','a:7:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"any\";s:10:\"conditions\";a:1:{i:0;a:5:{s:4:\"type\";s:32:\"salesrule/rule_condition_product\";s:9:\"attribute\";s:12:\"category_ids\";s:8:\"operator\";s:2:\"==\";s:5:\"value\";s:2:\"26\";s:18:\"is_value_processed\";b:0;}}}',0,1,NULL,10,'ewpa_xy_percent',30.0000,1.0000,1,0,0,0,1,1,0,0,'ac9003','',1,'','','all_product');
		INSERT IGNORE INTO `salesrule` (`rule_id`, `name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `coupon_type`, `use_auto_generation`, `uses_per_coupon`, `extendware_product_skus`, `extendware_category_ids`, `extendware_max_applications`, `extendware_stop_trigger_exceptions`, `extendware_stop_exceptions`, `extendware_product_add_type`) VALUES (2,'Cheapest cell phone receives discount example','Add any two products from the \"Cell Phones\" category and the cheapest of the two be discounted. \r\n\r\nThis rule has been configured to only apply once in the cart even if you add 4 products for example.',NULL,NULL,0,1,'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}','a:7:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";s:10:\"conditions\";a:1:{i:0;a:5:{s:4:\"type\";s:32:\"salesrule/rule_condition_product\";s:9:\"attribute\";s:12:\"category_ids\";s:8:\"operator\";s:2:\"{}\";s:5:\"value\";s:1:\"8\";s:18:\"is_value_processed\";b:0;}}}',0,1,NULL,10,'ewpa_cheapest_percent',10.0000,1.0000,2,0,0,0,1,1,0,0,'','',1,'','','all_product');
		INSERT IGNORE INTO `salesrule` (`rule_id`, `name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `coupon_type`, `use_auto_generation`, `uses_per_coupon`, `extendware_product_skus`, `extendware_category_ids`, `extendware_max_applications`, `extendware_stop_trigger_exceptions`, `extendware_stop_exceptions`, `extendware_product_add_type`) VALUES (3,'Buy 1 get 1 50% promotion example','Purchase 2 \"HTC Touch Diamond\" products and the 2nd receives a 50% discount. This is great for a buy 1 get 1 50% off sale.\r\n\r\nThis rule is configured to only apply for up to 4 products added to cart. After this quantity a further discount will not be given.',NULL,NULL,0,1,'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}','a:7:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";s:10:\"conditions\";a:1:{i:0;a:5:{s:4:\"type\";s:32:\"salesrule/rule_condition_product\";s:9:\"attribute\";s:3:\"sku\";s:8:\"operator\";s:2:\"==\";s:5:\"value\";s:7:\"UA-1020\";s:18:\"is_value_processed\";b:0;}}}',0,1,NULL,10,'ewpa_each_percent',50.0000,2.0000,2,0,0,0,1,1,0,0,'','',1,'','','all_product');
		INSERT IGNORE INTO `salesrule` (`rule_id`, `name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `coupon_type`, `use_auto_generation`, `uses_per_coupon`, `extendware_product_skus`, `extendware_category_ids`, `extendware_max_applications`, `extendware_stop_trigger_exceptions`, `extendware_stop_exceptions`, `extendware_product_add_type`) VALUES (4,'Purchase > 3 shirts and all shirts after 3 only cost $8.00 example','Receive discounts after 3 shirts are purchased. Add 5 shirts from the shirts category and the 4th and 5th shirt will be $8.00 each.',NULL,NULL,0,1,'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}','a:7:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";s:10:\"conditions\";a:1:{i:0;a:5:{s:4:\"type\";s:32:\"salesrule/rule_condition_product\";s:9:\"attribute\";s:12:\"category_ids\";s:8:\"operator\";s:2:\"{}\";s:5:\"value\";s:1:\"4\";s:18:\"is_value_processed\";b:0;}}}',0,1,NULL,10,'ewpa_after_fixedprice',8.0000,9999.0000,3,0,0,0,1,1,0,0,'','',1,'','','all_product');
		INSERT IGNORE INTO `salesrule` (`rule_id`, `name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `coupon_type`, `use_auto_generation`, `uses_per_coupon`, `extendware_product_skus`, `extendware_category_ids`, `extendware_max_applications`, `extendware_stop_trigger_exceptions`, `extendware_stop_exceptions`, `extendware_product_add_type`) VALUES (5,'Get $0.05 discount for every $1.00 spent on shoes example','Get a flat 5% discount for every dollar spent on products in the \"Shoes\" category.',NULL,NULL,0,1,'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}','a:7:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";s:10:\"conditions\";a:1:{i:0;a:5:{s:4:\"type\";s:32:\"salesrule/rule_condition_product\";s:9:\"attribute\";s:12:\"category_ids\";s:8:\"operator\";s:2:\"{}\";s:5:\"value\";s:9:\"5, 16, 17\";s:18:\"is_value_processed\";b:0;}}}',0,1,NULL,10,'ewpa_misc_amount',0.0500,NULL,0,0,0,0,1,1,0,0,'','',1,'','','all_product');
		INSERT IGNORE INTO `salesrule` (`rule_id`, `name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `coupon_type`, `use_auto_generation`, `uses_per_coupon`, `extendware_product_skus`, `extendware_category_ids`, `extendware_max_applications`, `extendware_stop_trigger_exceptions`, `extendware_stop_exceptions`, `extendware_product_add_type`) VALUES (6,'Buy a specific set of products and receive them for a fixed price','Purchase all products in the \"Magento Red Furniture Set\" bundle and it will only cost $700.00',NULL,NULL,0,1,'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}','a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}',0,1,NULL,10,'ewpa_set_fixedprice',700.0000,1.0000,1,0,0,0,1,1,0,0,'1111,1112,1113','',1,'','','all_product');
		INSERT IGNORE INTO `salesrule` (`rule_id`, `name`, `description`, `from_date`, `to_date`, `uses_per_customer`, `is_active`, `conditions_serialized`, `actions_serialized`, `stop_rules_processing`, `is_advanced`, `product_ids`, `sort_order`, `simple_action`, `discount_amount`, `discount_qty`, `discount_step`, `simple_free_shipping`, `apply_to_shipping`, `times_used`, `is_rss`, `coupon_type`, `use_auto_generation`, `uses_per_coupon`, `extendware_product_skus`, `extendware_category_ids`, `extendware_max_applications`, `extendware_stop_trigger_exceptions`, `extendware_stop_exceptions`, `extendware_product_add_type`) VALUES (7,'Buy 3 hard drives and receive 20% discount example','Buy 3 products from the \"Hard Drives\" category and recieve 20% off the group. \r\n\r\nThis rule is configured to allow up to two groups (6 hard drives) be discounted at a time. After this no more discount will be given.',NULL,NULL,0,1,'a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}','a:7:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";s:10:\"conditions\";a:1:{i:0;a:5:{s:4:\"type\";s:32:\"salesrule/rule_condition_product\";s:9:\"attribute\";s:12:\"category_ids\";s:8:\"operator\";s:2:\"{}\";s:5:\"value\";s:2:\"29\";s:18:\"is_value_processed\";b:0;}}}',0,1,NULL,10,'ewpa_group_percent',20.0000,2.0000,3,0,0,0,1,1,0,0,'','',2,'','','all_product');
			
		INSERT IGNORE INTO `salesrule_website` (`rule_id`, `website_id`) VALUES (1,1);
		INSERT IGNORE INTO `salesrule_website` (`rule_id`, `website_id`) VALUES (2,1);
		INSERT IGNORE INTO `salesrule_website` (`rule_id`, `website_id`) VALUES (3,1);
		INSERT IGNORE INTO `salesrule_website` (`rule_id`, `website_id`) VALUES (4,1);
		INSERT IGNORE INTO `salesrule_website` (`rule_id`, `website_id`) VALUES (5,1);
		INSERT IGNORE INTO `salesrule_website` (`rule_id`, `website_id`) VALUES (6,1);
		INSERT IGNORE INTO `salesrule_website` (`rule_id`, `website_id`) VALUES (7,1);
			
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (1,0);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (2,0);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (3,0);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (4,0);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (5,0);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (6,0);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (7,0);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (1,1);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (2,1);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (3,1);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (4,1);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (5,1);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (6,1);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (7,1);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (1,2);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (2,2);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (3,2);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (4,2);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (5,2);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (6,2);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (7,2);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (1,3);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (2,3);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (3,3);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (4,3);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (5,3);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (6,3);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (7,3);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (1,4);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (2,4);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (3,4);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (4,4);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (5,4);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (6,4);
		INSERT IGNORE INTO `salesrule_customer_group` (`rule_id`, `customer_group_id`) VALUES (7,4);
			
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,0,98);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,0,346);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,0,389);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,0,454);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,0,518);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,0,627);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,0,668);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,1,98);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,1,346);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,1,389);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,1,454);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,1,518);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,1,627);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,1,668);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,2,98);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,2,346);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,2,389);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,2,454);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,2,518);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,2,627);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,2,668);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,3,98);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,3,346);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,3,389);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,3,454);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,3,518);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,3,627);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,3,668);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,4,98);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,4,346);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,4,389);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,4,454);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,4,518);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,4,627);
		INSERT IGNORE INTO `salesrule_product_attribute` (`rule_id`, `website_id`, `customer_group_id`, `attribute_id`) VALUES (3,1,4,668);
	";
}
// [[/if]]

$command = @preg_replace('/(EXISTS\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(ON\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(REFERENCES\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(TABLE\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(INTO\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);
$command = @preg_replace('/(FROM\s+`)([a-z0-9\_]+?)(`)/ie', '"\\1" . $this->getTable("\\2") . "\\3"', $command);

if ($command) $installer->run($command);
$installer->endSetup(); 