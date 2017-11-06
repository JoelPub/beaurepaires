<?php

$installer = $this;
$installer->startSetup();
$identifier = 'mygiftcard';
$cms = Mage::getModel('cms/page')->load('easter-campaign', 'identifier');
if ($cms->getId()){
    $cms->setIdentifier($identifier)->save();
}

$installer->endSetup();
