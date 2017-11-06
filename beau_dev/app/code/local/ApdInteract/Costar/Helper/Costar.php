<?php
class ApdInteract_Costar_Helper_Costar extends Mage_Core_Helper_Abstract {

    /**    
    * Will check config value for debugging flag at System->Costar Data Import->Settings->Enable Debugging
    *   
    * @return  array    $debug      
    */
    public function isAllowedToLog() {
        $debug = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_sftp/costar_debugging_flag');
        return $debug;
    }

    /**    
    * Will log history of stock inventory import
    *  
    * @param    string  $file
    * @param    string  $logFile
    * @param    string  $status
    * @param    string  $duration
    * @param    array   $resultArray              
    * @param    int     $id        
    * @return   int     $int
    */
    public function logImportActivity($file, $logFile, $status, $duration, $resultArray, $id = false) {
        $log = Mage::getModel('apdinteract_costar/log');
        $currentStamp = $this->_convertDateTime();
        
        $updated = 0;
        $error = 0;
        $skipped = 0;
        
         //extract import result
        if (is_array($resultArray)) :
            $updated = $resultArray['updated'];
            $error = $resultArray['error'];
            $skipped = $resultArray['skipped'];
        endif;
                
        
        if (!$id) :  //insert if id provided yet          
            $log->setType("apdinteract_stock_update")
                    ->setDuration($duration)
                    ->setFilePath($file)
                    ->setLogFile($logFile)
                    ->setStartDate($currentStamp)
                    ->setStatus($status)                    
                    ->save();
        else:  //update if id is available              
            $log->load($id)
                    ->setStatus($status)
                    ->setUpdated($updated)
                    ->setSkipped($skipped)
                    ->setError($error)
                    ->setEndDate($currentStamp)
                    ->setFilePath($file)
                    ->save();
        endif;

        return $log->getId();
    }

    /**    
    * Will check all csv/ txt file for specific directory
    *      
    * @param    string  $directory        
    * @return   array   $csv
    */
    public function crawlDirectory($directory) {
        $extensions = "txt|csv";
        $csv = array();

        if(is_dir($directory)):
            $folder = new DirectoryIterator($directory);

            foreach ($folder as $fileinfo):
                if ($fileinfo->isFile() && stristr($extensions, $fileinfo->getExtension())):
                    array_push($csv, $fileinfo->getPathname());
                endif;
            endforeach;
        endif;

        return $csv;
    }
    
    /**    
    * Will get the filename from path
    *      
    * @param    string  $path        
    * @return   string  $csv
    */
    public function getFilename($path) {
        
        $parts = explode(DS, rtrim($path, DS));
        $id_str = array_pop($parts);
        return $id_str;
    }
    
    /**    
    * Will create directory with 777 permission
    *      
    * @param    string  $dir            
    */
    public function createDirectory($dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }
    
    /**    
    * Will fetch directory from remote
    *      
    * @param    string  $dir            
    */
    public function fetchFromRemote($pending_dir, $key = false) {
                
        $key_ar = explode('_',$key);            
        if(count($key_ar)>1)
            $key = $key_ar[1];
        
        //get config values                    
        $external_path = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_sftp/costar_stock_directory');
        $ftp_directory = ($external_path!= "") ? $external_path.$key : '/home/microhouse/upload/'.$key; 
                        
        if($key):            
            $csvs = $this->crawlDirectory($ftp_directory);
            
            $this->createDirectory($pending_dir); //create folder if not existing
            foreach($csvs as $csv):                
                $filename = $this->getFilename($csv);  
                rename($csv,$pending_dir. DS .$filename);
            endforeach;
        endif;
                
    }
    
    /**    
    * Will return real time depending on the timezone set on the backend
    *      
    * @return   date  $date            
    */
    protected function _convertDateTime(){
        
        $to = Mage::getStoreConfig('general/locale/timezone');
        $from = 'GMT';
        $fromDate = new DateTimeZone($from);
        $toDate = new DateTimeZone($to);
        $date = new DateTime($dateTime, $fromDate);
        $date->setTimezone($toDate);

        return $date->format('Y-m-d H:i:s');
    } 

}
