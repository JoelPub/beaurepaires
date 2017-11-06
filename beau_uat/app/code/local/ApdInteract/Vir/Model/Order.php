<?php
class ApdInteract_Vir_Model_Order
    extends Mage_Core_Model_Abstract
{
    const VISIBILITY_HIDDEN = '0';
    const VISIBILITY_DIRECTORY = '1';

    protected function _construct()
    {
        /**
         * This tells Magento where the related resource model can be found.
         *
         * For a resource model, Magento will use the standard model alias -
         * in this case 'apdinteract_vir' - and look in
         * config.xml for a child node <resourceModel/>. This will be the
         * location that Magento will look for a model when
         * Mage::getResourceModel() is called - in our case,
         * ApdInteract_Vir_Model_Resource.
         */
        $this->_init('apdinteract_vir/order');
    }

    /**
     * This method is used in the grid and form for populating the dropdown.
     */
    public function getAvailableVisibilies()
    {
        return array(
            self::VISIBILITY_HIDDEN
                => Mage::helper('apdinteract_vir')
                       ->__('Hidden'),
            self::VISIBILITY_DIRECTORY
                => Mage::helper('apdinteract_vir')
                       ->__('Visible in Directory'),
        );
    }

//    private function _dbw() {
//        return Mage::getSingleton('core/resource')->getConnection('core_write');
//    }
//    
//    public function quickSave($id, $field, $value) {
//        // quicker than the "proper" way
//        // Sanitize $field:
//        $field = preg_replace('/[^a-zA-Z_]*/', '', $field);
//        
//        $sql = "UPDATE apdinteract_vir_order SET {$field} = ? WHERE parent_id = ? LIMIT 1";
//        $this->_dbw()->query($sql, array($value, $id));
//        
//    }
//    
    protected function _beforeSave()
    {
        parent::_beforeSave();

        /**
         * Perform some actions just before a order is saved.
         */
        $this->_updateTimestamps();
        $this->_prepareUrlKey();
        
        return $this;
    }
    
    protected function _afterSave()
    {
        parent::_afterSave();
        
        // Add mapping data for user permissions
        $this->_updateMapping();
        
        return $this;
    }

    protected function _updateTimestamps()
    {
        $timestamp = now();

        /**
         * Set the last updated timestamp.
         */
        $this->setUpdatedAt($timestamp);

        /**
         * If we have a order new object, set the created timestamp.
         */
        if ($this->isObjectNew()) {
            $this->setCreatedAt($timestamp);
        }

        return $this;
    }

    protected function _prepareUrlKey()
    {
        /**
         * In this method, you might consider ensuring
         * that the URL Key entered is unique and
         * contains only alphanumeric characters.
         */

        return $this;
    }
    
    protected function _updateMapping()
    {
        /**
         * In this method, you might consider ensuring
         * that the Mapping user data entered is unique and
         * contains the right mapped records, usually clrearing extra ones created by an admin user.
         */
        
        $vir_id = $this->getId();
        $currentUserID = $this->getCurrentId();
        $isAdmin = $this->checkIfAdminLoggedIn();
        if(!empty($vir_id)){
            
            //iwd_storelocator_users
            $collectionStores = Mage::getResourceModel('storelocator/stores_collection');
            $collectionStores->getSelect()
			->join( array('s'=>'iwd_storelocator_users'),'main_table.entity_id = s.store_id', array('s.store_id as sstoreid'))
            ->where("s.account_id = $currentUserID");
            $collectionStores->load();
            $storesCounter = 0;            
            foreach($collectionStores as $stores){
                $storesCounter += 1;
            } 
            
            $collectionMappings = Mage::getResourceModel('apdinteract_vir/orderstoremapping_collection');
            $collectionMappings->getSelect()
                    ->where("vir_id = ".$vir_id."");
            $collectionMappings->load();
            $mappingsCounter = 0;
            foreach($collectionMappings as $mapping){
                $mappingsCounter += 1;
            }
            
            if ($mappingsCounter > 0)
            {
                // check if this user belongs to more than one store, then assume the user is admin
                // if the user is just against one store, then we will assign this mapping to just that store, and remove any extra mappings
                if($storesCounter == 1)
                {
                    $store_id = 0;
                    foreach($collectionStores as $store){
                        $store_id = $store->getSstoreid();
                        break;
                    } 
                    $mappingsLeft = $mappingsCounter;
                    
                    // get list of excisting records, and if there are to many, get rid of them, and assign to cutrrent non admin user
                    foreach($collectionMappings as $mapping){
                        if($mapping->getStoreId() != $store_id){
                            $mapping->delete();
                            $mappingsLeft -+ 1;
                        }
                    }
                    if($mappingsLeft == 0){ // too many cleared - needs new mapping for this store
                        $this->addStoreMapping($vir_id,$store->getSstoreid());
                    }
                }
                // else - we should probably leave the current mappings                
            }
            else {
                // create new mapping records
                if($isAdmin) {
					foreach($collectionStores as $store){
                    	$this->addStoreMapping($vir_id,$store->getSstoreid());
                	}	
				}
                
            }
            
        }
        else {
            // this is a new record, and we do something different here            
            // we are doomed! We cannot create a mapping here!
        }
    }
    
    protected function addStoreMapping($vir_id,$store_id)
    {
        $mapping = Mage::getModel('apdinteract_vir/orderstoremapping');
        $mapping->setVirId($vir_id); 
        $mapping->setStoreId($store_id);
        $mapping->setVirType(0); //consumer type
        $mapping->save();
    }
    
    protected function getCurrentUser()
    {
        $admin_user_session = Mage::getSingleton('admin/session');
        $admin_user = $admin_user_session->getUser();
        return $admin_user;
    }
    
    protected function getCurrentId()
    {
        Mage::getSingleton('core/session', array('name'=>'adminhtml'));
        if($this->checkIfAdminLoggedIn()){
		  $adminuserId = $this->getCurrentUser()->getUserId();
		}
		else
		{
		  $adminuserId = 8; //admin user
		}
        
        return $adminuserId;
    }
    
    protected function checkIfAdminLoggedIn() {
		if(Mage::getSingleton('admin/session')->isLoggedIn()){
		  return true;
		}
	}
}

