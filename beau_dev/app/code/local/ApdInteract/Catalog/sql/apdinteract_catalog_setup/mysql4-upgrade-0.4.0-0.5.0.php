<?php

$this->startSetup();
Mage::register('isSecureArea', 1);

$category = Mage::getModel('catalog/category');
$category->setPath('1/2') // set parent to be root category
->setName('Services')
    ->setIsActive(1)
    ->setUrlKey('services')
    ->setDisplayMode('PRODUCTS_AND_PAGE')
    ->setDescription('<p>Services CDP Desciption</p>')
    ->setMetaTitle('Services')
    ->setCustomUseParentSettings(0)
    ->setIsAnchor(1)
    ->save();
$this->endSetup();
