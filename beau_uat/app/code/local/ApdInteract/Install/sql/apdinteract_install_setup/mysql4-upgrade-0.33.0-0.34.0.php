<?php
$installer = $this;
$installer->startSetup();

/**
 * Subscription Confirmation pages.
 */
$cmsArray = array(
                array('title' => 'Your Subscription has been confirmed','content' => 'Thanks for subscribing.', 'identifier' => 'success-confirmation'),
                array('title' => 'Invalid Subscription confirmation code','content' => '', 'identifier' => 'invalid-confirmation-code'),
                array('title' => 'Invalid Subscription ID','content' => '', 'identifier' => 'invalid-confirmation-id'),
            );

foreach($cmsArray as $item){
    
    $cmsPageData = array(
        'title' => $item['title'],
        'root_template' => 'one_column',
        'identifier' => $item['identifier'],
        'content_heading' => '',
        'stores' => array(0),//available for all store views
        'content' => <<<EOF
<h1 class="page-title">{$item['title']}</h1>
<h5>{$item['content']}</h5>
EOF
    );

    Mage::getModel('cms/page')->setData($cmsPageData)->save();
}


$installer->endSetup();