<?php
/*
 * BCC-370: Bookings Dashboard to be renamed the 'Store Manager Console' and become the default page
 */
require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'IndexController.php';
class ApdInteract_Adminhtml_IndexController extends Mage_Adminhtml_IndexController
{
    /**
     * Admin area entry point     
     */
    public function indexAction()
    {
        $session = Mage::getSingleton('admin/session');
        
        if ($session->isFirstPageAfterLogin()) {
            // retain the "first page after login" value in session (before redirect)
            $session->setIsFirstPageAfterLogin(true);
        }
        
        $role = $session->getUser()->getRole()->getRoleName();
        //redirect to vir commercial if user type is store manager
        $url = ($role == 'Store Manager' ? 'admin_bookings/adminhtml_bookingsbackend': $session->getUser()->getStartupPageUrl());
        
        
        $this->_redirect($url);
    }
 
}
