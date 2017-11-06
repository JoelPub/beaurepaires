<?php
/***************************************
 *** Multistore Newsletter Subscription ***
 ***************************************
 *
 * @copyright   Copyright (c) 2015
 * @company     NetAttingo Technologies
 * @package     Netgo_MulistoreNewsletter
 * @author 		NetGo
 * @dev			netattingomails@gmail.com
 *
 */
class Netgo_MulistoreNewsletter_Model_Newsletter_Mysql4_Subscriber extends Mage_Newsletter_Model_Mysql4_Subscriber
{

  /**
     * Load subscriber from DB by email
     *
     * @param string $subscriberEmail
     * @return array
     */
    public function loadByEmail($subscriberEmail)
    {

        /** @var $customerSession Mage_Customer_Model_Session */
        $customerSession = Mage::getSingleton('customer/session');
        $ownerId = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($subscriberEmail)
            ->getId();
 
        $storeId = $customerSession->isLoggedIn() && $ownerId == $customerSession->getId()
            ? $customerSession->getCustomer()->getStoreId()
            : Mage::app()->getStore()->getId();
 
        $select = $this->_read->select()
            ->from($this->getMainTable())
            ->where('subscriber_email=:subscriber_email')
            ->where('store_id=:store_id'); // Add store ID for newsletters
 
        $result = $this->_read->fetchRow($select, array(
            'subscriber_email'  => $subscriberEmail,
            'store_id'          => $storeId
        ));
 
        if (!$result) {
            return array();
        }
 
        return $result;
		
		//parent::loadByEmail($subscriberEmail);
    }
 
    /**
     * Load subscriber by customer
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return array
     */
    public function loadByCustomer(Mage_Customer_Model_Customer $customer)
    {
		
		
        $select = $this->_read->select()
            ->from($this->getMainTable())
            ->where('customer_id=:customer_id')
            ->where('store_id=:store_id');
 
        $result = $this->_read->fetchRow($select, array(
            'customer_id'   => $customer->getId(),
            'store_id'      => $customer->getStoreId()
        ));
 
        if ($result) {
            return $result;
        }
 
        $select = $this->_read->select()
            ->from($this->getMainTable())
            ->where('subscriber_email=:subscriber_email')
            ->where('store_id=:store_id');
 
        $result = $this->_read->fetchRow($select, array(
            'subscriber_email'  => $customer->getEmail(),
            'store_id'          => $customer->getStoreId()
        ));
 
        if ($result) {
            return $result;
        }
 
        return array();
    }
}
