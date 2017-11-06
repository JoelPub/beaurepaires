<?php

class ApdInteract_ImportExport_Model_Scheduled_Operation extends Enterprise_ImportExport_Model_Scheduled_Operation{

    /**
     * Result directory
     */
    const RESULT_DIRECTORY = 'costar/import';


    public function run(){
        $operation = $this->getInstance();
        $this->setLastRunDate(Mage::getSingleton('core/date')->gmtTimestamp());
        $result = false;
        try {
            $result = $operation->runSchedule($this);
        } catch (Exception $e) {
            $operation->addLogComment($e->getMessage());
        }

        $filePath = $this->getHistoryFilePath();
        if (!file_exists($filePath)) {
            $filePath = Mage::helper('enterprise_importexport')->__('File has been not created');
        }

        if (!$result || isset($e) && is_object($e)) {
            $operation->addLogComment(
                Mage::helper('enterprise_importexport')->__('Operation finished with fail status')
            );
            $this->sendEmailNotification(array(
                'operationName'  => $this->getName(),
                'trace'          => nl2br($operation->getFormatedLogTrace()),
                'entity'         => $this->getEntityType(),
                'dateAndTime'    => Mage::getModel('core/date')->date(),
                'fileName'       => $filePath
            ));
        }
        $this->_checkResult($this->getData(),$result);
        $this->setIsSuccess($result);
        $this->save();

        return $result;
    }

    /**
     * Check result for Scheduled import process
     *
     * @param $result
     * @param $operation
     */
    private function _checkResult($operation,$result){

        $source =  $operation['file_info']['file_path'] . DS .  $operation['file_info']['file_name'];

        if($operation['operation_type'] == 'import' && file_exists($source)){

            $freq    = array('D' => 'Daily', 'W' => 'Weekly');
            $outcome = array(1 => 'Successful', 0 => 'Failed');

            $type = 'scheduled_' . $operation['entity_type'] . '_'. $operation['id'];
            $result_path = $this->_moveOperationHistory($operation,$result);

            if(Mage::helper('costar/costar')->isAllowedToLog()){
                $file_log = 'import_data_' . Mage::getModel('core/date')->date('Ymd') . '_' . $operation['id'] . '.log';
            }else{
                $file_log = 'N/A';
            }

            $log = Mage::registry('log_data');

            Mage::getModel('apdinteract_costar/log')
                ->setType($type)
                ->setDuration($freq[$operation['freq']])
                ->setFilePath($result_path)
                ->setStatus($outcome[$result])
                ->setLogFile($file_log)
                ->setEndDate(Mage::getSingleton('core/date')->gmtTimestamp())
                ->setUpdated($log['updated'])
                ->setSkipped($log['skipped'])
                ->setError($log['error'])
                ->save();

            Mage::unregister('log_data');
        }

    }

    /**
     * @param $operation
     * @throws Mage_Core_Exception
     * @return $result_path
     */
    private function _moveOperationHistory($operation,$result){

        $r_path = array(0 => 'failed', 1 => 'success');

        $dirPath = $this->_defaultPath() . $operation['entity_type']  . DS . $r_path[$result] . DS;

        if (!is_dir(Mage::getBaseDir() . DS . $dirPath)) {
            mkdir(Mage::getBaseDir() . DS . $dirPath, 0750, true);
        }

        $fileName = join('_', array($operation['entity_type'], Mage::getModel('core/date')->date('Ymdhis')));

        $fileInfo = $operation['file_info'];
        if (isset($fileInfo['file_format'])) {
            $extension = $fileInfo['file_format'];
        } elseif(isset($fileInfo['file_name'])) {
            $extension = pathinfo($fileInfo['file_name'], PATHINFO_EXTENSION);
        } else {
            Mage::throwException(Mage::helper('enterprise_importexport')->__('Unknown file format'));
        }

        $filePath =  $dirPath . $fileName . '.' .  $extension;
        $source =  $operation['file_info']['file_path'] . DS .  $operation['file_info']['file_name'];

        $fs = new Varien_Io_File();
        if (!$fs->mv($source,$filePath)) {
            Mage::throwException(Mage::helper('enterprise_importexport')->__('Unable to save file history file'));
        }

        return  Mage::getBaseDir() . DS . $filePath;


    }


    /**
     * @return string
     */
    private  function _defaultPath(){

        return basename(Mage::getBaseDir('var')) . DS . self::RESULT_DIRECTORY . DS;
    }
}