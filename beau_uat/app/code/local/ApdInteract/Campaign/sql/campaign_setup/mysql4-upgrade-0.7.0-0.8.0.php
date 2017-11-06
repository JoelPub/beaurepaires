<?php

$installer = $this;

$installer->startSetup();

$code = "Easter Campaign";
$subject = "Easter Campaign";
$text = '<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0" class="singleColumn">
                  <tbody>
                    <tr>
                      <td align="left" style="font-family: Arial, Verdana, sans-serif; font-size: 15px; line-height:24px; font-weight:bold; color: #0167b1; padding-top: 15px; padding-bottom:5px; text-align: left;">Hi Admin!</td>
                    </tr>
                    <tr>
                      <td align="left" style="font-family: Arial, Verdana, sans-serif; font-size: 13px; line-height:20px; color: #666666; text-align: left; padding-top:0px; padding-bottom:0px;">We have received the below subscription. <br>
                        <br />
                        <span><b>Name: </b>{{var data.name}} {{var data.last_name}}</span><br>
                        <span><b>Email: </b>{{var data.email}}</span><br>
                        <span><b>Mobile: </b>{{var data.mobile}}</span><br>
                        <span><b>Postcode: </b>{{var data.postcode}}</span><br>
                        <span><b>Make: </b>{{var data.make}}</span><br>
                        <span><b>Model: </b>{{var data.model}}</span>
                                            <table border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td height="15" style="height:15px; font-size:15px; line-height:15px;"><span style="font-size:15px; line-height:15px;">&nbsp;</span></td>
                                                    </tr>                                                   
                                                </tbody>
                                            </table>
                        <span><br />
                        Thanks!<br>
                        </td>
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
