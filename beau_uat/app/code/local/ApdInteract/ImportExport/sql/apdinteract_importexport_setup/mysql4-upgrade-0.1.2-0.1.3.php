<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

if (!$installer->getConnection()->fetchOne("select * from {$this->getTable('eav_entity_type')} where 'eav_entity_code'='product_review'")) {
    $installer->run("
        insert  into {$this->getTable('eav_entity_type')} 
        (`entity_type_id`,`entity_type_code`,`entity_model`,`attribute_model`,`entity_table`,`value_table_prefix`,`entity_id_field`,`is_data_sharing`,`data_sharing_key`,
          `default_attribute_set_id`,`increment_model`,`increment_per_store`,`increment_pad_length`,`increment_pad_char`,`additional_attribute_table`,`entity_attribute_collection`) 
         VALUES (NULL, 'product_inventory', 'apdinteract_importexport/inventory', NULL, 'catalog/product', NULL, NULL, '1', 'default', '0', '', '0', '8', '0', 'catalog/eav_attribute',
         'catalog/product_attribute_collection');
    ");
}

$installer->endSetup();
