<?php
/**
*
*  BFT-2055, BFT-2065  Handle multiple file processing for product, customer & order
*/
class ApdInteract_ImportExport_Model_Observer {
    
    /**
     * Will process the manual import for product, customer & order
     *
     * @param  object    
     * @return  
     */
    public function processImport($schedule) {

        $directories = $this->_getPendingDirs(); // get all pending directories
        //$key = $this->_getType($schedule); //get if catalog_product, customer or order
        $key = 'customer';
        $failed_email = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_emails/costar_failed_email');
        if ($directory = $directories[$key]) :
            Mage::helper('costar/costar')->fetchFromRemote($directory, $key.'s'); // fetch external content            
            $csvs = Mage::helper('costar/costar')->crawlDirectory($directory); //get all pending contents             
            foreach ($csvs as $csv): //process each csv                
                $data = array('name' => 'Costar Auto Import for ' . $key, 'entity_type' => $key, 'file' => $csv, 'email' => $failed_email, 'path' => $directory);
                $data = $this->_createData($data); //formulate data for schedule
                $operation_id = $this->_createIndividualSchedule($data); //add schedule
                $this->_manualRunImport($operation_id, $data); //actual import
            endforeach;
        endif;
        
    }

    /**
     * Will get all the pending directories
     *
     * @param       
     * @return array   
     */
    private function _getPendingDirs() {
        $dumpDir = Mage::getBaseDir() . DS . 'var' . DS . 'costar' . DS . 'import' . DS;
        return array('catalog_product' => $dumpDir . 'catalog_product' . DS . 'pending', 'customer' => $dumpDir . 'customer' . DS . 'pending', 'order' => $dumpDir . 'order' . DS . 'pending');
    }

    /**
     * Will manually create individual schedule on scheduled import table
     *
     * @param  array       
     * @return int   
     */
    private function _createIndividualSchedule($data) {
        $operation = Mage::getModel('enterprise_importexport/scheduled_operation')->setData($data);
        $operation->save();
        return $operation->getId();
    }

    /**
     * Will formulate data for individual schedule
     *
     * @param  array       
     * @return array   
     */
    private function _createData($data) {
        $filename = Mage::helper('costar/costar')->getFilename($data['file']);
        $array = array(
            'operation_type' => 'import',
            'name' => $data['name'],
            'details' => $data['name'],
            'entity_type' => $data['entity_type'],
            'behavior' => 'append',
            'start_time' => '00:00:00',
            'freq' => 'D',
            'force_import' => '1',
            'status' => '1',
            'file_info' => array('server_type' => 'file', 'file_path' => $data['path'], 'file_name' => $filename),
            'email_receiver' => 'general',
            'email_sender' => 'general',
            'email_template' => 'enterprise_importexport_import_failed',
            'email_copy' => $data['email'],
            'email_copy_method' => 'bcc',
        );

        return $array;
    }
    
    
    /**
     * Call the import process
     *
     * @param  int   
     * @param  array       
     * @return string   
     */
    private function _manualRunImport($operationId, $data) {
        try {
            
            $schedule = new Varien_Object();
            $schedule->setJobCode(
                    ApdInteract_ImportExport_Model_Scheduled_Operation::CRON_JOB_NAME_PREFIX . $operationId
            );
            
            $operation = Mage::getModel('apdinteract_importexport/scheduled_operation')
            ->loadByJobCode($schedule->getJobCode());

            $result = false;
            if ($operation && ($operation->getStatus() || $forceRun)) {
                $result = $operation->run();
                Mage::getModel('enterprise_importexport/scheduled_operation')->setId($operationId)->delete();
            }
            
        } catch (Exception $e) {
           Mage::log('There is an error on manual import processing on '.$data['key'].' for file '.$data['file'].': '.$e, null, 'costar_import.log');
        }
    }

    /**
     * Will disect the type on the schdule config
     *
     * @param  object    $schedule        
     * @param  string    $duration      
     */
    private function _getType($schedule) {

        //check type
        $jobsRoot = Mage::getConfig()->getNode('crontab/jobs');
        $jobConfig = $jobsRoot->{$schedule->getJobCode()};
        $type = (string) $jobConfig->run->type;

        return $type;
    }

}
