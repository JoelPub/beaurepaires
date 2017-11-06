<?php
/**
 * StoreFront Silverpop Transaction Email Magento Extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to commercial source code license 
 * of StoreFront Consulting, Inc.
 *
 * @category	SFC
 * @package    	SFC_SilverpopTransactional
 * @website 	http://www.storefrontconsulting.com/
 * @copyright 	Copyright (C) 2009-2013 StoreFront Consulting, Inc. All Rights Reserved.
 */

class SFC_SilverpopTransactional_Block_Adminhtml_Run extends Mage_Adminhtml_Block_System_Convert_Profile_Run
{
    protected function _toHtml()
    {		
        echo '
        <html>
	        <style type="text/css" >
			    ul { list-style-type:none; padding:0; margin:0; }
			    li { margin-left:0; border:1px solid #ccc; margin:2px; padding:2px 2px 2px 2px; font:normal 12px sans-serif; }
			    img { margin-right:5px; }
			</style>
         <head>';
        echo '
        <script type="text/javascript">
        	var FORM_KEY = "'.Mage::getSingleton('core/session')->getFormKey().'";        	
        </script>';

        $headBlock = $this->getLayout()->createBlock('page/html_head');
        $headBlock->addJs('prototype/prototype.js');
        $headBlock->addJs('mage/adminhtml/loader.js');
        echo $headBlock->getCssJsHtml();
        
		echo '
<script type="text/javascript">
processed = 0;
process_count = '.Mage::getSingleton('adminhtml/session')->getData('silverpoptransactional_process_count').';
function processData() {
	new Ajax.Request("'.$this->getUrl('silverpoptransactional/adminhtml_manage/run').'", {
		method: "post",
		onSuccess: function(transport) {
			if(transport.responseText == "done"){
				finish();
			} else if (isNaN(transport.responseText)) {
				$("profileRows").innerHTML += "<li style=\"background-color:#FDD;\">" + transport.responseText + "</li>";
				processData();
			} else {
				processed = processed + Number(transport.responseText)
				$("progress_count").innerHTML = processed + " / " + process_count;
				processData();
			}
		}
	});
}

function finish() {
	$("progress").innerHTML += "<li style=\"background-color:#DDF;\">Processing Complete.</li>";
	$("loader").hide();
}

processData();
</script>
<ul>
    <li>Processing emails, please wait...</li>
    <li style="background-color:#FFD;">Warning: Please do not close the window during processing.</li>
</ul>
<ul id="profileRows">
	
</ul>
<ul id="progress">
	<li style="background-color:#FFD;">
		<img id="loader" src="'.Mage::getDesign()->getSkinUrl('images/ajax-loader.gif').'" />
		<span>Processed <strong id="progress_count">0 / '.Mage::getSingleton('adminhtml/session')->getData('silverpoptransactional_process_count').'</strong> emails.</span>
	</li>
</ul>
';
    }
}