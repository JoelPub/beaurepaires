<?php
$this->startSetup();
$this->addAttribute('order', 'storelocation', array(
    'type'          => 'varchar',
    'label'         => 'Vehicle Make',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));

$this->addAttribute('order', 'delivery_date', array(
    'type'          => 'varchar',
    'label'         => 'Vehicle Model',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));


 
$this->endSetup();