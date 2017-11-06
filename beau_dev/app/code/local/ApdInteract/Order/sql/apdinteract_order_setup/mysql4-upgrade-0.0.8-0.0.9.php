<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("order", "easterfrm_tyre_size", array("type"=>"varchar"));
$installer->endSetup();