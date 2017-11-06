<?php

/**
 * This script does the following things:
 * - Create new "Wines" attribute set
 * - Create "wine_variety","wine_country","wine_type" product attributes & add them to "Wines" attribute set
 */

$connection = $this->getConnection();
$this->startSetup();
$this->cleanCache();

/**
 * Create new attribute set named "wines"
 */
$sNewSetName = 'Wines';
$iCatalogProductEntityTypeId = (int) $this->getEntityTypeId('catalog_product');

$oAttributeset = Mage::getModel('eav/entity_attribute_set')
    ->setEntityTypeId($iCatalogProductEntityTypeId)
    ->setAttributeSetName($sNewSetName);

if ($oAttributeset->validate()) {
    $oAttributeset->save()
        ->initFromSkeleton($iCatalogProductEntityTypeId)
        ->save();
}
else {
    die('Attributeset with name ' . $sNewSetName . ' already exists.');
}

/**
 * Create new attributes for "wines" attribute set
 */
//Variety
$this->addAttribute('catalog_product', 'wine_variety', array(
    'input'         => 'select',
    'type'          => 'int',
    'label'         => 'Variety',
    'backend'       => '',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'       => true,
    'required'      => true,
    'searchable'    => true,
    'search_weight' => 4,
    'filterable'    => true,
    'used_in_product_listing'   => true,
    'filterable_in_search'      => true,
    'position' => 1,
    'comparable'    => true,
    'user_defined'  => true,
    'visible_on_front' => true,
    'source'        => '',
    'option'        => array(
        'value' => array(
            'shiraz' => array('Shiraz','Shiraz'),
            'cabernet Shauvignon'   => array('Cabernet Shauvignon','Cabernet Shauvignon'),
            'pinot noir' => array('Pinot noir','Pinot noir'),
            'merlot' => array('Merlot','Merlot'),
            'zinfandel' => array('Zinfandel','Zinfandel'),
            'nebbiolo' => array('Nebbiolo','Nebbiolo'),
            'refosco' => array('Refosco','Refosco'),
            'sangiovese' => array('Sangiovese','Sangiovese'),
            'tempranillo' => array('Tempranillo','Tempranillo'),
            'grenache' => array('Grenache','Grenache'),
        )
    )
));

//Country
$this->addAttribute('catalog_product', 'wine_country', array(
    'input'         => 'select',
    'type'          => 'int',
    'label'         => 'Country',
    'backend'       => '',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'       => true,
    'required'      => true,
    'search_weight' => 3,
    'searchable'    => true,
    'filterable'    => true,
    'position' => 2,
    'used_in_product_listing'   => true,
    'filterable_in_search'      => true,
    'comparable'    => true,
    'user_defined'  => true,
    'visible_on_front' => true,
    'source'        => '',
    'option'        => array(
        'value' => array(
            'new zealand' => array('New Zealand','New Zealand'),
            'australia' => array('Australia','Australia'),
            'france' => array('France','France'),
            'spain' => array('Spain','Spain'),
            'italy' => array('Italy','Italy'),
            'other new world' => array('Other New World','Other New World'),
        )
    )
));


//Region
$this
    ->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'wine_region', array(
//        'group' => 'Prices',
//        'attribute_set' =>  'Wines',
        'input'         => 'text',
        'type'          => 'varchar',
        'label'         => 'Wine region',
        'backend'       => '',
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'       => true,
        'is_visible'    => true,
        'required'      => true,
        'searchable'    => false,
        'sort_order'    => 16,
        'is_filterable'    => false,
        'used_for_promo_rules' => false,
        'used_in_product_listing'   => true,
        'is_filterable_in_search'      => false,
        'filterable_in_search'      => 1,
        'comparable'    => false,
        'user_defined'  => true,
        'visible_on_front' => true,
    ));


//Type
$this->addAttribute('catalog_product', 'wine_type', array(
    'input'         => 'multiselect',
    'type'          => 'varchar',
    'label'         => 'Type',
    'backend'       => 'eav/entity_attribute_backend_array',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'       => true,
    'required'      => false,
    'searchable'    => true,
    'filterable'    => true,
    'search_weight' => 4,
    'position' => 3,
    'used_in_product_listing'   => true,
    'filterable_in_search'      => true,
    'comparable'    => true,
    'user_defined'  => true,
    'visible_on_front' => true,
    'source'        => '',
    'option'        => array(
        'value' => array(
            'specials' => array('Specials','Specials'),
            'quaffers' => array('Quaffers','Quaffers'),
            'classics' => array('Classics','Classics'),
            'new arrivals' => array('New Arrivals','New Arrivals'),
            'high rollers' => array('High Rollers','High Rollers'),
            'wines we love' => array('Wines We Love','Wines We Love'),
        )
)
));


/**
 * Add attributes to "wines" set
 */
$groupName = "General";
$attributeSetId = $this->getAttributeSetId('catalog_product','Wines');

$this->addAttributeToSet('catalog_product', $attributeSetId, $groupName, 'wine_variety');
$this->addAttributeToSet('catalog_product', $attributeSetId, $groupName, 'wine_country');
$this->addAttributeToSet('catalog_product', $attributeSetId, $groupName, 'wine_type');
$this->addAttributeToSet('catalog_product', $attributeSetId, $groupName, 'wine_region');

$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'price', 'position', '4');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'price', 'is_filterable_in_search', true);
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'name', 'search_weight', '5');
$this->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'description', 'search_weight', '2');

$this->endSetup();