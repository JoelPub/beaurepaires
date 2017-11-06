<?php
class ApdInteract_Requestprice_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$this->loadLayout();
    	$this->renderLayout();
    	
    }
    
    public function fillcartAction()
    {
        // eg https://beau.l/requestprice/index/fillcart/cart/521461-P|6|0,529713-P|4|1
        
        // cart/sku1|qty|option,sku2|qty|option
        // eg
        //abc123|4|1,def123|2|2
        $cart = $this->getRequest()->getParam('cart');        
        Mage::helper('apdinteract_requestprice')->createTestCart($cart);
        $this->_redirect('checkout/cart');
    }
    
}