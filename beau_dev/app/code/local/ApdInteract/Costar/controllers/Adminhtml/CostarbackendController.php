<?php

/*
 * This will list all the import history for products, inventory, order and customers
 * This will also allow admin to upload on pending directory for inventory to be process by cron
 * 
 * @author Analyn Javier <ajavier@apdgroup.com>
 * @category Local
 * @package ApdInteract_Costar
 * 
 */

class ApdInteract_Costar_Adminhtml_CostarbackendController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        if ($type = Mage::app()->getRequest()->getParam('type'))
            Mage::getSingleton('core/session')->setApdFilterType($type);

        $this->_title("Costar Data Imports");
        $this->loadLayout();
        $this->_setActiveMenu('apdcostar/costar');
        $this->renderLayout();
    }

    public function ordersAction() {
        $this->_title("Costar Resend Rejected Orders");
        $this->loadLayout();
        $this->_setActiveMenu('apdcostar/costar');
        $this->renderLayout();
    }

    public function newAction() {

        $this->_title("Costar Data Imports");
        $this->loadLayout();
        $this->_setActiveMenu('apdcostar/costar');
        $this->renderLayout();
    }

    public function saveAction() {
        $fileType = '';
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                try {

                    if ($data['type'] != '') {
                        $rootPath = 'var' . DS . 'costar' . DS . 'import' . DS . $data['type'] . DS . 'pending' . DS;
                        $path = Mage::getBaseDir() . DS . $rootPath;
                        Mage::helper('costar/costar')->createDirectory($path);
                        $fname = $_FILES['file']['name']; //file name
                        $fullname = $path . $fname;
                        $uploader = new Varien_File_Uploader('file'); //load class
                        $uploader->setAllowedExtensions(array('CSV', 'csv', 'txt', 'TXT')); //Allowed extension for file
                        $uploader->setAllowCreateFolders(true); //for creating the directory if not exists
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $uploader->save($path, $fname); //save the

                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('costar/costar')->__(" Successfully uploaded to {$rootPath}. The file will be process once cron runs."));
                        $this->_redirect('*/*/');
                    }
                } catch (Exception $e) {
                    $fileType = "Invalid file format";
                }
            }
        }
        if ($fileType == "Invalid file format") {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('costar/costar')->__($fname . " Invalid file format"));
            $this->_redirect('*/*/');
            return;
        }
    }

    public function downloadAction() {
        if ($id = Mage::app()->getRequest()->getParam('id')) {

            $log = Mage::getModel('apdinteract_costar/log')->load($id);

            $file = $log->getFilePath();
            header('Content-disposition: attachment; filename=' . $file);
            header('Content-type: text/csv');
            header('Content-type: application/ms-excel');
            readfile($file);
            exit;
        } else {

            Mage::getSingleton('core/session')->addError(Mage::helper('exportpanel')->__('Unable to find download file'));
            $this->_redirect('*/*/');
        }
    }

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('apdinteract_costar/adminhtml_costar_grid')->toHtml()
        );
    }

    public function resendAction() {

        $order_collection = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('request_type', array('nin' => array('BOOKING', 'PRICE REQUEST')))
            ->addFieldToFilter('status', array('eq' => 'costar_rejected'));
        Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::resendAction START");
        $api_model = Mage::getModel('apdinteract_costar/api');
        $response = $api_model->testSignatureAndEncryption();
        $message = "";
        $comment = "";
        $i = 0;
        $success = 0;
        $failed = 0;
        if ($response['status_code'] == 200):
            foreach ($order_collection as $_order):
                $i++;

                Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::resendAction Order Id:" . $_order->getId());
                $costarFieldsArray = array();
                //Prepare Costar Order Info
                $costarFieldsArray = Mage::helper('costar/api')->prepareCostarOrderInfo($_order);
                // Make a Costar SubmitOrder Api call
                $result = $api_model->submitOrder($costarFieldsArray[0], $costarFieldsArray[1]); 
               
                //Update Order status and History on API response
                if ($result['error']):
                    $comment = Mage::helper('costar/api')->costarRejectedHistory($_order, $result['message'], true);
                    $message .= $comment;
                    $failed++;
                else:
                    Mage::helper('costar/api')->costarAcceptedHistory($_order, $result['message']);
                    $success++;
                endif;
                sleep(2);
            endforeach;   
            $pre = "Resend Process finished. (" . $success . ") Successful, (" . $failed . ") Failure<br><br>";
            $this->_sendReject($pre.$message, 'resendAction');
            $this->_finish('resendAction', $failed, $success);                        
        else:
            $this->_sendTimeout('resendAction');
        endif;

        
    }

    public function massresendAction() {
        $failed_email = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_emails/costar_rejected_email');
        $orderIds = $this->getRequest()->getParam('order_ids');
        $success = 0;
        $failed = 0;
        if (!is_array($orderIds)):
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('costar')->__('Please select order(s).'));
        else:
            try {

                Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::massresendAction START");
                $api_model = Mage::getModel('apdinteract_costar/api');
                $response = $api_model->testSignatureAndEncryption();
                $message = "";

                if ($response['status_code'] == 200):
                    foreach ($orderIds as $_orderid):
                        $_order = Mage::getModel('sales/order')->load($_orderid);
                        Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::massresendAction Order Id:" . $_order->getId());

                        //Prepare Costar Order Info
                        $costarFieldsArray = Mage::helper('costar/api')->prepareCostarOrderInfo($_order);

                        // Make a Costar SubmitOrder Api call
                        $result = $api_model->submitOrder($costarFieldsArray[0], $costarFieldsArray[1]);

                        //Update Order status and History on API response
                        if ($result['error']):
                            $comment = Mage::helper('costar/api')->costarRejectedHistory($_order, $result['message'], true);
                            $message .= $comment;
                            $failed++;
                        else:
                            Mage::helper('costar/api')->costarAcceptedHistory($_order, $result['message']);
                            $success++;
                        endif;

                    endforeach;
                else:
                    $this->_sendTimeout('massresendAction');
                endif;
                  $pre = "Resend Process finished. (" . $success . ") Successful, (" . $failed . ") Failure<br><br>";
                $this->_sendReject($pre.$message, 'massresendAction');
            } catch (Exception $ex) {
                Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
            }
            $this->_finish('massresendAction', $failed, $success);
        endif;
    }

    private function _sendTimeout($action) {
        $timeout_email = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_emails/costar_failed_email');
        if ($timeout_email != ''):
            Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::" . $action . " Api Timeout");
            Mage::helper('costar/api')->sendEmail("API Timeout", "Error: Costar API connection timeout", $timeout_email);
        else:
            Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::" . $action . " No email configured on apdinteract_costar/apdinteract_costar_emails/costar_failed_email");
        endif;
    }

    private function _sendReject($message, $action) {
        $failed_email = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_emails/costar_rejected_email');
        if (trim($message) != '') :
            Mage::helper('costar/api')->sendEmail("Bulk Sending Rejected Orders", $message, $failed_email);
        else:
            Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::" . $action . " No email configured on apdinteract_costar/apdinteract_costar_emails/costar_rejected_email");
        endif;
    }

    private function _finish($action, $failed, $success) {
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('costar/costar')->__("Resend Process finished. (" . $success . ") Successful, (" . $failed . ") Failure"));
        Mage::helper('costar/api')->log("ApdInteract_Costar_Adminhtml_CostarbackendController::".$action." END");
        $this->_redirect('*/adminhtml_costarbackend/orders');
    }

}
