<?php
/*
 * BFT-1803
 * This script creates Communications and SMS group
 * Assign Existing newsletter to its respective group
 * General Subscription => Communications
 * General Newsletter => Communications
 * Product News => SMS
 * Special Offers and Rewards => SMS
 */
$communicationGroup = array(
        'group_name' => 'Newsletters',
        'visible_in_frontend' => '1',
    );
$newsletter = Mage::getModel('newslettergroup/group')
    ->setData($communicationGroup)
    ->save();

$generalSubscription = Mage::getModel('newslettergroup/group')->load('General Subscription', 'group_name');
if ($generalSubscription->getId()){
    $generalSubscription->setParentGroupId($newsletter->getId())->save();
}
$generalNewsletter = Mage::getModel('newslettergroup/group')->load('Beaurepaires - Marketing - General Newsletter', 'group_name');
if ($generalNewsletter->getId()){
    $generalNewsletter->setParentGroupId($newsletter->getId())->save();
}

$smsGroup =     array(
        'group_name' => 'SMS',
        'visible_in_frontend' => '1',
    );

$newsletter = Mage::getModel('newslettergroup/group')
    ->setData($smsGroup)
    ->save();

$productNews = Mage::getModel('newslettergroup/group')->load('Beaurepaires - Marketing - Product News', 'group_name')->setParentGroupId($newsletter->getId())->save();
if ($productNews->getId()){
    $productNews->setParentGroupId($newsletter->getId())->save();
}
$specialOffers = Mage::getModel('newslettergroup/group')->load('Beaurepaires - Marketing - Special Offers and Rewards', 'group_name')->setParentGroupId($newsletter->getId())->save();
if ($specialOffers->getId()){
    $specialOffers->setParentGroupId($newsletter->getId())->save();
}