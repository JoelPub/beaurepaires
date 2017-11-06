<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()
	->addColumn($installer->getTable('cms/block'),'customer_group_id',array(
		'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
		'nullable' => false,
		'length'   => 3,
		'comment'  => 'Customer Group Id'
	));

$installer->endSetup();

?>
