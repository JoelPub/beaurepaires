<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApdInteract_Si_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        
        if($this->checkIfApplicable()):
            $this->downloadAction();        
        else:
            echo "Not Applicable on this site";         
        endif;
    }
    
    public function downloadAction() {        
        
       if($this->checkIfApplicable()):
        $params = $this->getRequest()->getParams();
        $xml = Mage::helper('si')->getStreamInteractiveOrderXml($params);
        if (!$xml) {
            exit;
        }
        
        $this->loadLayout(false);
        $this->getResponse()->setHeader('Content-Type','text/xml');
            
        $this->getResponse ()
        ->setHttpResponseCode ( 200 )
        ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
        ->setHeader ( 'Pragma', 'public', true )
        ->setHeader ( 'Content-type', 'xml' )
        ->setBody($xml);
        
        $this->renderLayout();        
         else:
            echo "Not Applicable on this site";         
        endif;
    }
    
    public function checkIfApplicable() {        
        return (!Mage::getStoreConfig('advanced/modules_disable_output/ApdInteract_Si'))? true: false;
            
    }
}
