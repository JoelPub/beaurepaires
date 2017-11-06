<?php

/**
 * - Create CMS Block in the Success Page
 */
$installer = $this;
$installer->startSetup();
$content =
<<<EOF
<img src="http://placehold.it/400x150">
EOF;

$block = array (
		'identifier' => 'checkout_success_phrase',
		'title' => 'Checkout Success Phrase',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.</p>
EOF
);


$cmsUpdateBlock = Mage::getModel('cms/block')->load('checkout_success_block');
$cmsUpdateBlock->setContent($content)->save();

$cmsNewBlock = Mage::getModel('cms/block')->load('checkout_success_phrase');
if (!$cmsNewBlock->getId()){
	$cmsNewBlock->setData($block)->save();	
}
$installer->endSetup();
