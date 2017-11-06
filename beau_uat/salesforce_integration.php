<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/Mage.php';

Mage::app();

$process_name = "apdinteract_salesforce/process_business_".$argv[1];

$registryKey = 'salesforce-update';
    if (Mage::registry($registryKey))    
      Mage::unregister($registryKey); 

if(isset($argv[2])) {        
    Mage::register($registryKey, $argv[2]);               
}

echo "executing process $process_name\n";
if($argv[1]!='booking')
$result = Mage::getModel($process_name)->process()->getResult();
else
    $result = Mage::getModel($process_name)->process();

echo "\n";

echo "task completed";
