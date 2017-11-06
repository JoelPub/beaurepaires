<?php
class ApdInteract_Campaign_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function checkIfDuplicate($email) {
        $campaign_model = Mage::getModel("campaign/campaign");
        $campaign = $campaign_model->getCollection()
                ->addFieldToFilter('email', $email);

        return ($campaign->count() > 0) ? true : false;
    }
    
    public function clearSession() {
        $session = Mage::getSingleton('core/session');
        $session->setPostdata('');
    }
    
    public function addSession($postData) {
        $session = Mage::getSingleton('core/session');
        $session->setPostdata($postData);
    }
    
    public function convertDateTime(){
        
        $to = Mage::getStoreConfig('general/locale/timezone');
        $from = 'GMT';
        $fromDate = new DateTimeZone($from);
        $toDate = new DateTimeZone($to);
        $date = new DateTime($dateTime, $fromDate);
        $date->setTimezone($toDate);

        return $date->format('Y-m-d H:i:s');
    }
    
    public function getAllStoreViews() {
        $addoptions = array();
        $stores_new = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
        $option = array(
            'label' => Mage::helper('adminhtml')->__('All Store Views'),
            'value' => 0
        );

        if (!in_array($option, $stores_new)) {
            $addoptions[] = $option;
            $stores_new = array_merge($addoptions, $stores_new);
        }
        
        return $stores_new;
        
    }
    
    public function getAllEmailTemplates() {
        return array_merge(array(0=>'Not Applicable'),Mage::getResourceModel('core/email_template_collection')->load()->toOptionArray());
    }
    
    public function getPageInfoByIdentifier($identifier){
        $store_id = Mage::app()->getStore()->getStoreId();
        $model = Mage::getModel('campaign/setup');
        $page = $model->getCollection()
                      ->addFieldToFilter('cms_page',$identifier)
                      ->addFieldToFilter('store_id',$store_id)
                      ->addFieldToFilter('active',1)
                      ->getFirstItem();        
        return $page;
    }
    
}
	 