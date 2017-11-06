<?php
// Catalog Product Attribute

//Free Product

$installer = $this;
$installer->startSetup();
$installer->addAttribute('catalog_product', 'free_product', array(
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Free Product',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => 0,
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'unique'            => false,
    'used_in_product_listing' => true,
    'group'             => 'Prices',
    'position'        => 0
));

$installer->endSetup();
