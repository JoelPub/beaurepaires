<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("order", "appointment_datetime", array("type"=>"datetime"));
$installer->endSetup();