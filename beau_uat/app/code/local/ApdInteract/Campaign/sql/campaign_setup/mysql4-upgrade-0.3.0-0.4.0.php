<?php

$installer = $this;

$installer->startSetup();


$cmsPageData = array(
    'title' => 'Thank You',
    'root_template' => 'one_column',
    'meta_keywords' => 'Thank You',
    'meta_description' => 'Thank Youn',
    'identifier' => 'thank-you',
    'content_heading' => 'Thank You',
    'stores' => array(0),//available for all store views
    'content' => "Thank You."
);

Mage::getModel('cms/page')->setData($cmsPageData)->save();


$installer->endSetup();
