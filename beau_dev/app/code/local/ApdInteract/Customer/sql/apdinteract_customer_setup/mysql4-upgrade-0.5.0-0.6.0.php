<?php

$installer = $this;
$installer->startSetup();
$installer->removeAttribute('customer', 'costar_customer_id');
$installer->endSetup();
