<?php

$installer = $this;
$installer->startSetup();

    $key = 'home_block';
    $websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
    $model = Mage::getModel('cms/block');
    $collection = $model->getCollection()
        ->addFieldToFilter('identifier', array('like' => $key . '%'))
        ->addFieldToFilter('is_active',1)
        ->setOrder('identifier', 'asc');
    $collection->getSelect()->join(
        array('block_store' => $collection->getTable('cms/block_store')), 'main_table.block_id = block_store.block_id', array('store_id')
    )->where('block_store.store_id IN (?)', array($websiteAu->getId(),0));

    if(count($collection) > 0){
        foreach($collection as $item){
            $block = Mage::getModel('cms/block')->load($item->getId());
            $block->setIsActive(0); //disabled
            $block->save();
        }
    }

$installer->endSetup();


