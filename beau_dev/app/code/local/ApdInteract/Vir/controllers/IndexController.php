<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApdInteract_Vir_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();

        $block = $this->getLayout()
        ->createBlock('core/text', 'parent-id')
        ->setText('<script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>'
                .'<script type="text/javascript" src="/js/adpinteract/vir.js"></script>'
                .'<link rel="stylesheet" type="text/css" href="/skin/adminhtml/default/default/css/apdinteract/vir.css" media="screen, projection">'
                .'<span class="fieldlabel">Parent Id</span>'
                .'<input type="text" id="parentid" class="lookuptext"/>'
                .'<input type="button" id="btngetdata" class="actionbutton" value="Get Data"/>'
                .'<div id="message"></div>'
                .'<div id="virpanel"></div>');

        $this->_addContent($block); 

        $this->renderLayout();
    }

   protected function _addContent(Mage_Core_Block_Abstract $block)
   {
       $this->getLayout()->getBlock('content')->append($block);
       return $this;
   }
     
}

