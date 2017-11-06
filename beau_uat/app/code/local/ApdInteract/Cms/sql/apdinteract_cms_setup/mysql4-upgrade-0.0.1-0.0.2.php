<?php

$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array(
    array(
        'identifier' => 'configurable-tyre-wheel-selector',
        'title' => 'Configurable Tyre & Wheel Selector',
        'stores' => array(1),
        'is_active' => 1,
        'content' => <<<EOF
<div class="hero">
   <!-- slider -->
   <div class="hero-slider">
      {{block type="auguria_sliders/cms_page_cslider" template="auguria/sliders/configurable_slider.phtml" }}
   </div>
   {{block type="searchtyre/index" template="searchtyre/index.phtml" }}
</div>
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


$whitelistBlock_slider = Mage::getModel('admin/block')->load('auguria_sliders/cms_page_cslider', 'block_name');
$whitelistBlock_slider->setData('block_name', 'auguria_sliders/cms_page_cslider');
$whitelistBlock_slider->setData('is_allowed', 1);
$whitelistBlock_slider->save();

$whitelistBlock_selector = Mage::getModel('admin/block')->load('searchtyre/index', 'block_name');
$whitelistBlock_selector->setData('block_name', 'searchtyre/index');
$whitelistBlock_selector->setData('is_allowed', 1);
$whitelistBlock_selector->save();


$installer->endSetup();


