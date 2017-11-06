<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `ge_method` VARCHAR( 255 ) NOT NULL,
ADD `ge_term` VARCHAR( 255 ) NOT NULL;
  
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `ge_method` VARCHAR( 255 ) NOT NULL,
ADD `ge_term` VARCHAR( 255 ) NOT NULL;
");
$installer->endSetup();