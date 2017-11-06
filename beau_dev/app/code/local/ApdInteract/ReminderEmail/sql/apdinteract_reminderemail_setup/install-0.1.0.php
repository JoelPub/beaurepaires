<?php
/** Create Your Next Service transactional email */
$installer = $this;
$installer->startSetup();

$template_name = "Your Next Service Email";
$template_subject = "Your Next Service Reminder";
$template_text = <<<EOF
<p>Dear {{var customer_name}},</p>
<br>
		
<p>It has been {{var months}} months since your last appointment with Beaurepaires.</p>
<p>Book in for an inspection or our other maintenance services today to improve your safety and prolong the life of your tyres. Further details can be found <a href='https://www.beaurepaires.com.au/services/tyre-maintenance-services'>here.</a></p>
<p>If you purchased our Road Hazard Warranty, your tyres may be nearing your free 5,000km inspection. Further details can be found <a href='https://www.beaurepaires.com.au/services/road-hazard-warranty'>here.</a></p>
<p>It's the type of quality you have come to expect from Beaurepaires.</p>
<br>
				
<p>{{var store_name}}</p>
<p>{{var store_address}}</p>
<p>{{var store_phone}}</p>
<p>{{var booking_date_time}}</p>
<p>{{var booking_type}}</p>
EOF
;
$template_style = null;
$template_code = null; // custom email
$template_config = "vir/next_service/next_service_template";
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