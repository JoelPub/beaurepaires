<?php
/*
URL: sftp.microhouse.com.au
Username: Beaurepaires
Password: 45heRfU92W
Folder to Place Files: /Upload
 */

// Apd_Beaurepaires_Costar_Integration

// Bootstrap
require_once('../../app/Mage.php'); //Path to Magento
umask(0);
Mage::app();

// Some test stuff:

$costar = Mage::helper('costar');

// Test #1: Process stock file queue
$costar->processStockFileQueue();

// Test #2: Get stock qty from DB
echo "<pre>";

$qty_test = array(
    array('U881', '8490129', 5),
    array('U757', '568398',  3),
    array('U101', '3500228', 0)
);

foreach ($qty_test as $test_values) {
    $store_code = $test_values[0];
    $sku = $test_values[1];
    $expected_qty = $test_values[2];
    $actual_qty = $costar->getStockQty($store_code, $sku);
    
    $test_result = 'FAIL';
    if ($expected_qty == $actual_qty) {
        $test_result = 'PASS';
    }
    echo "Qty for sku:{$sku} store:{$store_code} - expected:{$expected_qty}, actual:{$actual_qty} => {$test_result} \r\n";
    
}



echo "\r\n\r\n\r\n the end";