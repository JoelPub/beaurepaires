<?php

class Wyomind_Googleproductratings_Block_Adminhtml_System_Config_Form_Field_DataFeedLink extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) 
    {


        if ($storeCode = Mage::getSingleton('adminhtml/config_data')->getStore()) {
            $storeIds = array(Mage::getModel('core/store')->load($storeCode)->getId());
            $websiteCode = Mage::getModel('core/store')->load($storeCode)->getWebsite()->getCode();
            $directory = 'default/' . $websiteCode . '/' . $storeCode . '/';
            $updated_at = Mage::app()->getStore($storeIds[0])->getConfig("googleproductratings/storage/updated_at");
        } elseif ($websiteCode = Mage::getSingleton('adminhtml/config_data')->getWebsite()) {
            $websiteId = Mage::getModel('core/website')->load($websiteCode)->getId();
            $storeIds = array_values(Mage::app()->getWebsite($websiteId)->getStoreIds());
            $directory = 'default/' . $websiteCode . '/';
            $updated_at = Mage::app()->getWebsite($websiteId)->getConfig("googleproductratings/storage/updated_at");
        } else {
            $updated_at = Mage::getStoreConfig('googleproductratings/storage/updated_at');
            $stores = Mage::app()->getStores();
            $storeIds = array();
            foreach ($stores as $eachStore => $val) {
                $storeIds[] = Mage::app()->getStore($eachStore)->getId();
            }

            $directory = 'default/';
        }

       
        $finalFile = Mage::getStoreConfig("googleproductratings/storage/file_name") . ".xml";
        $path = Mage::getStoreConfig("googleproductratings/storage/file_path") .'/'. $directory;

        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => Mage::getBaseDir()."/".$path));
        $html = null;
        $fileUrl = null;
        $lastUpdate = null;

        if (!$io->fileExists($finalFile, false)) {
            $html .= "<span id='googleratings_alert' style='color:red; padding:2px; background:#FDFAB1'>No data feed generated. Please click the update button or define a schedule.</span>";
        } else {
            $url = Mage::app()->getStore($storeIds[0])->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true);
            $fileUrl = $url.str_replace("//", "/", $path . $finalFile);




            $lastUpdate = "<br> Last update: " . Mage::getModel("core/date")->date("d M Y H:i:s", $updated_at);
        }
        $html .=  '<a target="_blank" id="googleratings_link" href=' . $fileUrl . '>' . $fileUrl . "</a> <span id='googleratings_updated_at'>" . $lastUpdate . "</span>\n";

        return $html;
    }

}
