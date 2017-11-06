<?php

$installer = $this;
$installer->startSetup();

$whitelistBlock_slider = Mage::getModel('admin/block')->load('cms/block', 'block_name');
$whitelistBlock_slider->setData('block_name', 'cms/block');
$whitelistBlock_slider->setData('is_allowed', 1);
$whitelistBlock_slider->save();

$installer->endSetup();


