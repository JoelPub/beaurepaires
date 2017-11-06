<?php
// Catalog Product Attribute

$installer = $this;
$installer->startSetup();
$installer->addAttribute('catalog_product', 'warranty', array(
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Warranty',
    'input'             => 'textarea',
    'class'             => '',
    'source'            => 'catalog/product_attribute_source_layout',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'unique'            => false,
    'group'             => 'General'
));

$installer->updateAttribute('catalog_product', 'warranty', 'is_wysiwyg_enabled', 1);
$installer->updateAttribute('catalog_product', 'warranty', 'is_html_allowed_on_front', 1);

$installer->endSetup();
