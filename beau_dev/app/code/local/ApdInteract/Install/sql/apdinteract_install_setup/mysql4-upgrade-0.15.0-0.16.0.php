<?php

/**
 * - Create CMS Block (Footer)
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$block = array (
		'identifier' => 'footer-links4',
		'title' => 'footer-links4',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<ul class="no-bullet">
<li><a href="#">Link 1</a></li>
<li><a href="#">Link 2</a></li>
<li><a href="#">Link 3</a></li>
<li><a href="#">Link 4</a></li>
<li><a href="#">Link 5</a></li>
<li>Sample Contact: <a href="tel://1-234-567-890">1234 567 890</a></li>
</ul>
 			
EOF
);

$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($block)->save();
$installer->endSetup();
