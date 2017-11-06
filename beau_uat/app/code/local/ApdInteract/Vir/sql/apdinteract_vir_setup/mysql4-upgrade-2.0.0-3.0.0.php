<?php
/** Create VIR notification transactional email */
$installer = $this;
$installer->startSetup();

$template_name = "VIR Notification Email";
$template_subject = "VIR Creation Notification";
$template_text = "
<p>A new VIR has been created for your store. Please find a summary of the VIR below.</p>
</br>
		
<p>VIR: {{var vir_id}}</p>
<p>Order: {{var order_no}}<p>
<p>Ordered on: {{var order_date}}<p>
<p>Fitting Date: {{var fitting_date}}<p>
<p>Fitting Time: {{var fitting_time}}<p>

{{block type='core/template' area='frontend' template='vir/email/order.phtml'}}

<p>Customer details:</p>
<p>{{var customer_name}}<p>
<p>{{var phone}}<p>
		
";
$template_style = null;
$template_code = null; // custom email
$template_config = "vir/notification/notification_template";
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