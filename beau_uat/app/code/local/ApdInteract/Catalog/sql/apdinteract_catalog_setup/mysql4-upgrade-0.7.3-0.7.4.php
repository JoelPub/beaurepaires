<?php


$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
	array (
		'identifier' => 'category-page-how-it-works',
		'title' => 'Category Page - How It Works',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<section class="how-it-works-static">
  <h2>How it works</h2>
  <div class="row">
    <div class="columns medium-4 text-center">
      <div class="vector-icons icon-home-browse" aria-hidden="true"></div>
      <h3>Browse</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="columns medium-4 text-center">
      <div class="vector-icons icon-home-purchase" aria-hidden="true"></div>
      <h3>Purchase</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="columns medium-4 text-center">
      <div class="vector-icons icon-home-fit" aria-hidden="true"></div>
      <h3>Fit Instore</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
  </div>   
</section>
EOF
	)
);

foreach ($blocks as $data){
	$cmsBlock = Mage::getModel('cms/block');
	$cmsBlock->setData($data)->save();
}


$installer->endSetup();

