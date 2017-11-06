<?php
require_once(__DIR__.'/../../../../../../../app/Mage.php');

Mage::app();
$helper = Mage::helper('silverpopapi');

echo "<pre>";

// Always login and logout.
$helper->silverpopLogin();

Mage::helper('newslettergroup')->syncSilverpopNewsletterGroups();


$list_names_array = array(
    'Beaurepaires - Marketing - General Newsletter',
    'Beaurepaires - Marketing - Special Offers and Rewards',
    'Beaurepaires - Marketing - Product News',
    );

$email_address = 'silaspalmer+test123@gmail.com'; // - ID: 731464158
// 'silaspalmer@gmail.com'; // - ID: 731464158
// 'spalmer@apdgroup.com'; // - ID:731464049

// $helper->createContactLists($list_names_array);

    
print_r($helper->addContactToLists($email_address, $list_names_array));

print_r($helper->removeContactFromLists($email_address, $list_names_array));

// print_r($helper->getLists());

print_r($helper->getListsStartingWith('B'));

//$helper->deleteContactList($list_name);
//print_r($helper->getListsStartingWith('B'));

// print_r($helper->getDatabaseDetails());

$helper->silverpopLogout();

  