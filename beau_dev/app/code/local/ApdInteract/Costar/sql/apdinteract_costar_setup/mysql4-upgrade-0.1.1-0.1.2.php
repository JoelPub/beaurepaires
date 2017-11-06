<?php
$installer = $this;
$installer->startSetup();
$sql="ALTER TABLE {$this->getTable('apdinteract_costar/costar')} ADD `costar_id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`costar_id`);";

$installer->run($sql);
$installer->endSetup();
	 