<?php

$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
    array (
        'identifier' => 'tyres-search-not-found',
        'title' => 'Tyres Search Not Found',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
We currently do not stock matching tyres for your vehicle. If you know the specific details for the product you require, our team would be happy to find a retailer that stocks your product.                
EOF
    ),
    array (
        'identifier' => 'wheels-search-not-found',
        'title' => 'Wheels Search Not Found',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
We currently do not stock matching wheels for your vehicle. If you know the specific details for the product you require, our team would be happy to find a retailer that stocks your product.                 
EOF
    )
);

foreach ($blocks as $data){
    $cmsBlock = Mage::getModel('cms/block');
    $cmsBlock->setData($data)->save();
}


$installer->endSetup();


