<?php
class ApdInteract_Vir_Adminhtml_OrdercommercialController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page, we will see a grid of all
     * ordercommercials currently available in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction()
    {
        // instantiate the grid container
        $ordercommercialBlock = $this->getLayout()
            ->createBlock('apdinteract_vir_adminhtml/ordercommercial');
        
        $block = $this->getLayout()
        ->createBlock('core/text', 'parent-id')
        ->setText('<link rel="stylesheet" type="text/css" href="/skin/adminhtml/default/default/css/apdinteract/vir.css" media="screen, projection">');
                
        // Add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($block)
            ->_addContent($ordercommercialBlock)
            ->renderLayout();
    }

    private function _validateFields($postData) {                
        if (empty($postData['customername'])) $errors[] = 'name';
        if (empty($postData['phonenumber']) && empty($postData['phonemobile'])) $errors[] = 'phone number';
//        if (empty($postData['custemail'])) $errors[] = 'email'; // email not collected on commercial VIR
//        if (empty($postData['vehiclemake'])) $errors[] = 'make';
        if (empty($postData['fleetnumber'])) $errors[] = 'fleet number';
        if (empty($postData['regonumber'])) $errors[] = 'registration number';
        if (empty($postData['speedohubreading'])) $errors[] = 'speedo/hub';
        
        if (isset($errors)) {
            $error_msg = 'Please complete the following required fields: ' . implode(', ', $errors);
            return $error_msg;
        }
    }

    /**
     * Update drawing
     * @return JSON
     */
    public function savedrawingAction(){

        $params = $this->getRequest()->getPost();

        $form_id = $params['id'];
        $form_data = $params['data'];
        $msg = "";

        if(isset($form_id)){

            try{
                $order = Mage::getModel('apdinteract_vir/ordercommercial')->load($form_id);
                $order->setSketchContainer($form_data);
                $order->save();

                $msg = array('return' => 'success');

            }catch (Exception $e){
                Mage::logException($e);

                $msg = array('return' => 'error');
            }
        }else{
            $msg = array('return' => 'error');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($msg));
    }

    /**
     *
    * This action handles both viewing and editing existing ordercommercials.
    */
    public function editAction()
    {

        if (Mage::helper('addblock')->checkSF()):
            Mage::Helper('apdinteract_salesforce')->checkconnection();
        endif;
        /**
         * Retrieve existing ordercommercial data if an ID was specified.
         * If not, we will have an empty ordercommercial entity ready to be populated.
         */
        $ordercommercial = Mage::getModel('apdinteract_vir/ordercommercial');
        if ($ordercommercialId = $this->getRequest()->getParam('id', false))
        {
            $ordercommercial->load($ordercommercialId);
            if (!$ordercommercial->getId()) {
                $this->_getSession()->addError( $this->__('This commercial vehicle inspection report no longer exists.') );
                return $this->_redirect('apdinteract_vir_admin/ordercommercial/index');
            }
        }
        else {
            // Create a new one. Save to model straight away so the ajax updater can work
            $ordercommercial->setData('inspectiondate', date('Y-m-d'))->save(); //  eg '2000-01-01'
            $ordercommercialId = $ordercommercial->getId();
            $this->_sendToSf($ordercommercialId);
            return $this->_redirect('apdinteract_vir_admin/ordercommercial/edit/id/' . $ordercommercialId . '/');
        }

        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('orderData')) {
            try {
                $bookingDate = explode("/",$postData['booking_date']);
                $newFormat = array($bookingDate[2],$bookingDate[1],$bookingDate[0]);
                $postData['booking_date'] = implode("-",$newFormat);

                $ordercommercial->addData($postData);
                $ordercommercial->save();
                $params = $this->getRequest()->getParams();
                $status = $params['status'];
                $appointmentId = $params['appointmentid'];
                $customerid = $params['customerid'];

                if($customerid > 0) {
                    $sales_order = Mage::getModel('sales/order')->loadByIncrementId($params['ordernumber']);
                    $billing_address_id = $sales_order->getBillingAddress()->getCustomerAddressId();
                    $address_data = array('telephone' => $postData['phonenumber'],
                        'mobile' => $postData['phonemobile'],
                        'street' => $postData['addressline1'],
                        'suburb' => $postData['suburb'],
                        'postcode' => $postData['postcode'],
                    );
                    Mage::Helper('apdinteract_vir')->updateCustomerBillingAddress($address_data, $billing_address_id);
                    Mage::Helper('apdinteract_vir')->saveCommVirVehicleData($postData, $customerid); //update or add vehicle details
                }

                Mage::Helper('apdinteract_vir')->virMapper($ordercommercial->getId(),$params['storelocation_id'],1);
                $mapFields = $this->_mapFields($params);
                $responseId = Mage::Helper('sync')->sendBooking($mapFields); //update vir status at booking calendar
                if(!empty($responseId)){
                    $ordercommercial->setData('appointmentid',$responseId);
                    $ordercommercial->save();
                }

                if ($error_message = $this->_validateFields($postData)) {
                    $this->_getSession()->addError($this->__($error_message));
                }
                else {
                    $this->_getSession()->addSuccess($this->__('The commercial vehicle inspection report has been saved.'));
                }

                $this->_sendToSf($ordercommercial->getId());
                // redirect to remove $_POST data from the request
                return $this->_redirect('apdinteract_vir_admin/ordercommercial/edit',array('id' => $ordercommercial->getId()));
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }

            /**
             * If we get to here, then something went wrong. Continue to
             * render the page as before, the difference this time being
             * that the submitted $_POST data is available.
             */
        }

        // Make the current ordercommercial object available to blocks.
        Mage::register('current_ordercommercial', $ordercommercial);

        // Instantiate the form container.
        $ordercommercialEditBlock = $this->getLayout()->createBlock('apdinteract_vir_adminhtml/ordercommercial_edit');

        // Add the form container as the only item on this page.
        $this->loadLayout()
            ->_addContent($ordercommercialEditBlock)
            ->renderLayout();
    }

    /**
     * Mapped fields to match in booking sync
     * @param array $params
     * @return array
     */
    protected function _mapFields($params = array()){

        $params['appointmentid'] = $params['orderData']['appointmentid'];
        $params['orderData']['custname'] = $params['orderData']['customername'];
        $params['orderData']['custphoneno'] = $params['orderData']['phonenumber'];
        $params['orderData']['custphonemobile'] = $params['orderData']['phonemobile'];
        $params['orderData']['custcompany'] = "";
        $params['orderData']['custaddress'] = $params['orderData']['addressline1'];
        $params['orderData']['custaddress2'] = $params['orderData']['addressline2'];
        $params['orderData']['custpostcode'] = $params['orderData']['postcode'];

        return $params;
    }

    public function autocompleteAction() {
        $this->loadLayout(false);
        $this->renderLayout();
        // Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }
    
    public function printAction() {
        $this->loadLayout(false);
        
        // This prevents magento headers overwriting the PDF one
        $this->getResponse()->clearHeaders();
        
        $this->renderLayout();
//        Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }
    
    public function deleteAction()
    {
        if (Mage::helper('addblock')->checkSF()):
            Mage::Helper('apdinteract_salesforce')->checkconnection();
        endif;
        $ordercommercial = Mage::getModel('apdinteract_vir/ordercommercial');
        if ($ordercommercialId = $this->getRequest()->getParam('id', false)) {
            $ordercommercial->load($ordercommercialId);
        }
        if (!$ordercommercial->getId()) {
            $this->_getSession()->addError($this->__('This commercial vehicle inspection report no longer exists.'));
            return $this->_redirect('apdinteract_vir_admin/ordercommercial/index');
        }

        try {
            Mage::Helper('sync')->update_booking_status($ordercommercial->getAppointmentid(), 'Cancelled'); //update vir status at booking calendar
            
            if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled                                    
                Mage::helper('apdinteract_vir')->deleteToSfCommercial($ordercommercial->getId()); // send to salesforce                        
            endif;
            
            $ordercommercial->delete();
            $this->_getSession()->addSuccess($this->__('The commercial vehicle inspection report has been deleted.'));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect(
            'apdinteract_vir_admin/ordercommercial/index'
        );
    }

    /**
     * Thanks to Ben for pointing out this method was missing. Without
     * this method the ACL rules configured in adminhtml.xml are ignored.
     */
    protected function _isAllowed()
    {
        /**
         * we include this switch to demonstrate that you can add action
         * level restrictions in your ACL rules. The isAllowed() method will
         * use the ACL rule we have configured in our adminhtml.xml file:
         * - acl
         * - - resources
         * - - - admin
         * - - - - children
         * - - - - - apdinteract_vir
         * - - - - - - children
         * - - - - - - - ordercommercial
         *
         * eg. you could add more rules inside ordercommercial for edit and delete.
         */
        $actionName = $this->getRequest()->getActionName();
        switch ($actionName) {
            case 'index':
            case 'edit':
            case 'delete':
                // intentionally no break
            default:
                $adminSession = Mage::getSingleton('admin/session');
                $isAllowed = $adminSession
                    ->isAllowed('apdinteract_vir/ordercommercial');
                break;
        }

        return $isAllowed;
    }
    
    private function _sendToSf($orderId) {

        if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled
            if (Mage::Helper('apdinteract_salesforce/mapper_vircommercial')->getVirCommercialSalesforceId($orderId) != '')
                $action = "update";
            else
                $action = "add";

            Mage::Helper('apdinteract_vir')->sendToSFCommercial($orderId, $action);
        endif;
    }
}
