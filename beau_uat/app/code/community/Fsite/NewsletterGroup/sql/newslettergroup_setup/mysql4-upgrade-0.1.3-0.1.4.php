<?php 
//  Rename General Newsletter - See BFT-2074

$installer = $this;
$installer->startSetup();

$sql = "
UPDATE {$this->getTable('newslettergroup/group')} SET group_name = 'Beaurepaires - Marketing - General Newsletter'
WHERE group_name = 'Beaurepaires - Marketing - General Newslette';
";

$installer->run($sql);
$installer->endSetup();