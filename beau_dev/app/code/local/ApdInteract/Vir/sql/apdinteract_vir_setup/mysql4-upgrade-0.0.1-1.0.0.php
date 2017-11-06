<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
try{
    $installer->startSetup();
    
    $installer->run("DROP TABLE IF EXISTS `{$installer->getTable('apdinteract_vir/orderstoremapping')}`;    
        CREATE TABLE `{$installer->getTable('apdinteract_vir/orderstoremapping')}` (
        `entity_id` int(11) NOT NULL AUTO_INCREMENT,
        `vir_id` int(11) DEFAULT NULL,
        `store_id` int(11)DEFAULT NULL,
        `vir_type` int(11) NOT NULL COMMENT '0 = consumer, 1 = commercial',
        PRIMARY KEY (`entity_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;	
    ");
    
    $installer->endSetup();
}catch(Exception $e){
    
}