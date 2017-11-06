<?php
/*
 * This will auto process the stock inventory import for files under <root>/var/costar/import/inventory/pending 
 * 
 * @author Analyn Javier <ajavier@apdgroup.com>
 * @category Local
 * @package ApdInteract_Costar
 * 
 */
class ApdInteract_Costar_Model_Observer {

    /**    
    * Method called by cron on hourly/ daily basis
    *
    * @param  object   $schedule        
    */
    public function importStockFromCostar($schedule) {

        $dumpDir = Mage::getBaseDir() . DS . 'var' . DS . 'costar' . DS . 'import' . DS . 'inventory' . DS;

        $dirPending = $dumpDir . 'pending' . DS;
        $dir_success = $dumpDir . 'success' . DS;
        $dir_failed = $dumpDir . 'failed' . DS;
        $key = 'inventory';

        Mage::helper('costar/costar')->fetchFromRemote($dirPending,$key);


        $csvs = Mage::helper('costar/costar')->crawlDirectory($dirPending); //get all pending contents        
        foreach ($csvs as $csv):
            $logFile = 'invupdate-' . date("m-d-Y-H-i") . ".log";
            $log = Mage::getBaseDir() . '/var/log/' . $logFile;
            $duration = $this->_getDuration($schedule); // get cron duration            
            $status = "Processing";
            $resultArray = '';

            try {
                $file = fopen($csv, 'r');
                $duration = $this->_getDuration($schedule); // get cron duration            
                $status = "Processing";
                $resultArray = '';
                $id = Mage::helper('costar/costar')->logImportActivity($csv, $logFile, $status, $duration, $resultArray);

                $delimiter = "\t";
                $i = 0;
                //$this->_logRowActivity("----Start Store Inventory Import----", $logFile);
                $this->_logRowActivity("Parsing csv file...", $logFile);
                while (!feof($file)) :
                    $line = fgets($file, 2048);

                    if ($i > 0) :
                        $data = str_getcsv($line, $delimiter);

                        if (isset($sku[$data[0]]))
                            $sku[$data[0]] = $sku[$data[0]] + $data[1];
                        else
                            $sku[$data[0]] = $data[1];

                    /* if ($data[5] == 'Active') // disable store import
                      $status = 1; // disable store import
                      else// disable store import
                      $status = 0; // disable store import
                     */
                    //$this->_logRowActivity("Import SKU: " . $data[1] . " to  Store: " . $data[0] . "...", $logFile); // disable store import
                    //$this->_importToCostaTable($data[1], $data[0], $data[3], $status, $logFile); //import to costar store inventory table // disable store import

                    endif;

                    $i++;
                endwhile;

                fclose($file);

                $this->_logRowActivity("----End Store Inventory Import----", $logFile);
                $this->_logRowActivity("----Start Magento Inventory Import----", $logFile);
                $resultArray = $this->_importToMagento($sku, $logFile); //import indovidual products at the magento db                
                $this->_logRowActivity("----End Magento Inventory Import----", $logFile);

                //$fileName = Mage::helper('costar/costar')->getFilename($csv);
                $base_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true);
                $newFileName = time() . '.csv';
                $newPath = $dir_success . $newFileName;
                $status = "Successful";

                if (Mage::helper('costar/costar')->isAllowedToLog() != 1)
                    $logFile = "N/A";

                $link = str_replace(Mage::getBaseDir(), $base_url, $newPath);
                Mage::helper('costar/costar')->logImportActivity($newPath, $logFile, $status, $duration, $resultArray, $id);
                Mage::helper('costar/costar')->createDirectory($dir_success);
                rename($csv, $newPath); //move to success directory
            } catch (Exception $ex) {
                $status = "Failed";
                $newFileName = time() . '.csv';
                $newPath = $dir_failed . $newFileName;
                Mage::helper('costar/costar')->createDirectory($dir_failed);
                Mage::helper('costar/costar')->logImportActivity($csv, $logFile, $status, $duration, $resultArray);
                rename($csv, $newPath); //move to failed directory
            }
        endforeach;        
    }

    /**    
    * Will import qty of sku on magento
    *
    * @param  array   $data        
    * @param  string  $logFile
    * @return array         
    */
    private function _importToMagento($data, $logFile) {

        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        $count = 0;
        $skipped = 0;
        $updated = 0;
        $error = 0;
        foreach ($data as $sku => $qty) :

            $product = Mage::getModel('catalog/product');
            $product_id = $product->getIdBySku($sku);
            $product->load($product_id);

            if ($product_id):

                $stockItem = Mage::getModel('cataloginventory/stock_item');
                $stockItem->assignProduct($product);
                $this->_logRowActivity("Import SKU: " . $sku . " to magento...", $logFile);
                if ($stockItem->getQty() > $qty || $stockItem->getQty() < $qty):
                    $stockItem->setData('is_in_stock', 1)
                            ->setData('stock_id', 1)
                            ->setData('manage_stock', 1)
                            ->setData('use_config_manage_stock', 0)
                            ->setData('qty', $qty);
                    try {
                        $stockItem->save();
                        $updated++; //count updated products
                        $this->_logRowActivity("Update Stock Level Successful for sku: " . $sku, $logFile);
                    } catch (Exception $ex) {
                        $error++;
                        $this->_logRowActivity("There was an issue updating stock for SKU: " . $sku . " -" . $ex->getMessage() . ".", $logFile);
                    }

                else:
                    $this->_logRowActivity("Skipped Update Stock Level for sku: " . $sku, $logFile);
                    $skipped++; //count updated products
                endif;
            else :
                $this->_logRowActivity("Update Stock Level not Successful for sku: " . $sku . ", product does not exists", $logFile);
                $error++;
            endif;


        endforeach;
        $result = array("error" => $error, "updated" => $updated, "skipped" => $skipped);

        return $result;
    }
    
    /**    
    * Will import qty of sku to apd costar store table
    *
    * @param  string    $sku        
    * @param  string    $store_code
    * @param  int       $qty
    * @param  string    $status
    * @param  string    $logFile    
    *       
    */
    private function _importToCostaTable($sku, $store_code, $qty, $status, $logFile) {

        $store = Mage::getModel('apdinteract_costar/costar');

        $storeAndProduct = $store->load($store_code)
                ->getCollection()
                ->AddFieldToFilter('sku', $sku); // select sku + store

        if ($storeAndProduct->count() <= 0):
            $store->setCostarStoreCode($store_code)
                    ->setSku($sku)
                    ->setQty($qty)
                    ->setIsActive($status);

            try {
                $store->save();
                $this->_logRowActivity("Add Successful for SKU: " . $sku . " Store: " . $store_code, $logFile);
            } catch (Exception $ex) {
                $this->_logRowActivity("There was an issue adding SKU: " . $sku . " Store: " . $store_code . " -" . $ex->getMessage(), $logFile);
            }

        else:
            $storeDetails = $storeAndProduct->getFirstItem();
            $storeDetails->setQty($qty)
                    ->setIsActive($status);


            try {
                $storeDetails->save();
                $this->_logRowActivity("Update Successful for SKU: " . $sku . " Store: " . $store_code . ".", $logFile);
            } catch (Exception $ex) {
                $this->_logRowActivity("There was an issue updating SKU: " . $sku . " Store: " . $store_code . " -" . $ex->getMessage(), $logFile);
            }

        endif;
    }

    
    /**    
    * Will log  activity on the log file
    *
    * @param  string    $msg        
    * @param  string    $file      
    */
    private function _logRowActivity($msg, $file) {
        if (Mage::helper('costar/costar')->isAllowedToLog() == 1) //check if debug mode is on
            Mage::log($msg, null, $file);
    }
    
    
    /**    
    * Will disect the duration on the schdule config
    *
    * @param  object    $schedule        
    * @param  string    $duration      
    */
    private function _getDuration($schedule) {

        //check duration
        $jobsRoot = Mage::getConfig()->getNode('crontab/jobs');
        $jobConfig = $jobsRoot->{$schedule->getJobCode()};
        $duration = (string) $jobConfig->run->duration;

        return $duration;
    }

    /**
     * By Cron Job send Order to Costar who have
     * Status: processing
     * And State: costar_rejected and costar_accepted
     * @param $schedule
     */
    public function sendOrderToCostar($schedule){

        Mage::helper('costar/api')->log("ApdInteract_Costar_Model_Observer::sendOrderToCostar START");

        //Get All Order with status Proccessing.
        $_read = Mage::getModel('core/resource')->getConnection('core_read');

        $_select = $_read->select() //Varien_Db_Select;
            ->from(array('sfo' => 'sales_flat_order'),array('entity_id'))
            ->where("sfo.state = 'processing'")
            ->where("sfo.status NOT IN ('costar_rejected', 'costar_accepted')")
            ->where("sfo.request_type NOT IN ('PRICE REQUEST', 'BOOKING')")
            ->where("sfo.created_at > :from");

        $orderIdArray = $_read->fetchAll($_select, array( 'from' => date("Y-m-d H:i:s", strtotime('-1 day'))));

        if(empty($orderIdArray)){
            return;
        }

        foreach($orderIdArray as $orderId) {

            $_order = Mage::getModel('sales/order')->load($orderId['entity_id']);

            Mage::helper('costar/api')->log("ApdInteract_Costar_Model_Observer::sendOrderToCostar Order Id:".$orderId['entity_id']);

            //Prepare Costar Order Info
            $costarFieldsArray = Mage::helper('costar/api')->prepareCostarOrderInfo($_order);

            // Make a Costar SubmitOrder Api call
            $result = Mage::getModel('apdinteract_costar/api')->submitOrder($costarFieldsArray[0],$costarFieldsArray[1]);

            //Update Order status and History on API response
            if($result['error']){
                Mage::helper('costar/api')->costarRejectedHistory($_order,$result['message']);
            }else{
                Mage::helper('costar/api')->costarAcceptedHistory($_order,$result['message']);
            }

        }

        Mage::helper('costar/api')->log("ApdInteract_Costar_Model_Observer::sendOrderToCostar END");

    }

    /**
     *
     * Costar API montioring Trigger
     *
     * @param $schedule
     */
    public function pingCostarApi($schedule) {

        $response = Mage::getModel('apdinteract_costar/Apimonitoring')->ping();

        //If no error found in response then ignore.
        if(!$response['error'] || !$emailAddress = Mage::getStoreConfig('system/enterprise_import_export_log/error_email')) {
            return false;
        }

        //send email
        $message = "";
        $message .= "Costar API response ";
        $message .= "<br/> Status Code: ".((isset($response['code'])) ? $response['code']: '');
        $message .= "<br/> Error: ".((isset($response['message'])) ? $response['message']: '');
        $subject = "Monitoring error";

        //Can send api error email
        if(Mage::helper('costar/api')->canSendApiErrorEmail()) {
            Mage::helper('costar/api')->sendEmail($subject, $message, $emailAddress);
            Mage::helper('costar/api')->setEmailTriggerTime();
        }

    }


}
