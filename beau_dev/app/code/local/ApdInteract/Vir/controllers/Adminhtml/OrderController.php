<?php

class ApdInteract_Vir_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action {

    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page, we will see a grid of all
     * orders currently available in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction() {
        // instantiate the grid container
        $orderBlock = $this->getLayout()
                ->createBlock('apdinteract_vir_adminhtml/order');

        // Add the grid container as the only item on this page
        $this->loadLayout()
                ->_addContent($orderBlock)
                ->renderLayout();
    }

    public function autocompleteAction() {
        $this->loadLayout(false);
        $this->renderLayout();
        // Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }

    public function ajaxsaveAction() {
        $params = $this->getRequest()->getParams();
//        echo "<pre>";
//        print_r($params);

        $models['order'] = true;
        $models['ordercommercial'] = true;

        // Prevent parameter injection
        if (!isset($models[$params['dataname']])) {
            return;
        }

        $order = Mage::getModel('apdinteract_vir/' . $params['dataname']);

        // Quick way. Not as secure. Don't do it.
//        $order->quickSave($params['id'], $params['field'], $params['value']);
//        return;

        $order->load($params['id']);
        if (!$order->getId()) {
            Mage::log("ajaxsaveAction: Can't find id: {$params['id']}", null, 'vir_ordercontroller.log');
//            echo "can't find id: {$params['id']}";
            return;
        }


//        echo "\r\n\r\n\r\nObject: ";
//        print_r($order->getData());
//        echo "\r\n\r\n\r\nBefore Save: ";
//        print_r($order->getData($params['field']));

        try {
            $order->setData($params['field'], $params['value']);


            //$order->addData($postData);
            $order->save();



//            // $this->_getSession()->addSuccess($this->__('The order has been saved.'));
//            // redirect to remove $_POST data from the request
//          echo "\r\nSuccess";
//            //return $this->_redirect('apdinteract_vir_admin/order/edit',array('id' => $order->getId()));
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function printAction() {
        $this->loadLayout(false);

        // This prevents magento headers overwriting the PDF one
        $this->getResponse()->clearHeaders();

        $this->renderLayout();
//        Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }

    public function updatefieldAction($id, $fieldname, $fieldvalue) {
        // Mage::log("Hello this is test updatefieldAction message");
        //return "Hello";
        // instantiate the grid container
        //$orderBlock = $this->getLayout()
        //    ->createBlock('apdinteract_vir_adminhtml/order');
        // Add the grid container as the only item on this page
        //$this->loadLayout()
        //    ->_addContent($orderBlock)
        //    ->renderLayout();

        $order = Mage::getModel('apdinteract_vir/order');
        $order->load($orderId);
        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This order no longer exists.'));
            Mage::log("updatefieldAction message: This order no longer exists");
            return $this->_redirect('apdinteract_vir_admin/order/index');
        }


        try {
            $order->setData($fieldname, $fieldvalue);
            //$order->addData($postData);
            $order->save();
            $this->_getSession()->addSuccess($this->__('The order has been saved.'));

            // redirect to remove $_POST data from the request
            return "Sucess";
            //return $this->_redirect('apdinteract_vir_admin/order/edit',array('id' => $order->getId()));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        // Make the current order object available to blocks.
        Mage::register('current_order', $order);

        return "Success";
    }

    private function _validateFields($postData) {
        if (empty($postData['custname']))
            $errors[] = 'name';
        if (empty($postData['custphoneno']) && empty($postData['custphonemobile']))
            $errors[] = 'phone number';
        if (empty($postData['custemail']))
            $errors[] = 'email';
        if (empty($postData['vehiclerego']))
            $errors[] = 'registration';
        if (empty($postData['vehicleodometer']))
            $errors[] = 'vehicleodometer';

        if (isset($errors)) {
            $error_msg = 'Please complete the following required fields: ' . implode(', ', $errors);
            return $error_msg;
        }
    }

    /**
     * Update drawing
     * @return JSON
     */
    public function savedrawingAction() {

        $params = $this->getRequest()->getPost();

        $form_id = $params['id'];
        $form_data = $params['data'];
        $msg = "";

        if (isset($form_id)) {

            try {
                $order = Mage::getModel('apdinteract_vir/order')->load($form_id);
                $order->setSketchContainer($form_data);
                $order->save();

                $msg = array('return' => 'success');
            } catch (Exception $e) {
                Mage::logException($e);

                $msg = array('return' => 'error');
            }
        } else {
            $msg = array('return' => 'error');
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($msg));
    }

    /**
     * This action handles both viewing and editing existing orders.
     */
    public function editAction() {
        if (Mage::helper('addblock')->checkSF()):
            Mage::Helper('apdinteract_salesforce')->checkconnection();
        endif;
        /**
         * Retrieve existing order data if an ID was specified.
         * If not, we will have an empty order entity ready to be populated.
         */
        $order = Mage::getModel('apdinteract_vir/order');
        $consumer = $this->getRequest()->getParam('consumer');
        $params = $this->getRequest()->getPost();
        
        if(isset($params['hcData']) && array($params['hcData'])) {
            try {
                $hc = Mage::getModel('apdinteract_vir/healthcheck')->load($params['healthcheckid']);
         
                $tyreDetails = (array)json_decode($params['details-tyres']);
                Mage::log($tyreDetails, null, 'abc.log');

                $params['hcData']['vehicle_make'] = $tyreDetails['make-tyres'];
                $params['hcData']['vehicle_model'] = $tyreDetails['model-tyres'];

                $hc->addData($params['hcData']);
                $hc->save();
                $healthcheckId = $hc->getId();
				
				
            } catch(Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }
		
        if ($orderId = $this->getRequest()->getParam('id', false)) {
            $order->load($orderId);
            if (!$order->getId()) {
                $this->_getSession()->addError($this->__('This order no longer exists.'));
                return $this->_redirect('apdinteract_vir_admin/order/index');
            }
        } else {
            // Create a new one. Save to model straight away so the ajax updater can work
            $order->setData('orderdate', date('Y-m-d'))->save(); //  eg '2000-01-01'
            $orderId = $order->getId();

            $this->_sendToSf($orderId, $params['hcData'], $params['healthcheckid']);

            return $this->_redirect('apdinteract_vir_admin/order/edit/id/' . $orderId . '/');
        }

        // process $_POST data if the form was submitted
        
        $postData = array();
        if(isset($params['orderData']))
            $postData = $params['orderData'];

        $v_data_collection = $this->_checkVehicleData($params);
        if ($v_data_collection['is_valid']) {
            $postData = array_merge($params['orderData'], $v_data_collection['vehicle_data']);
        }

        if ($postData) {

            $bookingDate = explode("/",$postData['booking_date']);
            $newFormat = array($bookingDate[2],$bookingDate[1],$bookingDate[0]);
            $postData['booking_date'] = implode("-",$newFormat);
            //Mage::Helper('sync')->update_booking_status($appointmentId, $status);  //update vir status at booking calendar

            try {
                $order->addData($postData);
                $order->save();

                $status = $params['status'];
                $appointmentId = $params['appointmentid'];
                $customerid = $params['customerid'];

                if ($customerid > 0) {
                    $sales_order = Mage::getModel('sales/order')->loadByIncrementId($params['ordernumber']);
                    $billing_address_id = $sales_order->getBillingAddress()->getCustomerAddressId();
                    $address_data = array('telephone' => $postData['custphoneno'],
                        'mobile' => $postData['custphonemobile'],
                        'street' => $postData['custaddress'],
                        'suburb' => $postData['custsuburb'],
                        'postcode' => $postData['custpostcode'],
                    );
                    Mage::Helper('apdinteract_vir')->updateCustomerBillingAddress($address_data, $billing_address_id);

                    if ($v_data_collection['is_valid']) {
                        Mage::Helper('apdinteract_vir')->saveConsVirVehicleData(array_merge($postData, $v_data_collection['vehicle_ids']), $customerid); //update or add vehicle details
                    }
                }

                if (($error_message = $this->_validateFields($postData)) && $customerid > 0) {
                    $this->_getSession()->addError($this->__($error_message));
                } else {

                    $this->_getSession()->addSuccess($this->__('The vehicle inspection report has been saved.'));
                }
                // redirect to remove $_POST data from the request
                Mage::Helper('apdinteract_vir')->virMapper($order->getId(),$params['storelocation_id'],0);
                $responseId = Mage::Helper('sync')->sendBooking($params); //update vir status at booking calendar
                if(!empty($responseId)){
                    $order->setData('appointmentid',$responseId);
                    $order->save();
                }

                $this->_sendToSf($orderId, $params['hcData'],$params['healthcheckid']);

                return $this->_redirect('apdinteract_vir_admin/order/edit', array('id' => $order->getId()));
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

        // Make the current order object available to blocks.
        Mage::register('current_order', $order);

        // Instantiate the form container.
        $orderEditBlock = $this->getLayout()->createBlock('apdinteract_vir_adminhtml/order_edit');

        //$block = $this->getLayout()
        //->createBlock('core/text', 'parent-id')
        //->setText('<link rel="stylesheet" type="text/css" href="/skin/adminhtml/default/default/css/apdinteract/vir.css" media="screen, projection">');
        // Add the form container as the only item on this page.
        $this->loadLayout()
            //->_addContent($block)
            ->_addContent($orderEditBlock)
            ->renderLayout();

        //$this->setTemplate('apdinteract/vir/order/edit/order.phtml');
        //$this->_addContent($block);
    }

    /**
     * @param $params
     * @return array
     */
    private function _checkVehicleData($params) {

        if (isset($params['details-tyres'])) {
            $vehicleDetailsValue = (array)json_decode($params['details-tyres'], true);
            $vehicleDetailsId = array("make-tyres" => $params['make-tyres'], "year-tyres" => $params['year-tyres'], "model-tyres" => $params['model-tyres'], "series-tyres" => $params['series-tyres'],);

            //check if array is has empty value
            $is_empty_id = $this->_isArrayEmpty($vehicleDetailsId);

            //check if array is has empty value
            $is_empty_value = $this->_isArrayEmpty($vehicleDetailsValue);
            Mage::log($vehicleDetailsValue, null, 'abc.log');

            if (!$is_empty_value && !$is_empty_id) {
                $vehicle_data = array(
                    "vehiclemake" => $vehicleDetailsValue['make-tyres'],
                    "vehicleyear" => $vehicleDetailsValue['year-tyres'],
                    "vehiclemodel" => $vehicleDetailsValue['model-tyres'],
                    "vehicleseries" => $vehicleDetailsValue['series-tyres'],
                    "vehicledetails" => json_encode($vehicleDetailsId),
                );

                return array('is_valid' => TRUE, 'vehicle_data' => $vehicle_data, 'vehicle_ids' => $vehicleDetailsId);
            }

            return array('is_valid' => FALSE, 'vehicle_data' => array(), 'vehicle_ids' => array());
        }
    }

    /**
     * @param $array
     * @return bool
     */
    private function _isArrayEmpty($array) {

        $is_empty = false;
        foreach ($array as $key => $value) {
            /* in_array condition for BFT-2181 */
            if ($value == '' && !in_array($key, array('model-tyres', 'series-tyres', 'year-tyres'))) {
                $is_empty = true;
                break;
            }
        }
        return $is_empty;
    }

    public function deleteAction() {


        if (Mage::helper('addblock')->checkSF()):
            Mage::Helper('apdinteract_salesforce')->checkconnection();
        endif;

        $order = Mage::getModel('apdinteract_vir/order');



        if ($orderId = $this->getRequest()->getParam('id', false)) {
            $order->load($orderId);
        }
        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This vehicle inspection report  no longer exists.'));
            return $this->_redirect('apdinteract_vir_admin/order/index');
        }

        try {
            Mage::Helper('sync')->update_booking_status($order->getAppointmentid(), 'Cancelled'); //update vir status at booking calendar

            if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled                                    
                Mage::helper('apdinteract_vir')->deleteToSfConsumer($order->getId()); // send to salesforce                        
            endif;

            $order->delete();
            $this->_getSession()->addSuccess($this->__('The vehicle inspection report has been deleted.'));
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }



        return $this->_redirect(
                        'apdinteract_vir_admin/order/index'
        );
    }

    /**
     * Thanks to Ben for pointing out this method was missing. Without
     * this method the ACL rules configured in adminhtml.xml are ignored.
     */
    protected function _isAllowed() {
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
         * - - - - - - - order
         *
         * eg. you could add more rules inside order for edit and delete.
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
                        ->isAllowed('apdinteract_vir/order');
                break;
        }

        return $isAllowed;
    }

    private function _sendToSf($orderId, $healthCheckData, $healthcheckid) {

        if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled
            if (Mage::Helper('apdinteract_salesforce/mapper_virconsumer')->getVirConsumerSalesforceId($orderId) != '')
                $action = "update";
            else
                $action = "add";

            Mage::Helper('apdinteract_vir')->sendToSFConsumer($orderId, $action);
            Mage::Helper('apdinteract_vir')->sendToSFHealthCheck($healthCheckData, $action, $healthcheckid);
        endif;
    }

}
