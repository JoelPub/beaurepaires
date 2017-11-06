<?php
/** Create Price Request transactional email */
$installer = $this;
$installer->startSetup();

$template_name = "Price Request Email";
$template_subject = "Price Request";
$template_text = "
<p>Thank you for contacting Beaurepaires.</p>
<p>Please find below a summary of your request for pricing. A Beaurepaires expert will be in contact with you within 24 hours with a response to your request.</p>
</br>
<b>Your request for pricing: #{{var order_no}}</b><br>
<p>Requested on {{var order_date}}</span></p>
<br>
		
{{block type='core/template' area='frontend' template='requestprice/email/order.phtml'}}

<p>Your details:</p>
<p>{{var customer_name}}<p>
<p>{{var phone}}<p>
<p>{{var store_address}}<p>
		
";
$template_style = null;
$template_code = null; // custom email
$template_config = "vir/price_request_booking/price_request_booking_template";
$template_variables = null;

    
    $templateDb = Mage::getModel('core/email_template')
        ->setTemplateCode($template_name)
        ->setTemplateSubject($template_subject)
        ->setTemplateText($template_text)
        ->setTemplateStyles($template_style)
        ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate())
        ->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
        ->setOrigTemplateCode($template_code)
        ->setOrigTemplateVariables($template_variables)
        ->setTemplateType(Mage_Core_Model_Email_Template::TYPE_HTML)
        ->save();
    
    // Set this template in config
    $installer->setConfigData($template_config, $templateDb->getId());
    
$this->endSetup();