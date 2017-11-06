<?php

$installer = $this;

$installer->startSetup();

$code = "were-taking-care-of-xmas";
$subject = "We're taking care of Christmas";
$text = '<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0" class="singleColumn">
                  <tbody>
                    <tr>
                      <td align="left" style="font-family: Arial, Verdana, sans-serif; font-size: 17px; line-height:24px; font-weight:bold; color: #0167b1; padding-top: 15px; padding-bottom:5px; text-align: left;">Thank you for your subscription at  {{config path="general/store_information/name"}}</td>
                    </tr>
                    <tr>
                      <td align="left" style="font-family: Arial, Verdana, sans-serif; font-size: 13px; line-height:20px; color: #666666; text-align: left; padding-top:0px; padding-bottom:0px;">We have received your subscription. For your records, please find below a copy of the information we have received from you.<br />
                        <br />
<span><b>Name: </b>{{var data.first_name}} {{var data.last_name}}</span><br>
<span><b>Email: </b>{{var data.email}}</span><br>
<span><b>Mobile: </b>{{var data.mobile}}</span><br>
<span><b>Postcode: </b>{{var data.postcode}}</span>
 <table border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td height="15" style="height:15px; font-size:15px; line-height:15px;"><span style="font-size:15px; line-height:15px;">&nbsp;</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top" align="left" bgcolor="#f28c1d" style="text-align:left; padding-left:20px; padding-right:20px; padding-top:10px; padding-bottom:10px; color:#ffffff; font-weight:bold; font-size:14px; line-height:18px; "><a href="https://www.beaurepaires.com.au/" style=" color:#ffffff; font-weight:bold; font-size:14px; line-height:18px; text-decoration:none;" target="_blank" name="Button Website">Visit our website</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                        <span><br />
                        {{depend store_phone}}
						<b>Call Us:</b> <a style="color:#f28c1d; text-decoration:none;" href="tel:{{var phone}}">{{var store_phone}}</a>
						{{/depend}}</span><br>
                                <span>{{depend store_hours}}
						<span class="no-link">{{var store_hours}}</span>
						{{/depend}}</span><br>
				<span>{{depend store_email}}
						<b>Email:</b> <a style="color:#f28c1d; text-decoration:none;" href="mailto:{{var store_email}}">{{var store_email}}</a>
						{{/depend}}
				</span></td>
                    </tr>
                  </tbody>
                </table>';
$styles = '';

$template = Mage::getModel('adminhtml/email_template');

$template->setTemplateSubject($subject)
        ->setTemplateCode($code)
        ->setTemplateText($text)
        ->setTemplateStyles($styles)
        ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate())
        ->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
        ->setTemplateType(Mage_Core_Model_Email_Template::TYPE_HTML);

$template->save();



$installer->endSetup();
