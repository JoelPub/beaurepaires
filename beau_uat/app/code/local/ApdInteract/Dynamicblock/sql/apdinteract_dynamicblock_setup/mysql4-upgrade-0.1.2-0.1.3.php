<?php

$installer = $this;
$installer->startSetup();
$websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
//$websiteNz = Mage::getModel('core/website')->load('goodyear_nz', 'code');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array(
    array(
        'identifier' => 'home_block1_1col_1_fullcontent',
        'title' => 'Goodyear AU Homepage Static Block',
        'stores' => array($websiteAu->getId()),
        'is_active' => 1,
        'content' => <<<EOF
                <!-- Static mockup from FE for GAN-121  -->
    <!-- BE: please remove these comments -->
    <article class="home-promo-bars">
      <ul>
        <!-- start loop through static blocks -->
        <li>
          <!-- static block 1 start -->
          <h2>Passenger</h2>
          <a href="test1.html">View Passenger Tyres</a>
          <img src="/skin/frontend/polar/goodyear/images/home-promo-bar1.jpg">
          <!-- static block 1 end -->
        </li>
        <li>
          <!-- static block 2 start -->
          <h2>4WD &amp; SUV</h2>
          <a href="test2.html">View 4WD &amp SUV Tyres</a>
          <img src="/skin/frontend/polar/goodyear/images/home-promo-bar1.jpg">
          <!-- static block 2 end -->
        </li>
        <li>
          <!-- static block 3 start -->
          <h2>Light Commercial</h2>
          <a href="test3.html">View Light Commercial Tyres</a>
          <img src="/skin/frontend/polar/goodyear/images/home-promo-bar1.jpg">
          <!-- static block 3 end -->
        </li>
        <!-- end loop through static blocks -->
      </ul>
    </article>
    <!-- end of static mock up -->
                  
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


