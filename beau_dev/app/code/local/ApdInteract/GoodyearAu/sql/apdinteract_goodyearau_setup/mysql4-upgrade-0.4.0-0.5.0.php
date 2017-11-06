<?php
/**
 * Update Goodyear Au and NZ Website Star Rating Settings
 */

$websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
$websiteNz = Mage::getModel('core/website')->load('goodyear_nz', 'code');

$auId = $websiteAu->getId();
$nzId = $websiteNz->getId();


$ratingModel = Mage::getModel('rating/rating');
$stores = array("1","$auId","$nzId");
$position = 0;
$stores[] = 0;
$rating_code='Rating';
$rating_codes = array("Main Website","Beaurepaires","Goodyear AU","Goodyear NZ");

$ratingModel->setRatingCode($rating_code)
    ->setRatingCodes($rating_codes)
    ->setStores($stores)
    ->setPosition($position)
    ->setId('1')
    ->setEntityId('1')
    ->save();
