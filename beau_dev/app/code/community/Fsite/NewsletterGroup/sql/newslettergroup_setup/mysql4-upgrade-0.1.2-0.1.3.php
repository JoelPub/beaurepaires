<?php
$installer = $this;
$installer->startSetup();

$sql = "
INSERT INTO {$this->getTable('newslettergroup/group')} (`id`, `group_name`, `visible_in_frontend`) 
  SELECT '1','General Subscription','1' FROM DUAL
WHERE NOT EXISTS 
  (SELECT id FROM {$this->getTable('newslettergroup/group')} WHERE id='1');
  
INSERT INTO {$this->getTable('newslettergroup/group')} (`id`, `group_name`, `visible_in_frontend`) 
  SELECT '2','Beaurepaires - Marketing - General Newslette','1' FROM DUAL
WHERE NOT EXISTS 
  (SELECT id FROM {$this->getTable('newslettergroup/group')} WHERE id='2');  

INSERT INTO {$this->getTable('newslettergroup/group')} (`id`, `group_name`, `visible_in_frontend`) 
  SELECT '3','Beaurepaires - Marketing - Product News','1' FROM DUAL
WHERE NOT EXISTS 
  (SELECT id FROM {$this->getTable('newslettergroup/group')} WHERE id='3');

INSERT INTO {$this->getTable('newslettergroup/group')} (`id`, `group_name`, `visible_in_frontend`) 
  SELECT '4','Beaurepaires - Marketing - Special Offers and Rewards','1' FROM DUAL
WHERE NOT EXISTS 
  (SELECT id FROM {$this->getTable('newslettergroup/group')} WHERE id='4');  


";

$installer->run($sql);

$installer->endSetup();
	 