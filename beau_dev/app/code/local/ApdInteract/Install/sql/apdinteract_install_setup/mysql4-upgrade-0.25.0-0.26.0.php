<?php

/**
 * - 29/10/2015 - Add Vimeo Video ID attribute to product
 *
 * Modified by Jagdeep :: BFT-2236 Fixed conflict after merged ecom into bcc.
 *
 */

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();	

$installer->addAttribute('catalog_product', 'vimeo_video_id', array(
    'group'             => 'Images',
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Vimeo Video Id (eg 45229491)',
    'note'		=> 'Leave blank for no video',
    'input'             => 'text',
    'class'             => '',
//    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => true,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => true,
    'used_in_product_listing' => true, 
    'sort_order'        => 15  
));

$setup->addAttribute('catalog_product', 'wheel_manufacture', array(
        'group'         => 'Wheel and Tyres Attributes',
        'input'         => 'text',
        'type'          => 'text',
        'label'         => 'Wheel Manufacture',
        'backend'       => '',
        'visible'       => 1,
        'required'      => 0,
        'user_defined' => 1,
        'searchable' => 1,
        'filterable' => 0,
        'comparable'    => 1,
        'visible_on_front' => 1,
        'visible_in_advanced_search'  => 0,
        'is_html_allowed_on_front' => 0,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));


Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$block = array (
        'identifier' => 'cart_promotional_block',
        'title' => 'Cart Promotional Block',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
<img src="http://placehold.it/400x150">
            
EOF
);

$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($block)->save();

/*
 * - 10/26/2015 - Vimeo Video URL
 */
$installer->addAttribute('catalog_product', 'noupdatebywiser', array(
    'group'             => 'Prices',
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Set price to manual update?',
    'note'              => 'Prevent automatic price updates (Wiser)',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => true,
    'used_in_product_listing' => true, 
    'sort_order'        => 15  
));


$installer->endSetup();

