<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("quote_item", "rr_price", array("type"=>"decimal"));
$installer->addAttribute("order_item", "rr_price", array("type"=>"decimal"));
$installer->endSetup();
	 