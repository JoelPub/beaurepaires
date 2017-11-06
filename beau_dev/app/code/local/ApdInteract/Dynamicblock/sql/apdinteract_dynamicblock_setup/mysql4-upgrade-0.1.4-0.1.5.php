<?php

$installer = $this;
$installer->startSetup();
$websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
$websiteNz = Mage::getModel('core/website')->load('goodyear_nz', 'code');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array(
    array(
        'identifier' => 'fullwidth_block1_1col_1_passenger',
        'title' => 'fullwidth_block1_1col_1_passenger',
        'stores' => array($websiteAu->getId(),$websiteNz->getId()),
        'is_active' => 1,
        'content' => <<<EOF
<h2>Passenger</h2>
<a href="test1.html">View Passenger Tyres</a>
<img src="/skin/frontend/polar/goodyear/images/home-promo-bar1.jpg">
EOF
    ),
    array(
        'identifier' => 'fullwidth_block1_1col_2_suv',
        'title' => 'fullwidth_block1_1col_2_suv',
        'stores' => array($websiteAu->getId(),$websiteNz->getId()),
        'is_active' => 1,
        'content' => <<<EOF
<h2>4WD &amp; SUV</h2>
<a href="test2.html">View 4WD &amp SUV Tyres</a>
<img src="/skin/frontend/polar/goodyear/images/home-promo-bar1.jpg">
EOF
    ),
     array(
         'identifier' => 'fullwidth_block1_1col_3_lightcommercial',
         'title' => 'fullwidth_block1_1col_3_lightcommercial',
         'stores' => array($websiteAu->getId(),$websiteNz->getId()),
         'is_active' => 1,
         'content' => <<<EOF
<h2>Light Commercial</h2>
<a href="test3.html">View Light Commercial Tyres</a>
<img src="/skin/frontend/polar/goodyear/images/home-promo-bar1.jpg">
EOF
     )
);


foreach ($blocks as $data) {

    $helper = Mage::helper("dynamicblock");
    $cmsBlock = Mage::getModel('cms/block');
    $identifier = $data['identifier'];

    if (!$helper->isBlockExisting($identifier))
        $cmsBlock->setData($data)->save();
}


$installer->endSetup();


