<?php
$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `apd_stock_store_product`;    
CREATE TABLE `apd_stock_store_product` (
`costar_store_code` VARCHAR( 10 ) NOT NULL,
`sku` VARCHAR( 64 ) NOT NULL,
`qty` int(11) NOT NULL,
`is_active` tinyint(1) NOT NULL,   
UNIQUE KEY `costar_store_code-sku` (`sku`,`costar_store_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

$this->endSetup();