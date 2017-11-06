<?php

/**
 * ApdInteract Customer account controller
 *
 * Rewrite createPostAction to Unset session variables - See BFT-1917
 */
require 'Mage/Customer/controllers/AccountController.php';
class ApdInteract_Customer_AccountController extends Mage_Customer_AccountController
{
    /**
     * Create customer account action
     */
    public function createPostAction()
    {

        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));

        if (!$this->_validateFormKey()) {
            $this->_redirectError($errUrl);
            return;
        }

        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError($errUrl);
            return;
        }

        $customer = $this->_getCustomer();

        $session = Mage::getSingleton('core/session');
        $session->unsGuestFirstName();
        $session->unsGuestLastName();
        $session->unsGuestEmail();


        try {
            $errors = $this->_getCustomerErrors($customer);

            if (empty($errors)) {

                $this->checkDormantAccount($customer);

                $customer->cleanPasswordsValidationData();
                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistration($customer);
                return;
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('customer/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
            } else {
                $message = $this->_escapeHtml($e->getMessage());
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            $session->addException($e, $this->__('Cannot save the customer.'));
        }

        $this->_redirectError($errUrl);


    }


    /**
     * Check if customer is Dormant
     *
     * @param Mage_Customer_Model_Customer $customer
     *
     */
    public function checkDormantAccount($customer){

        $customer_account = $this->_getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($customer->getEmail());


        if ($customer_account->getId() && $customer_account->getDormantFlag()) {

            $this->_successDormantUser($customer_account);

            $customer_account->setDormantFlag(0);
            $customer_account->setPassword($customer->getPassword());
            $customer_account->save();

            Mage::app()->getResponse()->sendResponse();
            exit;
        }

    }


    /**
     * Success Registration
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Customer_AccountController
     */
    protected function _successDormantUser($customer){

        $session = $this->_getSession();

        $app = $this->_getApp();
        /** @var $store  Mage_Core_Model_Store*/
        $store = $app->getStore();
        $customer->sendNewAccountEmail('confirmation', '', Mage::app()->getStore()->getId()); 
        $customerHelper = $this->_getHelper('customer');
        $session->addSuccess($this->__('Thanks for registering. It appears you\'ve shopped with us before. To continue please check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
            $customerHelper->getEmailConfirmationUrl($customer->getEmail())));

        $url = $this->_getUrl('*/*/login', array('_secure' => true));

        $this->_redirectSuccess($url);
        return $this;

    }

    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('account/details');
        }

        if(Mage::getSingleton('customer/session')->getPasswordExpired() && !$this->getRequest()->getParam('change_password')){
            return $this->_redirect('account/details');
        }

        if ($this->getRequest()->isPost()) {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getSession()->getCustomer();

            /** @var $customerForm Mage_Customer_Model_Form */
            $customerForm = $this->_getModel('customer/form');
            $customerForm->setFormCode('customer_account_edit')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

            $errors = array();
            $customerErrors = $customerForm->validateData($customerData);
            if ($customerErrors !== true) {
                $errors = array_merge($customerErrors, $errors);
            } else {
                $customerForm->compactData($customerData);
                $errors = array();

                // If password change was requested then add it to common validation scheme
                if ($this->getRequest()->getParam('change_password')) {
                    $currPass   = $this->getRequest()->getPost('current_password');
                    $newPass    = $this->getRequest()->getPost('password');
                    $confPass   = $this->getRequest()->getPost('confirmation');

                    $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                    if ( $this->_getHelper('core/string')->strpos($oldPass, ':')) {
                        list($_salt, $salt) = explode(':', $oldPass);
                    } else {
                        $salt = false;
                    }

                    if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                        if (strlen($newPass)) {
                            /**
                             * Set entered password and its confirmation - they
                             * will be validated later to match each other and be of right length
                             */
                            $customer->setPassword($newPass);
                            $customer->setPasswordConfirmation($confPass);
                        } else {
                            $errors[] = $this->__('New password field cannot be empty.');
                        }
                    } else {
                        $errors[] = $this->__('Invalid current password');
                    }
                }

                // Validate account and compose list of errors if any
                $customerErrors = $customer->validate();
                if (is_array($customerErrors)) {
                    $errors = array_merge($errors, $customerErrors);
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('account/details');
                return $this;
            }

            try {
                Mage::getSingleton('customer/session')->setPasswordExpired(false);
                $customer->cleanPasswordsValidationData();
                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('The account information has been saved.'));

                $this->_redirect('account/details');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
        }

        $this->_redirect('account/details');
    }

    /**
     * Customer logout action
     */
    public function logoutAction()
    {
        Mage::getSingleton('customer/session')->setPasswordExpired(false);
        $session = $this->_getSession();
        $session->logout()->renewSession();

        if (Mage::getStoreConfigFlag(Mage_Customer_Helper_Data::XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD)) {
            $session->setBeforeAuthUrl(Mage::getBaseUrl());
        } else {
            $session->setBeforeAuthUrl($this->_getRefererUrl());
        }
        $this->_redirect('*/*/logoutSuccess');
    }
}

