<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("quote_item", "road_hazard_warranty", array("type"=>"int"));
$installer->endSetup();