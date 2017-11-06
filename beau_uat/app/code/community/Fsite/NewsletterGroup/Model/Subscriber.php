<?php
/**
 * Subscriber model
 *
 * @category   Fsite
 * @package    Fsite_NewsletterGroup
 * @author     Fsite
 */
class Fsite_NewsletterGroup_Model_Subscriber extends Mage_Newsletter_Model_Subscriber
{
    protected $_groupName;

    public function groupSubscribe($email, $group)
    {
        $this->loadByEmail($email);
        $customerSession = Mage::getSingleton('customer/session');

        if(!$this->getId()) {
            $this->setSubscriberConfirmCode($this->randomSequence());
        }

        $isConfirmNeed = (Mage::getStoreConfig(self::XML_PATH_CONFIRMATION_FLAG) == 1) ? true : false;
        $isOwnSubscribes = false;

        if (!$this->getId() || $this->getStatus() == self::STATUS_UNSUBSCRIBED || $this->getStatus() == self::STATUS_NOT_ACTIVE) {
            if ($isConfirmNeed === true) {
                // if user subscribes own login email - confirmation is not needed
                $ownerId = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email)
                    ->getId();
                $isOwnSubscribes = ($customerSession->isLoggedIn() && $ownerId == $customerSession->getId());
                if ($isOwnSubscribes == true){
                    $this->setStatus(self::STATUS_SUBSCRIBED);
                }
                else {
                    $this->setStatus(self::STATUS_NOT_ACTIVE);
                }
            } else {
                $this->setStatus(self::STATUS_SUBSCRIBED);
            }
            
            if ($this->getStatus() == self::STATUS_SUBSCRIBED) {
                $this->_groupSubscribeToSilverpop($email, $group);
            }
            
            $this->setSubscriberEmail($email);
            
            if ( is_array( $group ) ) {
                $groupString = implode( ',', $group );
                $this->setNewsletterGroupId( $groupString );
            }
            else {
                $this->setNewsletterGroupId( $group );
            }
            
        }

        if ($customerSession->isLoggedIn()) {
            $this->setStoreId($customerSession->getCustomer()->getStoreId());
            $this->setCustomerId($customerSession->getCustomerId());
        } else {
            $this->setStoreId(Mage::app()->getStore()->getId());
            $this->setCustomerId(0);
        }

        $this->setIsStatusChanged(true);

        try {
            $this->save();
            if ($isConfirmNeed === true
                && $isOwnSubscribes === false
            ) {
                $this->sendConfirmationRequestEmail();
            } else {
                $this->sendConfirmationSuccessEmail();
            }

            return $this->getStatus();
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Overriden
     * Saving customer subscription status
     *
     * @param   Mage_Customer_Model_Customer $customer
     * @return  Mage_Newsletter_Model_Subscriber
     */
    public function subscribeCustomer($customer)
    {
        $this->loadByCustomer($customer);

        if ($customer->getImportMode()) {
            $this->setImportMode(true);
        }

        if (!$customer->getIsSubscribed() && !$this->getId()) {
            // If subscription flag not set or customer is not a subscriber
            // and no subscribe below
            return $this;
        }

        if(!$this->getId()) {
            $this->setSubscriberConfirmCode($this->randomSequence());
        }

       /*
        * Logical mismatch between customer registration confirmation code and customer password confirmation
        */
       $confirmation = null;
       if ($customer->isConfirmationRequired() && ($customer->getConfirmation() != $customer->getPassword())) {
           $confirmation = $customer->getConfirmation();
       }

        $subscribed_on_confirm = false;
        if($customer->hasIsSubscribed()) {
            $status = $customer->getIsSubscribed() ? (!is_null($confirmation) ? self::STATUS_UNCONFIRMED : self::STATUS_SUBSCRIBED) : self::STATUS_UNSUBSCRIBED;
        } elseif (($this->getStatus() == self::STATUS_UNCONFIRMED) && (is_null($confirmation))) {
            $status = self::STATUS_SUBSCRIBED;
            $subscribed_on_confirm = true;
        } else {
            $status = ($this->getStatus() == self::STATUS_NOT_ACTIVE ? self::STATUS_UNSUBSCRIBED : $this->getStatus());
        }

        if($status != $this->getStatus()) {
            $this->setIsStatusChanged(true);
        }

        $this->setStatus($status);

        if(!$this->getId()) {
            $this->setStoreId($customer->getStoreId())
                ->setCustomerId($customer->getId())
                ->setEmail($customer->getEmail());
        } else {
            $this->setEmail($customer->getEmail());
        }

        $isSubscribed = Mage::app()->getRequest()->getParam( 'is_subscribed', 1 );
        $allSubscribeIds = Mage::app()->getRequest()->getParam( 'new_is_subscribed' );

        if ($isSubscribed == '1' && is_array($allSubscribeIds))
        {
            // Single tick of the subscribe box subscribes all
            // Otherwise sub preferences are left unchanged.
            // new_is_subscribed
            $isSubscribed = $allSubscribeIds;
        }

        // Changes made by BFT-1803
        $data = Mage::app()->getRequest()->getPost();
        if ( is_array( $isSubscribed ) ) {
            $groupString = implode( ',', $isSubscribed );
            $this->setNewsletterGroupId( $groupString );

        }elseif (isset($data['general_subscription']) || isset($data['_general_newsletter']) || isset($data['_product_news']) || isset($data['_special_offers_and_rewards'])) {
            // this is for the Admin Section => Manage Customer => Newsletter
            $newsletterGroupIds = array($data['general_subscription'], $data['_general_newsletter'], $data['_product_news'], $data['_special_offers_and_rewards']);
            $groupString = implode(',', $newsletterGroupIds);
            $this->setNewsletterGroupId($groupString)->save();
        }
        else {
            // Do not set a default Newsletter, allow customer to not subscribe
            $isSubscribed = 0;
            $this->setNewsletterGroupId( $isSubscribed );
        }

        if (!$data['email'] && !$data['account']['email'] && !$data['form_key']){
            // Added this condition for BFT-2159
            // This condition will take effect if users clicked the Confirm account in the Account Confirmation Email, we need to set all newsletter group at first for new Confirmed customers
            $groupString = implode( ',', $this->_getNewsletterGroupdIds());
            $this->setNewsletterGroupId($groupString);
        }

        $this->_groupSubscribeToSilverpop($customer->getEmail(), $isSubscribed);

        $this->save();
        $sendSubscription = $customer->getData('sendSubscription') || $subscribed_on_confirm;
        if (is_null($sendSubscription) xor $sendSubscription) {
            if ($this->getIsStatusChanged() && $status == self::STATUS_UNSUBSCRIBED) {
                $this->sendUnsubscriptionEmail();
            } elseif ($this->getIsStatusChanged() && $status == self::STATUS_SUBSCRIBED) {
                $this->sendConfirmationSuccessEmail();
            }
        }
        return $this;
    }

    /**
     * Get all the newsletter ids
     * @return Array of ids
     */
    private function _getNewsletterGroupdIds(){
        $collection = Mage::getResourceModel( 'newslettergroup/group_collection' )
            ->addFieldToFilter( 'visible_in_frontend', array( "eq" => 1 ) )
            ->addFieldToFilter( 'parent_group_id', array( "neq" => 0 ) )
            ->load();

        return $collection->getAllIds();
    }

    /**
     * Get the newsletter group name
     */
    public function getNewsletterGroupName()
    {
        if (!$this->_groupName) {
            $groupId = $this->getNewsletterGroupId();
            if ($groupId) {
                $this->_groupName = $this->_getNewsletterGroupName($groupId);
            }
        }

        return $this->_groupName;

    }
    
    private function _getNewsletterGroupName($group_id)
    {
       $group = Mage::getModel('newslettergroup/group')->load($group_id);
       return $group->getGroupName();       
    }

    /**
     * Get newsletter groups
     */
    public function getGroups()
    {
        if ( !$this->_groupCollection ) {
            // Add filter
            $collection = Mage::getResourceModel( 'newslettergroup/group_collection' )
                ->addFieldToFilter( 'visible_in_frontend', array( "eq" => 1 ) )
                ->load();

            $this->_groupCollection = $collection->getItems();
        }

        return $this->_groupCollection;
    }
    
    private function _groupSubscribeToSilverpop($email_address, $subscribe_group_array)
    {
        // Move all this to a background task if it's too slow
        // First, make sure the newsletter group lists are up to date with the Silverpop ones.
        Mage::helper('silverpopapi')->silverpopLogin();
        
        Mage::helper('newslettergroup')->syncSilverpopNewsletterGroups();
        
        if ( !is_array( $subscribe_group_array ) )
        {
            $subscribe_group_array = array($subscribe_group_array);
        }
        
        $add_list_names_array = false;
        $remove_list_names_array = false;
        
        foreach ($this->getGroups() as $group) 
        {            
            $group_name = $group->getGroupName();
            $group_id = $group->getId();
            
            if (in_array($group_id, $subscribe_group_array))
            {
                $add_list_names_array[] = $group_name;                
            }
            else
            {
                $remove_list_names_array[] = $group_name;
            }
        }
            
        if (is_array($add_list_names_array)) 
        {
            Mage::helper('silverpopapi')->addContactToLists($email_address, $add_list_names_array);
        }

        if (is_array($remove_list_names_array)) 
        {
            Mage::helper('silverpopapi')->removeContactFromLists($email_address, $remove_list_names_array);
        }
                
        Mage::helper('silverpopapi')->silverpopLogout();
        
    }
    
}
