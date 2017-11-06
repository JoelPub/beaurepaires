<?php
class ApdInteract_Bookings_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {

      if(!$this->_adminSession()->isLoggedIn()){
          $this->norouteAction();
          return;
      }

	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Store Manager Console"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("store manager console", array(
                "label" => $this->__("Store Manager Console"),
                "title" => $this->__("Store Manager Console")
		   ));

      $this->renderLayout();
	  
    }

    protected function _adminSession(){
        return Mage::getSingleton('admin/session');
    }
}