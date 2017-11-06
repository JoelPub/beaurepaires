<?php

/**
 * - BFT-1910 As a Magento Administrator, I require the ability to publish a promo tile to the CDP pages
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
	array (
		'identifier' => 'promo-tyres-cdp',
		'title' => 'promo-tyres-cdp',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<img src="http://placehold.it/300x150" class="expand" alt="tyres-promo">
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus aut quidem, rerum pariatur eum dignissimos, magni sequi? Itaque vitae minus incidunt minima ab hic deleniti, repellat. Pariatur est iste illum.
                    
EOF
	),
	array (
		'identifier' => 'promo-batteries-cdp',
		'title' => 'promo-batteries-cdp',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<img src="http://placehold.it/300x150" class="expand" alt="batteries-promo">
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus aut quidem, rerum pariatur eum dignissimos, magni sequi? Itaque vitae minus incidunt minima ab hic deleniti, repellat. Pariatur est iste illum.
EOF
	),
	array (
		'identifier' => 'promo-services-cdp',
		'title' => 'promo-services-cdp',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<img src="http://placehold.it/300x150" class="expand" alt="services-promo">
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus voluptas fugiat aliquam sapiente, itaque a, repudiandae assumenda magni nulla fugit explicabo. Eos, illo? Dolorum ducimus officiis similique laborum, saepe repellat.
EOF
	),
	array (
		'identifier' => 'promo-tyres-results-cdp',
		'title' => 'promo-tyres-results-cdp',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<img src="http://placehold.it/300x150" class="expand" alt="search-promo">
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus aut quidem, rerum pariatur eum dignissimos, magni sequi? Itaque vitae minus incidunt minima ab hic deleniti, repellat. Pariatur est iste illum.
EOF
	),
	array (
		'identifier' => 'promo-wheels-cdp',
		'title' => 'promo-wheels-cdp',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum accusamus recusandae labore iusto porro, eos ducimus dolor magni distinctio voluptatem et enim explicabo iste, minus deleniti repellendus necessitatibus consequatur pariatur!
EOF
	),
);

foreach ($blocks as $data){
	$cmsBlock = Mage::getModel('cms/block');
	$cmsBlock->setData($data)->save();
}


$installer->endSetup();


