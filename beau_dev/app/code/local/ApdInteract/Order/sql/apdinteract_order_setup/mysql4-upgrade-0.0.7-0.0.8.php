<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute('order', 'online_flag', array(
    'label'        => 'Onlne Order Flag',
    'visible'      => 1,
    'required'     => 0,
    'position'     => 141,
    'type'         => 'int',
    'default'      => 1,
    'input'        => 'boolean',

));

$installer->endSetup();