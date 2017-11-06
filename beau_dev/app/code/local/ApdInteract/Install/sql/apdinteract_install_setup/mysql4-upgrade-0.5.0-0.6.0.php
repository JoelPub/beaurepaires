<?php

/**
 * - Create CMS Block in the Success Page
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$block = array (
		'identifier' => 'checkout_success_block',
		'title' => 'Checkout Success Block',
		'stores' => array(0),
		'is_active' => 1,
 		'content' => <<<EOF
<div class="columns medium-5">
<img src="http://placehold.it/400x150">
</div>
<div class="columns medium-7">
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.</p>
</div>
 			
EOF
);

$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($block)->save();
$installer->endSetup();
