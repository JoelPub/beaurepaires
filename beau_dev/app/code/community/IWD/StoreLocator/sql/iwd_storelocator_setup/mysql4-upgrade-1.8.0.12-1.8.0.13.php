<?php

/**
 *  Create Static blocks for each city landing page
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
	
	array (
		'identifier' => 'sa-landing-page',
		'title' => 'South Australia Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
);

foreach ($blocks as $data){
$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($data)->save();
}

$installer->endSetup();