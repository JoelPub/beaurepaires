<?php

$this->startSetup();

$categoryName = 'Services';
$category = Mage::getModel('catalog/category')->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('name',$categoryName)
    ->getFirstItem();

$date = new DateTime();

$menuModel = Mage::getModel('jmmegamenu/jmmegamenu')
    ->setTitle($category->getName() . ' CDP')
    ->setLink('services')
    ->setUrl('services')
    ->setCatid(0)
    ->setMenutype(2)
    ->setCategory($category->getUrl())
    ->setMegaCols(1)
    ->setStatus(1)
    ->setOrdering(4)
    ->setShowtitle(1)
    ->setMenugroup(12)
    ->setMegaSubcontent(1)
    ->setCreatedTime(date_format($date, 'Y-m-d H:i:s'))
    ->setUpdateTime(date_format($date, 'Y-m-d H:i:s'));

$menuModel->save();

$this->endSetup();
