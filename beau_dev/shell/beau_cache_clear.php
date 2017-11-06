<?php
ini_set("display_errors", 1);

require '../app/Mage.php';

$app = Mage::app(); 
if($app != null) { 
    $cache = $app->getCache(); 
    if($cache != null) { 
        $cache->clean();         
    }     
}