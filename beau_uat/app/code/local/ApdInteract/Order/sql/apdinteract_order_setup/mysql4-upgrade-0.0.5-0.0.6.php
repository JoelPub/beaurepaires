<?php
$this->startSetup();
$this->addAttribute('order', 'store_details', array(
    'type'          => 'varchar',
    'label'         => 'Store details',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'user_defined'  =>  true
));

 
$this->endSetup();