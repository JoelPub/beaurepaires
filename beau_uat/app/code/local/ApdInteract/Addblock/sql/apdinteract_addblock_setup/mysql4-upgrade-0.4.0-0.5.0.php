<?php

$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
    array (
        'identifier' => 'homepage-promo-1-full-width',
        'title' => 'Homepage Promo 1',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
         [home promo 1]              
EOF
    ),
    array (
        'identifier' => 'homepage-promo-1-left',
        'title' => 'Homepage Promo 1 Left',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
[home promo 1 left]
EOF
    ),
    array (
        'identifier' => 'homepage-promo-2-right',
        'title' => 'Homepage Promo 2 Right',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
    [home promo 2 Right]
EOF
    )
);

foreach ($blocks as $data){
    $cmsBlock = Mage::getModel('cms/block');
    $cmsBlock->setData($data)->save();
}


$installer->endSetup();


