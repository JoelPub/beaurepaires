<?php
try{
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='Perth Tyres At 20 Locations - Quick Fittings + low Prices', page_description='View our low prices and great range on a tyres for cars, 4WDs, trucks and more. Compare our Perth Tyres stores and book in a fitting now!' WHERE url_key='perth' LIMIT 1;
                    UPDATE {$this->getTable('storelocator/region')} SET page_title='WA Beaurepaires Stores With Quick Fittings', page_description='View our complete list of Beaurepaires WA tyre stores from Perth to Kalgoorlie. Compare our WA shops and book online now!' WHERE url_key='western-australia' LIMIT 1;                    
		");

$installer->endSetup();
}catch (Exception $e){
    
}