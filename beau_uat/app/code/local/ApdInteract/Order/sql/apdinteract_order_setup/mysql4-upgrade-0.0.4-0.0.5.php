<?php
$this->startSetup();
$this->addAttribute('order', 'appointmentid', array(
    'type'          => 'varchar',
    'label'         => 'Appointment ID',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
        'user_defined'  =>  true
));

 
$this->endSetup();