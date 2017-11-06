<?php

$installer = $this;
$installer->startSetup();


$table = $installer->getTable('iwd_storelocator');

$attributes = array('batteries'=>'batteries_available','brakes_service'=>'brake_fitting','nitrogen'=>'nitrogen_available','mobile_fleet'=>'has_mobility_fleet','balancing'=>'wheel_balancing_service','wheel_alignments'=>'wheel_alignment_service');

foreach($attributes as $old=>$new):
    $installer->getConnection()->changeColumn($table,$old,$new,array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => false,
        
    ));
endforeach;

$installer->endSetup();