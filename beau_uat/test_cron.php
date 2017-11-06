<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/Mage.php';

Mage::app();

$main = dirname(__FILE__);
$path_from    = $main.'/for_import';
$path_to    = $main.'/var/costar/import/customer/pending';
$files = array_slice(scandir($path_from),2);
 
if(isset($files)) {
       
    rename($path_from.'/'.$files[0],$path_to.'/'.$files[0]);
    $observer = Mage::getModel("apdinteract_importexport/observer");
    $observer->processImport('');
}