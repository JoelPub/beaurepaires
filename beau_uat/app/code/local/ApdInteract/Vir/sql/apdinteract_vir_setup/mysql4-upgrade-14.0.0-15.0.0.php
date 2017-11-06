<?php


$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE `apdinteract_vir_ordercommercial`  ADD `custapprpaycash` int(11) DEFAULT NULL,
        ADD `custapprpayvisamaster` int(1) DEFAULT NULL,
        ADD`custapprpayaccount` int(1) DEFAULT NULL,
        ADD `custapprpayother` int(1) DEFAULT NULL,
        ADD `custapprpayeftpos` int(1) DEFAULT NULL,
        ADD `custapprpayamexdiners` int(1) DEFAULT NULL,
        ADD `custapprpayfinance` int(1) DEFAULT NULL;");

$this->endSetup();

