<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/Mage.php';

Mage::app();

 
//First we load the model
$model = Mage::getModel('pricefilter/cron');
 
//Then execute the task
$model->UpdateConfigurablePrice();
