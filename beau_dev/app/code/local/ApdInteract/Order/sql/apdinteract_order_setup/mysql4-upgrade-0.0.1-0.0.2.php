<?php
$this->startSetup();
$this->addAttribute('order', 'vmake', array(
    'type'          => 'varchar',
    'label'         => 'Vehicle Make',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));

$this->addAttribute('order', 'vmodel', array(
    'type'          => 'varchar',
    'label'         => 'Vehicle Model',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));







$this->addAttribute('order', 'last_wheel_balance', array(
    'type'          => 'varchar',
    'label'         => 'Last Wheel Balance',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));
$this->addAttribute('order', 'last_wheel_alignment', array(
    'type'          => 'varchar',
    'label'         => 'Last Wheel Alignment',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));
$this->addAttribute('order', 'registration_number', array(
    'type'          => 'varchar',
    'label'         => 'Registration Number',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));

$this->addAttribute('order', 'odometer', array(
    'type'          => 'varchar',
    'label'         => 'Odometer',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));


 
$this->endSetup();