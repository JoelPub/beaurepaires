<?php

/**
 * - Create CMS Blocks in Request Modal and Book an Appointment Modal
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
 	array (
		'identifier' => 'request-price-modal',
		'title' => 'request-price-modal',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
Aenean ut hendrerit libero, a vulputate turpis. Ut in tortor eu leo pulvinar efficitur sed vel tortor. Phasellus mollis lacus eu malesuada semper. Integer id volutpat turpis, a tincidunt ante. Aliquam quam nisl, aliquam nec nisi ut, aliquet laoreet lacus. Aenean sit amet nibh blandit, dignissim erat sit amet, pulvinar turpis.
                    
EOF
	),
    array (
		'identifier' => 'book-an-appoinment-modal',
		'title' => 'book-an-appoinment-modal',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
Aenean ut hendrerit libero, a vulputate turpis. Ut in tortor eu leo pulvinar efficitur sed vel tortor. Phasellus mollis lacus eu malesuada semper. Integer id volutpat turpis, a tincidunt ante. Aliquam quam nisl, aliquam nec nisi ut, aliquet laoreet lacus. Aenean sit amet nibh blandit, dignissim erat sit amet, pulvinar turpis.
EOF
	),
);

foreach ($blocks as $data){
$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($data)->save();
}

$installer->getConnection()
->addColumn($installer->getTable('sales/order'),'year', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'comment'   => 'Year'
));

$installer->endSetup();


