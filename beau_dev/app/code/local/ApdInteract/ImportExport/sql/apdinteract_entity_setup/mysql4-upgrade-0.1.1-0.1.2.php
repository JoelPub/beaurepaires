<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("order", "date_range", array(
            "type"=>"datetime",
            'label' => 'Date Range',
            ));
$installer->endSetup();  
