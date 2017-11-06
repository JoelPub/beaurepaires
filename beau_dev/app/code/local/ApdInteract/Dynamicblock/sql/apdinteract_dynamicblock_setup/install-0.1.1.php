<?php

/**
 * - GAN-74 As an Administrator, I require static blocks on the Login and Register pages (GOODYEAR)
 */
$installer = $this;
$installer->startSetup();
$websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
$websiteNz = Mage::getModel('core/website')->load('goodyear_nz', 'code');
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array(
    array(
        'identifier' => 'gyau-Login-Important-Message',
        'title' => 'gyau-Login-Important-Message',
        'stores' => array($websiteAu->getId()),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
EOF
    ),
    array(
        'identifier' => 'gyau-Register-Important-Message',
        'title' => 'gyau-Register-Important-Message',
        'stores' => array($websiteAu->getId()),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
EOF
    ),
    array(
        'identifier' => 'gynz-Login-Important-Message',
        'title' => 'gynz-Login-Important-Message',
        'stores' => array($websiteNz->getId()),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
EOF
    ),
    array(
        'identifier' => 'gynz-Register-Important-Message',
        'title' => 'gynz-Register-Important-Message',
        'stores' => array($websiteNz->getId()),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
EOF
    )
,
    array(
        'identifier' => 'bft-Login-Important-Message',
        'title' => 'bft-Login-Important-Message',
        'stores' => array(1),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
EOF
    ),
    array(
        'identifier' => 'bft-Register-Important-Message',
        'title' => 'bft-Register-Important-Message',
        'stores' => array(1),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
EOF
    ), array(
        'identifier' => 'bft-Login-Important-Message-all',
        'title' => 'bft-Login-Important-Message-all',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
EOF
    ),
    array(
        'identifier' => 'bft-Register-Important-Message-all',
        'title' => 'bft-Register-Important-Message-all',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
                <h4>New Here?</h4>
		<p>
                    <em>Registration is free and easy!</em>
		</p>
		<ul>
		  <li>Faster checkout</li>
		  <li>Save multiple shipping addresses</li>
		  <li>View and track orders and more</li>
		</ul>
                    
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


