<?php
class ApdInteract_Vir_Adminhtml_ReportconsumerController extends Mage_Adminhtml_Controller_Report_Abstract
{

    const CSV_FILE_NAME = "vir_report_consumer";

    public function indexAction(){

        $this->_title($this->__('Reports'))->_title($this->__('VIR'))->_title($this->__('VIR Consumer Vehicle'));
        $this->_initAction();
        $filterFormBlock = $this->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction(array(
            $filterFormBlock
        ));

        $this->renderLayout();

    }


    /**
     * Export VIR Consumer Report
     *
     */
    public function exportReportConsumerCsvAction(){


        $session_data =  Mage::getSingleton('admin/session')->getReportFilter();
        $status    = isset($session_data['status']) ? $session_data['status'] : '';
        $from_date = isset($session_data['from']) ? $session_data['from'] : '';
        $to_date   = isset($session_data['to']) ? $session_data['to'] : '';


        $vir_consumer = Mage::getModel('apdinteract_vir/order')->getCollection()
            ->addFieldToSelect('*');

        // Filter created date
        if($from_date != '' && $to_date != ''){
            $from_date = date('Y-m-d',strtotime($from_date));
            $to_date = date('Y-m-d',strtotime($to_date));
            $filter_date = array('from'=>$from_date,'to'=>$to_date);
            $vir_consumer->addFieldToFilter('created_at',array($filter_date));
        }elseif($from_date != '' && $to_date == ''){
            $from_date = date('Y-m-d',strtotime($from_date));
            $vir_consumer->addFieldToFilter('created_at',array('gteq' => $from_date));
        }elseif($from_date == '' && $to_date != ''){
            $to_date = date('Y-m-d',strtotime($to_date));
            $vir_consumer->addFieldToFilter('created_at',array('lteq' => $to_date));
        }

        //Filter Status
        if($status != '' && $status != 'empty'){
            $vir_consumer->addFieldToFilter('status',array('eq'=>$status));
        }elseif($status == 'empty'){
            // show all
        }else{
            $vir_consumer->addFieldToFilter('parent_id',array('eq'=>0));;
        }

        $this->saveToCSV($vir_consumer);

    }


    /**
     * Prepare to Download CSV
     * @param Object
     */
    public function saveToCSV($collection){


        $file_location = Mage::getBaseDir('var') . DS . 'export';
        $filename = self::CSV_FILE_NAME .'.csv';

        $csv = new Varien_File_Csv();
        $csvdata = array();

        $vir_first_item = $collection->getFirstItem()->getData();
        $csvdata[] = array_keys($vir_first_item);

        $vir_all_items = $collection->getData();
        foreach($vir_all_items as $row){

            $csvdata[] = $row;
        }

        $fullpath = $file_location . DS . $filename;
        $csv->saveData($fullpath, $csvdata);

        $this->_prepareDownloadResponse($filename, array('type' => 'filename', 'value' => $fullpath));


    }
}
