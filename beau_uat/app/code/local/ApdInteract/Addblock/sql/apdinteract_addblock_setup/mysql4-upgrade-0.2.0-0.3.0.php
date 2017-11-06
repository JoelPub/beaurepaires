<?php

$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
    array (
        'identifier' => 'checkout_payment_tips',
        'title' => 'Checkout Payment Tips',
        'stores' => array(0),
        'is_active' => 1,
        'content' => <<<EOF
<p>Press the Submit Order button once only. Payment may take up to 20 seconds to process. Please do not close the browser window or press the browser Back button during this time. You will receive a confirmation window when the transaction completes. For security reasons you have 10 minutes from item confirmation to complete the payment. If your session times out, your cart will be emptied and you will need to reselect your items.</p>                  
EOF
    ) 
);

foreach ($blocks as $data){
    $cmsBlock = Mage::getModel('cms/block');
    $cmsBlock->setData($data)->save();
}


$installer->endSetup();


