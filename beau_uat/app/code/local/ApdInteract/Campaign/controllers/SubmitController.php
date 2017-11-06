<?php
class ApdInteract_Campaign_SubmitController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {    	
        $helper = Mage::Helper('campaign');
    	$url = Mage::helper('core/http')->getHttpReferer();
        
        $identifier = substr(parse_url($url,PHP_URL_PATH),1);        
        $identifier = rtrim($identifier,"/");     
        $details = $helper->getPageInfoByIdentifier($identifier);          
        
        if(count($details->getData())>0):
            $postData = $this->getRequest()->getPost();
            $url = '/'.$details->getData('thank_you');
            $this->_createOrder($postData,$details);
            Mage::app()->getResponse()->setRedirect($url);
        else:
            echo "Campaign not configured correctly.";
            exit();
        endif;
        
    }   
    
    private function _createOrder($postData,$details) {
         try {
            
            $cart = Mage::getModel('checkout/cart');
            $cart->truncate(); 
            $cart->init();             
            $helper = Mage::Helper('apdinteract_requestprice'); 
            $storeLocationId = $helper->getPostedValues('cartaddress-id');
            Mage::getSingleton('core/session')->setRequestStoreId($storeLocationId);
            
            $_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $details->getData('sku'));;
            
            $parent_product_id = $_product->getId();
                        
            
            $qty = 1; //$helper->getQty();

            $helper->addSimpleProductToCart($parent_product_id, $qty);

            $order_id = $helper->convertQuoteToOrder($details->getData('campaign_name'));

            //return $this->_successMessageCode();
            $analytics = Mage::helper('apdwidgets')->getAnalyticsAsJson($order_id);
            return array("Success" => 1,"analytics"=>$analytics);

        } catch (Exception $e) {
            Mage::logException($e);
            return $helper->errorMessageCode();
        }            
    }
    
    
    
    
    
}