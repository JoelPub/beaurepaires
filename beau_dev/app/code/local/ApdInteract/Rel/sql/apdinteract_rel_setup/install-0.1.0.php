<?php
/**
* Created table apdinteract_canonical_url
* BCC-156 3/14/2016
* 
*/
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `{$installer->getTable('apdinteract_rel/canonical')}`;    
CREATE TABLE IF NOT EXISTS {$this->getTable('apdinteract_rel/canonical')} (
  `url_id` int(10) NOT NULL auto_increment,
  `url` varchar(255) DEFAULT NULL,
  `new_canonical_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`url_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

$installer->endSetup();
