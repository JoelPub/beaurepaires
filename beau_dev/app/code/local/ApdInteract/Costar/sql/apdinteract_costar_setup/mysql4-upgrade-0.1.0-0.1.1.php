<?php
$installer = $this;
$installer->startSetup();
$sql="CREATE TABLE {$this->getTable('apdinteract_costar/log')} (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` DATETIME on update CURRENT_TIMESTAMP NOT NULL,
  `duration` varchar(10) NOT NULL,
  `added` INT NOT NULL,
  `updated` INT NOT NULL,
  `skipped` INT NOT NULL,
  `error` INT NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;".
   
"ALTER TABLE {$this->getTable('apdinteract_costar/log')}
  ADD PRIMARY KEY (`id`);".
        
"ALTER TABLE {$this->getTable('apdinteract_costar/log')}
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;"
;

$installer->run($sql);
$installer->endSetup();


	 