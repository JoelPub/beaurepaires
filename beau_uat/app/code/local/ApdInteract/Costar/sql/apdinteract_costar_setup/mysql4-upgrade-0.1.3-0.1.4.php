<?php
/**
 * Added New Order Status : used to track if Order received/rejected in Costar
 */
$installer = $this;

$installer->startSetup();

$statuses = array(
    array('d' => 0, 'status' => 'costar_new',       'label' => 'Costar Pending',   'state' => Mage_Sales_Model_Order::STATE_PROCESSING),
    array('d' => 0, 'status' => 'costar_rejected',  'label' => 'Costar Rejected',  'state' => Mage_Sales_Model_Order::STATE_PROCESSING),
    array('d' => 0, 'status' => 'costar_accepted',  'label' => 'Costar Accepted',  'state' => Mage_Sales_Model_Order::STATE_PROCESSING)
);

foreach ($statuses as $status) {
    Mage::getModel('sales/order_status')
        ->setStatus($status['status'])
        ->setLabel($status['label'])
        ->assignState($status['state'], $status['d'])
        ->save();
}

$installer->endSetup();