<?php

/**
 * - Create CMS Blocks in the homepage
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
 	array (
		'identifier' => 'homepage_cta_left',
		'title' => 'Homepage CTA Left',
		'stores' => array(0),
		'is_active' => 1,
		'content' => 'Left content'
	),
    array (
		'identifier' => 'homepage_cta_middle',
		'title' => 'Homepage CTA Middle',
		'stores' => array(0),
		'is_active' => 1,
		'content' => 'Middle content'
	),
	array (
		'identifier' => 'homepage_cta_right',
		'title' => 'Homepage CTA Right',
		'stores' => array(0),
		'is_active' => 1,
		'content' => 'Right content'
	),
	array (
		'identifier' => 'homepage_feature',
		'title' => 'Homepage Feature',
		'stores' => array(0),
		'is_active' => 1,
		'content' => 'Homepage Feature'
	),
);

foreach ($blocks as $data){
$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($data)->save();
}
$installer->endSetup();