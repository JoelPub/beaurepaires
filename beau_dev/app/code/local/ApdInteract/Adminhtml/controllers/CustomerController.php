<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Customer admin controller
 *
 * @category    Mage
 * @package     Apdinteract_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */

require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'CustomerController.php';

class ApdInteract_Adminhtml_CustomerController extends Mage_Adminhtml_CustomerController
{
    /*
     * Save Vehicle Action
     */

    public function saveVehicleAction(){
        $this->_initCustomer();
        $customer = Mage::registry('current_customer');
        $this->_addOrUpdate($customer);
        $this->_redirectReferer();
    }

    /*
     * Save all vehicle Information
     * @param Customer object
     */

    private function _addOrUpdate($customerData){
        $post = $this->getRequest()->getPost();
        $vehicleId = $this->getRequest()->getParam('update_vehicle');

        $vehicle = Mage::getModel('apdinteract_vehicle/vehicle');

        if (($customerData->getId()) && (!$vehicleId) && ($post)){
            // add new vehicle

            $registration = $this->_helper()->_isRegistrationUnique($customerData,$post['registration']);
            if (!$registration){
                $this->_showDuplicateErrorMsg();
            }else{
                $success = $this->_saveDetails($vehicle,$customerData,$post);
                if ($success){
                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('adminhtml')->__('The vehicle has been added.'));
                }
            }

        }elseif(($customerData->getId()) && ($vehicleId) && ($post)){
            // update vehicle

            $vehicle->load($vehicleId);
            $registration = $this->_helper()->_isRegistrationUnique($customerData,$post['registration']);
            if (!$registration && $post['registration'] != $post['orig_rego']){
                $this->_showDuplicateErrorMsg();
            }else{
                $success = $this->_saveDetails($vehicle,$customerData,$post);
                if ($success){
                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('adminhtml')->__('The vehicle has been updated.'));
                }
            }

        }else{
            Mage::getSingleton('core/session')->addError(Mage::helper('adminhtml')->__('Something went wrong. Vehicle was not added/updated.'));
        }

    }

    private function _helper(){
        return Mage::helper('apdinteract_adminhtml');
    }

    private function _showDuplicateErrorMsg(){
        return Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Registration is already existing.'));
    }



    /*
     * Save Details whether it is for add or edit vehicle action
     * @param $vehicle object
     * @param $customer object
     * @param $data post values
     */

    private function _saveDetails($vehicle,$customer,$data){
        try {
            // Changed on how to save data rather than changing the JS file (vehicle.js)
            // Instead of saving the Ids for each selected dropdown, saved the actual value like BMW, Audi.
            // Initially, $data['details-tyres'] holds the IDs for each dropdown in json format but have to decode it
            // to properly save the Actual value instead of IDs for field Make, Model and Series then Encode the posted
            // values for make, model and series to save the ids in json format in Detail field.

            $decode = Mage::helper('core')->jsonDecode($data['details-tyres']);

            $tyres = array('make-tyres' => $data['make-tyres'],
							'year-tyres' => $data['year-tyres'],
							'model-tyres' => $data['model-tyres'],
							'series-tyres'=> $data['series-tyres']
						);

            $encode = Mage::helper('core')->jsonEncode($tyres);

            $vehicle->setCustomerId($customer->getId());
            $vehicle->setWebsiteId($customer->getWebsiteId());
            $vehicle->setMake($decode['make-tyres']);
            $vehicle->setManufactureYear($data['year-tyres']);
            $vehicle->setModel($decode['model-tyres']);
            $vehicle->setSeries($decode['series-tyres']);
            $vehicle->setRegistration($data['registration']);
            $vehicle->setUrl($data['url']);
            $vehicle->setDetails($encode);

            $vehicle->save();
            return true;
        }
        catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Error occurred while Saving vehicle details.'));
            return false;
        }
    }

    /*
     * Filter the Grid results
     */

    public function vehicleAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('apdinteract_adminhtml/customer_edit_tab_vehicle_grid')->toHtml()
        );
    }

    /**
     * Customer edit action
     */
    public function editAction()
    {
        $this->_initCustomer();
        $this->loadLayout();

        /* Need to check if there are any existing parameters before deleting a vehicle */
        $vehicle = $this->getRequest()->getParam('delete_vehicle');
        if ($vehicle){
            $this->_deleteVehicle($vehicle);
        }

        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::registry('current_customer');

        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getCustomerData(true);

        // restore data from SESSION
        if ($data) {
            $request = clone $this->getRequest();
            $request->setParams($data);

            if (isset($data['account'])) {
                /* @var $customerForm Mage_Customer_Model_Form */
                $customerForm = Mage::getModel('customer/form');
                $customerForm->setEntity($customer)
                    ->setFormCode('adminhtml_customer')
                    ->setIsAjaxRequest(true);
                $formData = $customerForm->extractData($request, 'account');
                $customerForm->restoreData($formData);
            }

            if (isset($data['address']) && is_array($data['address'])) {
                /* @var $addressForm Mage_Customer_Model_Form */
                $addressForm = Mage::getModel('customer/form');
                $addressForm->setFormCode('adminhtml_customer_address');

                foreach (array_keys($data['address']) as $addressId) {
                    if ($addressId == '_template_') {
                        continue;
                    }

                    $address = $customer->getAddressItemById($addressId);
                    if (!$address) {
                        $address = Mage::getModel('customer/address');
                        $customer->addAddress($address);
                    }

                    $formData = $addressForm->setEntity($address)
                        ->extractData($request);
                    $addressForm->restoreData($formData);
                }
            }
        }

        $this->_title($customer->getId() ? $customer->getName() : $this->__('New Customer'));

        /**
         * Set active menu item
         */
        $this->_setActiveMenu('customer/new');

        $this->renderLayout();
    }

    /**
     * Delete the vehicle based from the parameter found in the URL
     * @param $id int
     */
    private function _deleteVehicle($id){
        try {
            $vehicle = Mage::getModel('apdinteract_vehicle/vehicle');
            $vehicle->load($id);
            $vehicle->delete();
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('adminhtml')->__('The vehicle has been deleted.'));
        }
        catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirectReferer();
    }

    /**
     * Save customer action
     * Rewrite this controller to include Fsite newsletter - See BFT-1803
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data) {
            $redirectBack = $this->getRequest()->getParam('back', false);
            $this->_initCustomer('customer_id');

            /** @var $customer Mage_Customer_Model_Customer */
            $customer = Mage::registry('current_customer');

            /** @var $customerForm Mage_Customer_Model_Form */
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setEntity($customer)
                ->setFormCode('adminhtml_customer')
                ->ignoreInvisible(false)
            ;

            $formData = $customerForm->extractData($this->getRequest(), 'account');

            // Handle 'disable auto_group_change' attribute
            if (isset($formData['disable_auto_group_change'])) {
                $formData['disable_auto_group_change'] = empty($formData['disable_auto_group_change']) ? '0' : '1';
            }

            $errors = null;
            if ($customer->getId()&& !empty($data['account']['new_password'])
                && Mage::helper('customer')->getIsRequireAdminUserToChangeUserPassword()
            ) {
                //Validate current admin password
                if (isset($data['account']['current_password'])) {
                    $currentPassword = $data['account']['current_password'];
                } else {
                    $currentPassword = null;
                }
                unset($data['account']['current_password']);
                $errors = $this->_validateCurrentPassword($currentPassword);
            }

            if (!is_array($errors)) {
                $errors = $customerForm->validateData($formData);
            }

            if ($errors !== true) {
                foreach ($errors as $error) {
                    $this->_getSession()->addError($error);
                }
                $this->_getSession()->setCustomerData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/customer/edit', array('id' => $customer->getId())));
                return;
            }

            $customerForm->compactData($formData);

            // Unset template data
            if (isset($data['address']['_template_'])) {
                unset($data['address']['_template_']);
            }

            $modifiedAddresses = array();
            if (!empty($data['address'])) {
                /** @var $addressForm Mage_Customer_Model_Form */
                $addressForm = Mage::getModel('customer/form');
                $addressForm->setFormCode('adminhtml_customer_address')->ignoreInvisible(false);

                foreach (array_keys($data['address']) as $index) {
                    $address = $customer->getAddressItemById($index);
                    if (!$address) {
                        $address = Mage::getModel('customer/address');
                    }

                    $requestScope = sprintf('address/%s', $index);
                    $formData = $addressForm->setEntity($address)
                        ->extractData($this->getRequest(), $requestScope);

                    // Set default billing and shipping flags to address
                    $isDefaultBilling = isset($data['account']['default_billing'])
                        && $data['account']['default_billing'] == $index;
                    $address->setIsDefaultBilling($isDefaultBilling);
                    $isDefaultShipping = isset($data['account']['default_shipping'])
                        && $data['account']['default_shipping'] == $index;
                    $address->setIsDefaultShipping($isDefaultShipping);

                    $errors = $addressForm->validateData($formData);
                    if ($errors !== true) {
                        foreach ($errors as $error) {
                            $this->_getSession()->addError($error);
                        }
                        $this->_getSession()->setCustomerData($data);
                        $this->getResponse()->setRedirect($this->getUrl('*/customer/edit', array(
                                'id' => $customer->getId())
                        ));
                        return;
                    }

                    $addressForm->compactData($formData);

                    // Set post_index for detect default billing and shipping addresses
                    $address->setPostIndex($index);

                    if ($address->getId()) {
                        $modifiedAddresses[] = $address->getId();
                    } else {
                        $customer->addAddress($address);
                    }
                }
            }

            // Default billing and shipping
            if (isset($data['account']['default_billing'])) {
                $customer->setData('default_billing', $data['account']['default_billing']);
            }
            if (isset($data['account']['default_shipping'])) {
                $customer->setData('default_shipping', $data['account']['default_shipping']);
            }
            if (isset($data['account']['confirmation'])) {
                $customer->setData('confirmation', $data['account']['confirmation']);
            }

            // Mark not modified customer addresses for delete
            foreach ($customer->getAddressesCollection() as $customerAddress) {
                if ($customerAddress->getId() && !in_array($customerAddress->getId(), $modifiedAddresses)) {
                    $customerAddress->setData('_deleted', true);
                }
            }

            // Added this logic for BFT-1803 since we hide the default "Subscribe to Newsletter" of Magento
            // If any of the Fsite newsletter is ticked, we need to set the subscription of the customer to true
            $data['subscription'] = false;
            if (isset($data['general_subscription']) || isset($data['_general_newsletter']) || isset($data['_product_news']) || isset($data['_special_offers_and_rewards'])){
                $data['subscription'] = true;
            }

            if (Mage::getSingleton('admin/session')->isAllowed('customer/newsletter')
                && !$customer->getConfirmation()
            ) {
                $customer->setIsSubscribed($data['subscription']);
            }

            if (isset($data['account']['sendemail_store_id'])) {
                $customer->setSendemailStoreId($data['account']['sendemail_store_id']);
            }

            $isNewCustomer = $customer->isObjectNew();
            try {
                $sendPassToEmail = false;
                // Force new customer confirmation
                if ($isNewCustomer) {
                    $customer->setPassword($data['account']['password']);
                    $customer->setForceConfirmed(true);
                    if ($customer->getPassword() == 'auto') {
                        $sendPassToEmail = true;
                        $customer->setPassword($customer->generatePassword());
                    }
                }

                Mage::dispatchEvent('adminhtml_customer_prepare_save', array(
                    'customer'  => $customer,
                    'request'   => $this->getRequest()
                ));

                $customer->save();

                // Send welcome email
                if ($customer->getWebsiteId() && (isset($data['account']['sendemail']) || $sendPassToEmail)) {
                    $storeId = $customer->getSendemailStoreId();
                    if ($isNewCustomer) {
                        $customer->sendNewAccountEmail('registered', '', $storeId);
                    } elseif ((!$customer->getConfirmation())) {
                        // Confirm not confirmed customer
                        $customer->sendNewAccountEmail('confirmed', '', $storeId);
                    }
                }

                if (!empty($data['account']['new_password'])) {
                    $newPassword = $data['account']['new_password'];
                    if ($newPassword == 'auto') {
                        $newPassword = $customer->generatePassword();
                    }
                    $customer->changePassword($newPassword);
                    $customer->sendPasswordReminderEmail();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('The customer has been saved.')
                );
                Mage::dispatchEvent('adminhtml_customer_save_after', array(
                    'customer'  => $customer,
                    'request'   => $this->getRequest()
                ));

                if ($redirectBack) {
                    $this->_redirect('*/*/edit', array(
                        'id' => $customer->getId(),
                        '_current' => true
                    ));
                    return;
                }
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $this->_getSession()->setCustomerData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/customer/edit', array('id' => $customer->getId())));
                return;
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('adminhtml')->__('An error occurred while saving the customer.'));
                $this->_getSession()->setCustomerData($data);
                $this->getResponse()->setRedirect($this->getUrl('*/customer/edit', array('id'=>$customer->getId())));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/customer'));
    }


}
