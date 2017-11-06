<?php
/*
 * This script will update the attribute type of an existing attribute which is "searchfilter"
 * Will update from text field to text 
 * For reference of the ticket, see BFT-1445.
 */
/** @var $this Mage_Eav_Model_Entity_Setup */

$this->startSetup();

$attribute_id = $this->getAttribute(
		Mage_Catalog_Model_Product::ENTITY,
		281,
		'attribute_id'
);

if (!is_numeric($attribute_id)) {
    Mage::throwException("Couldn't run migration: Unable to find attribute id");
}

/** @var Varien_Db_Adapter_Pdo_Mysql $connection */
$connection = $this->getConnection();

$connection->beginTransaction();

/**
 * Copy the data from the VARCHAR table to the TEXT table.
 */
$connection->query("
        INSERT INTO catalog_product_entity_text 
            (entity_type_id, attribute_id, store_id, entity_id, value)
        SELECT
            entity_type_id, attribute_id, store_id, entity_id, value
        FROM catalog_product_entity_varchar
        WHERE attribute_id = ?
    ",
    array($attribute_id)
);

/**
 * Update eav_attribute to use the text table instead of the varchar.
 */
$connection->query("UPDATE eav_attribute SET backend_type = 'text' WHERE attribute_id = ?", array($attribute_id));

/**
 * Delete the attribute values from the VARCHAR table.
 */
$connection->query("DELETE FROM catalog_product_entity_varchar WHERE attribute_id = ?", array($attribute_id));
$connection->commit();

$this->endSetup();