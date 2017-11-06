<?php

class ApdInteract_Dynamicblock_Model_Dynamicblock extends Mage_Core_Model_Abstract {

    protected $_customer_id = 0;

    public function getAllBlocksPerPosition($position) {

        return $this->_search($position);
    }

    public function getBlockByGroup($group_id) {
        $identifier = "cgi" . $group_id;
        return $this->_search($identifier);
    }

    /**
     * @return array
     */
    public function getBlockContent() {

        $blockArray = array();
        $storeId = Mage::app()->getStore()->getId();
        $model = Mage::getModel('cms/block');

        $collection = $model->getCollection()
                        ->addFieldToFilter('identifier', array('like' => 'home_block%'))
                        ->addFieldToFilter('is_active', 1)
                        ->addFieldToFilter(
                                array('customer_group_id', 'customer_group_id'), array(array('eq' => 0), array('eq' => $this->_getCustomerId()),)
                        )->setOrder('identifier', 'asc');

        $collection->getSelect()->join(array('block_store' => $collection->getTable('cms/block_store')), 'main_table.block_id = block_store.block_id', array('store_id')
            )->where('block_store.store_id IN (?)', array($storeId,0));

        foreach ($collection as $data) {

            $block = explode('_', $data->getIdentifier());

            $blockArray[$block[1]][$data->getcustomerGroupId()][] = $data->getData();
        }

        return $blockArray;
    }

    /**
     * @return array
     */
    public function getBlockPromoContent() {

        $blockArray = array();
        $storeId = Mage::app()->getStore()->getId();
        $model = Mage::getModel('cms/block');

        $collection = $model->getCollection()
            ->addFieldToFilter('identifier', array('like' => 'GY_home_page_fullwidth_block%'))
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter(
                array('customer_group_id', 'customer_group_id'), array(array('eq' => 0), array('eq' => $this->_getCustomerId()),)
            )->setOrder('identifier', 'asc');

        $collection->getSelect()->join(array('block_store' => $collection->getTable('cms/block_store')), 'main_table.block_id = block_store.block_id', array('store_id')
        )->where('block_store.store_id IN (?)', array($storeId,0));

        foreach ($collection as $data) {

            $block = explode('_', $data->getIdentifier());
            $blockArray[$block[4]][$data->getcustomerGroupId()][] = $data->getData();
        }

        return $blockArray;
    }

    private function _search($key) {

        $model = Mage::getModel('cms/block');
        $storeId = Mage::app()->getStore()->getId();
        if ($this->_customerSession()->isLoggedIn()) {
            $collection1 = $model->getCollection()
                    ->addFieldToFilter('identifier', array('like' => $key . '%'))
                    ->addFieldToFilter('is_active', 1)
                    ->addFieldToFilter('customer_group_id', array('eq' => $this->_getCustomerId()))
                    ->setOrder('identifier', 'asc');

            $collection1->getSelect()->join(
                    array('block_store' => $collection1->getTable('cms/block_store')), 'main_table.block_id = block_store.block_id', array('store_id')
            )->where('block_store.store_id IN (?)', array($storeId,0));

            if ($collection1->count() > 0) {
                return $collection1;
            }
            
            
            
        }

        $collection = $model->getCollection()
                ->addFieldToFilter('identifier', array('like' => '%'.$key . '%'))
                ->addFieldToFilter('is_active', 1)
                ->addFieldToFilter(
                        array('customer_group_id', 'customer_group_id'), array(array('eq' => 0), array('eq' => $this->_getCustomerId()),)
                )
                ->setOrder('identifier', 'asc');

        $collection->getSelect()->join(
                array('block_store' => $collection->getTable('cms/block_store')), 'main_table.block_id = block_store.block_id', array('store_id')
        )->where('block_store.store_id IN (?)', array($storeId,0));

        return $collection;
    }

    /**
     * Get customer group ID
     * @return int
     */
    private function _getCustomerId() {

        $session = $this->_customerSession();

        if ($session->getCustomerGroupId()) {
            $this->_customer_id = $session->getCustomerGroupId();
        }
        return $this->_customer_id;
    }

    /**
     * Customer Session
     * @return Mage_Core_Model_Abstract
     */
    private function _customerSession() {

        return Mage::getSingleton('customer/session');
    }

}
