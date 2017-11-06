<?php

/**
 * - View Tyre Glossary Link
 */
$installer = $this;
$installer->startSetup();
/*Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$block = array (
	'identifier' => 'view_tyre_glossary',
	'title' => 'View Tyre Glossary Link',
	'stores' => array(0),
	'is_active' => 1,
	'content' => <<<EOF
<a href="/tyres/glossary-of-tyre-terminology" title="View Tyre Glossary">View Tyre Glossary</a>	
EOF
);

$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($block)->save();*/
$installer->endSetup();
