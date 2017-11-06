<?php
/** Create Pre-booking transactional email */
$installer = $this;
$installer->startSetup();

$template_name = "Pre-booking Email";
$template_subject = "Pre-booking Reminder";
$template_text = <<<EOF
{{template config_path="design/email/header"}}
{{inlinecss file="email-inline.css"}}

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="action-content">
			<div class="font-15">
<p>Dear {{var myfirstname}} {{var mylastname}},</p>

<p>This is a reminder for your appointment with Beaurepaires on {{var mydelivery_date}} at {{var mydelivery_time}}. </p>

<p>To change or cancel this appointment, please contact the store below.</p>

<p>We look forward to assisting you soon.</p>

<p>{{var mystorename}}</p>
<p>{{var mystoreaddress}}</p>
<p>{{var mystorephone}}</p>
<p>Store ID:{{var mystorelocation}}</p>
			</div>	
			<table><tr class="row">
				<td class="full wrapper last">
					<p><a href="{{store url=""}}terms-and-conditions">Terms</a> | <a href="{{store url=""}}privacy-and-security-disclaimer">Privacy</a> | <a href="{{store url=""}}newsletter/manage/">Unsubscribe</a></p>
				</td>
			</tr>
                </table>
        </td>
    </tr>
</table>

{{template config_path="design/email/footer"}}
EOF
;
$template_style = null;
$template_code = null; // custom email
$template_config = "vir/pre_booking/pre_booking_template";
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