<?php
class Apdinteract_Customer_VehicleController extends Mage_Core_Controller_Front_Action{


    private $_message = null;


    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }


    public function indexAction(){


        if (!$this->_getSession()->isLoggedIn()) {
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vehicle Details'));
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * New Vehicle landing page.
     *
     */
    public function newAction(){

        if (!$this->_getSession()->isLoggedIn()) {
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vehicle Details'));
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * save Vehicle item
     *
     */
    public function saveAction(){

        $session = $this->_getSession();
        $post = $this->getRequest()->getParams();
       try{

           $customerData = $this->_getSession()->getCustomer();

           $registration = Mage::helper('apdinteract_adminhtml')->_isRegistrationUnique($customerData,$post['registration']);
           if (!$registration && $post['registration'] != $post['orig_rego']){
               $session->addError($this->__('Registration is already existing'));
           }else{

               $vehicle_id = isset($post['vehicle_id']) ? $post['vehicle_id'] : '';
               if($vehicle_id != ""){
                   $data = Mage::getModel('apdinteract_vehicle/vehicle')->load($vehicle_id);
               }else{
                   $data = Mage::getModel('apdinteract_vehicle/vehicle');
               }

               $vehicle = json_decode($post['details-tyres'],true);
               unset($post['details-tyres']);
               $details = json_encode($post);

               $data->setCustomerId($customerData->getId());
               $data->setWebsiteId($customerData->getWebsiteId());
               $data->setMake($vehicle['make-tyres']);
               $data->setManufactureYear($vehicle['year-tyres']);
               $data->setModel($vehicle['model-tyres']);
               $data->setSeries($vehicle['series-tyres']);
               $data->setRegistration($post['registration']);
               $data->setDetails($details);

               $data->save();
               $session->addSuccess($this->__('The vehicle information has been saved.'));

           }

       }
       catch (Exception $e){

           switch ($e->getCode()){
               case 23000:
                   $this->_message = "Duplicate entry for registration not allowed.";
                   break;
               default:
                   $this->_message = "Error on saving vehicle information";
                   break;
           }
           $session->addError($this->__($this->_message));
       }

        $this->_redirect('account/vehicles');

    }

    /**
     * Edit Vehicle item
     *
     */
    public function editAction(){


        if (!$this->_getSession()->isLoggedIn()) {
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Vehicle Details'));
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();

    }

    /**
     * Delete Vehicle item
     *
     */
    public function deleteAction(){

        $vehicle_id = $this->getRequest()->getParam('vehicle_id');
        $customerId = $this->_getSession()->getCustomer()->getId();

        try{

            $collection = Mage::getModel('apdinteract_vehicle/vehicle')->getCollection()
                ->addFieldToFilter('vehicle_id', $vehicle_id)
                ->addFieldToFilter('customer_id', $customerId);
            $collection->walk('delete');

            Mage::getSingleton('customer/session')->addSuccess('Vehicle was successfully deleted');

        }catch (Exception $e){
            Mage::getSingleton('customer/session')->addError('Cannot delete vehicle');
        }

        $this->_redirect('account/vehicles');

    }
}