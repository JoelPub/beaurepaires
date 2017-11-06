<?php

/**
 * - Create CMS Block in Search Page
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$block = array (
		'identifier' => 'search_cms',
		'title' => 'Search CMS',
		'stores' => array(0),
		'is_active' => 1,
 		'content' => <<<EOF
<img src="http://placehold.it/800x600" class="expand">
 			
EOF
);

$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($block)->save();
$installer->endSetup();
