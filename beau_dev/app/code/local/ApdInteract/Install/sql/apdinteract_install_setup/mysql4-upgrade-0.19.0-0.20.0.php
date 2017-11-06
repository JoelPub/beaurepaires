<?php

/**
 * - Update existing content for request and book price static blocks
 */
$installer = $this;
$installer->startSetup();
$requestPriceContent =
<<<EOF
Simply fill in the fields below and a Beaurepaires team member will contact you shortly to tailor a quote to suit your needs. 
Alternatively, call us on 13 23 81.
EOF;

$bookAppointmentContent =
<<<EOF
Simply fill in the fields below and one of our team will contact your shortly with your confirmation details.
Alternatively, book via phone on 13 23 81.
EOF;


$request = Mage::getModel('cms/block')->load('request-price-modal');
$request->setContent($requestPriceContent)->save();

$book = Mage::getModel('cms/block')->load('book-an-appoinment-modal');
$book->setContent($bookAppointmentContent)->save();

$installer->endSetup();
