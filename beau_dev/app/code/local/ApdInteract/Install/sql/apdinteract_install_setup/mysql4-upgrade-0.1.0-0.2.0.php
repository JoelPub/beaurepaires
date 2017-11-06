<?php

/**
 * - Create CMS Blocks in the homepage (footer)
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
 	array (
		'identifier' => 'footer-links1',
		'title' => 'footer-links1',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<ul class="no-bullet">
<li><a href="#">Tyres</a></li>
<li><a href="#">Wheels</a></li>
<li><a href="#">Batteries</a></li>
<li><a href="#">Services</a></li>
<li><a href="#">Learn</a></li>
</ul>
                    
EOF
	),
    array (
		'identifier' => 'footer-links2',
		'title' => 'footer-links2',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<ul class="no-bullet">
<li><a href="#">Cars</a></li>
<li><a href="#">4WDs</a></li>
<li><a href="#">Light Commercial</a></li>
<li><a href="#">Truck</a></li>
<li><a href="#">Agriculture</a></li>
</ul>
                    
EOF
	),
	array (
		'identifier' => 'footer-links3',
		'title' => 'footer-links3',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<ul class="no-bullet">
<li><a href="#">About us</a></li>
<li><a href="#">Careers</a></li>
<li><a href="#">Sitemap</a></li>
<li><a href="#">Privacy Policy</a></li>
<li><a href="#">Terms &amp; Conditions</a></li>
</ul>
                    
EOF
	),
	array (
		'identifier' => 'footer-contact',
		'title' => 'footer-contact',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
<ul class="no-bullet">
<li>Email Enquiry: <a href="#">Click here</a></li>
<li>General Enquiries: <a href="tel://13-23-81">13 23 81</a></li>
<li>Customer Service: <a href="tel://1-800-809-514">1800 809 514</a></li>
<li>Emergency Hotline: <a href="tel://1-800-106-010">1800 106 010</a></li>
</ul>
                    
EOF
	),
);

foreach ($blocks as $data){
$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($data)->save();
}
$installer->endSetup();




$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();						
							
$setup->addAttribute('catalog_product', 'test_attribute', array(
			 'label'             => 'Test',
			 'type'              => 'varchar',
			 'input'             => 'select',
			 'backend'           => 'eav/entity_attribute_backend_array',
			 'frontend'          => '',	
			 'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
			 'visible'           => true,
			 'required'          => true,
			 'user_defined'      => true,
			 'searchable'        => false,
			 'filterable'        => false,
			 'comparable'        => false,
			 'option'            => array ('value' => array('optionone' => array('Sony'),
									 'optiontwo' => array('Samsung'),
									 'optionthree' => array('Apple'),												
								)
							),
			 'visible_on_front'  => false,
			 'visible_in_advanced_search' => false,
			 'unique'            => false
));
 
$installer->endSetup();