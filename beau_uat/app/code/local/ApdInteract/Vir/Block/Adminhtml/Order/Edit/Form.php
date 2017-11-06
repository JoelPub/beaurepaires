<?php
class ApdInteract_Vir_Block_Adminhtml_Order_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        // Instantiate a new form to display our order for editing.
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl(
                'apdinteract_vir_admin/order/edit',
                array(
                    '_current' => true,
                    'continue' => 0,
                )
            ),
            'method' => 'post',
        ));
        $form->setUseContainer(true);
        $this->setForm($form);

        // Define a new fieldset. We need only one for our simple entity.
        $fieldset = $form->addFieldset(
            'general',
            array(
                'legend' => $this->__('Vehicle Inspection Report')
            )
        );       

        $orderSingleton = Mage::getSingleton(
            'apdinteract_vir/order'
        );
        
        $order  = $this->_getOrder()->_data;

        // Add the fields that we want to be editable.
        $this->_addFieldsToFieldset($fieldset, array(
            'parent_id' => array('label' => '','input' => 'text','required' => false, 'class' => 'parent_id', 'name' => 'parent_id',
                'after_element_html' => ''),
            'Customerdetails' => array('label' => '','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'Customerdetails',
                'after_element_html' => ''),
            'orderdate' => array('label' => 'Date','input' => 'text','required' => false, 'class' => 'datefieldhidden',
                'after_element_html' => '<input id="date1" name="orderdate[date1]" value="" class="datefield" title="Day" type="text" placeholder="DD" onchange="SaveDate(\'orderdate\');">'
                .'<span class="dateslash">/</span><input id="date2" name="orderdate[date2]" value="" class="datefield" title="Month" type="text" placeholder="MM" onchange="SaveDate(\'orderdate\');">'
                .'<span class="dateslash">/</span><span class="dateyearlabel" style="padding-top:0px;height: 49px;">20</span>'
                . '<input id="date3" name="orderdate[date3]" value="" class="datefield" title="Year" type="text" '
                . 'placeholder="YY" onchange="SaveDate(\'orderdate\');" style="margin-left:0px;">'
                . '<span class="dateslash"> (DD/MM/YY) </span>'
                . '<script type="text/javascript">LoadDate(\'orderdate\');</script>',),
            'timereq' => array('label' => 'Time req','input' => 'text','required' => false,'onchange' => "updateText(this.id);",),
            'invoiceno' => array('label' => 'Invoice No','input' => 'text','required' => false,'onchange' => "updateText(this.id);",
                'after_element_html' => '</tbody></table><table cellspacing="0" class="newline"><tbody>',),
            'header1' => array('label' => '','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'Customerdetails',
                'after_element_html' => '<h3 class="virheader">Customer Details</h3></td></tr></tbody></table><table cellspacing="0" class="newline"><tbody><tr><td></td><td>'),
            'custname' => array('label' => 'Name','input' => 'text','required' => false,'after_element_html' => '','class' => 'greyfield long',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '<div id="custname_autocomplete_choices" class="autocomplete"></div>'),
            'custaddress' => array('label' => 'Address','input' => 'text','required' => false,'after_element_html' => '','class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'custsuburb' => array('label' => 'Suburb','input' => 'text','required' => false,'after_element_html' => '','class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'custpostcode' => array('label' => 'Postcode','input' => 'text','required' => false,'after_element_html' => '','class' => 'greyfield short',
                'onchange' => "updateText(this.id);",),
            'custphoneno' => array('label' => 'Phone No.','input' => 'text','required' => false,'after_element_html' => '','class' => 'greyfield shorter',
                'onchange' => "updateText(this.id);",),
            'custemail' => array('label' => 'Email','input' => 'text','required' => false,'class' => 'greyfield long',
                'after_element_html' => '</tbody></table><table class="viroptiontableheader field optiongroup rightgroup" style="display:block"><tbody style="display:block">',
                'onchange' => "updateText(this.id);",),
            'option1' => array('label' => $this->__('How long since the wheels were last balanced?'),'input' => 'label','required' => false,
                'after_element_html' => '',),
            'option2' => array('label' => $this->__('How long since your last wheel alignment?'),'input' => 'label','required' => false,
                'after_element_html' => '</tbody></table><table class="viroptiontable field optiongroup rightgroup" style="display:block"><tbody style="display:block">',),
            'lastbalance' => array(
                'label' => $this->__(''),
                'name'  => 'lastbalance[]', //add this one and make sure it contains []
                'input' => 'radios',
                'required' => true,
                'values' => array(
                            array('value'=>'0','label'=>'0-6 months '),
                            array('value'=>'1','label'=>'6-12 months'),
                            array('value'=>'2','label'=>'over 12 months')
                       ),
                'onclick' => "", // doesn't work on radio buttons. // updateRadio(this.id);
                'onchange' => "", // updateRadio(this.id);
                'disabled' => false,
                'after_element_html' => '',
                'required' => false,
                'class' => 'optiongroupgrey radio',
            ), //0 = 0-6 months, 1 = 6-12 months, 2 = over 12 months
            
            'lastwheelalignment' => array(
                'label' => $this->__(''),
                'name'  => 'lastwheelalignment[]', //add this one and make sure it contains []
                'input' => 'radios',
                'required' => true,
                'values' => array(
                            array('value'=>'0','label'=>'0-6 months '),
                            array('value'=>'1','label'=>'6-12 months'),
                            array('value'=>'2','label'=>'over 12 months'),
                       ),
                'onclick' => "",
                'onchange' => "",
                'disabled' => false,
                'after_element_html' => ''
                . '</tbody></table><table cellspacing="0" class="newline"><tbody>',
                'required' => false,
                'class' => 'optiongroupgrey radio',
            ), //0 = 0-6 months, 1 = 6-12 months, 2 = over 12 months
            //'lastwheelalignment' => array('label' => $this->__('lastwheelalignment'),'input' => 'text','required' => false,),
            'header2' => array('label' => '','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'Vehicledetails',
                'after_element_html' => '<h3 class="virheader">Vehicle Details</h3></td></tr></tbody></table><table cellspacing="0" class="newline"><tbody><tr><td></td><td>'),
            //'Vehicledetails' => array('label' => $this->__('Vehicle Details'),'input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'Vehicledetails',),
            'vehiclemake' => array('label' => $this->__('vehiclemake'),'input' => 'text','required' => false,'class' => 'yellow longer',
                'onchange' => "updateText(this.id);",),
            'vehiclemodel' => array('label' => $this->__('vehiclemodel'),'input' => 'text','required' => false,'class' => 'yellow longer',
                'onchange' => "updateText(this.id);",),
            'vehiclerego' => array('label' => $this->__('vehiclerego'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'vehicleodometer' => array('label' => $this->__('vehicleodometer'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'vehiclecomments' => array('label' => $this->__('vehiclecomments'),'input' => 'textarea','required' => false,  
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</tbody></table><table class="virbigcheckbox field" style="display:block"><tbody style="display:block">',),            
            'vehiclewheelLF' => array('label' => $this->__('LF'),'input' => 'checkbox','required' => false,'onchange' => "updateCheck('vehiclewheelLF');",
                'checked'  => $this->_isChecked("vehiclewheelLF",$order),), 
            'vehiclewheelLFtorque' => array('label' => $this->__(''),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</tbody></table><table class="virbigcheckbox field" style="display:block"><tbody style="display:block">',),
            'vehiclewheelRF' => array('label' => $this->__('RF'),'input' => 'checkbox','required' => false,'onchange' => "updateCheck('vehiclewheelRF');",
                'checked'  => $this->_isChecked("vehiclewheelRF",$order),),
            'vehiclewheelRFtorque' => array('label' => $this->__(''),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</tbody></table><table class="virbigcheckbox field" style="display:block;clear: left;"><tbody style="display:block">',),
            'vehiclewheelLR' => array('label' => $this->__('LR'),'input' => 'checkbox','required' => false,'onchange' => "updateCheck('vehiclewheelLR');",
                'checked'  => $this->_isChecked("vehiclewheelLR",$order),),
            'vehiclewheelLRtorque' => array('label' => $this->__(''),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</tbody></table><table class="virbigcheckbox field" style="display:block"><tbody style="display:block">',),
            'vehiclewheelRR' => array('label' => $this->__('RR'),'input' => 'checkbox','required' => false,'onchange' => "updateCheck('vehiclewheelRR');",
                'checked'  => $this->_isChecked("vehiclewheelRR",$order),),
            'vehiclewheelRRtorque' => array('label' => $this->__(''),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</tbody></table><table class="virbigcheckbox middlebox field" style="display:block;clear: left;"><tbody style="display:block">',),
            'vehiclewheelSPARE' => array('label' => $this->__('SPARE'),'input' => 'checkbox','required' => false,'onchange' => "updateCheck('vehiclewheelSPARE');",
                'checked'  => $this->_isChecked("vehiclewheelSPARE",$order),),
            'vehiclewheelSPAREtorque' => array('label' => $this->__(''),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</tbody></table><div class="spacer"></div><table cellspacing="0" class="newline paddedtop"><tbody>',),
            'spareconditioncomment' => array('label' => $this->__('Spare Condition'),'input' => 'text','required' => false,
                'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'vehiclecommentsimage' => array('label' => $this->__('Drawing box'),'input' => 'textarea','required' => false,
                'after_element_html' => '</tbody></table><table cellspacing="0" class="newline"><tbody>',
                'onchange' => "updateText(this.id);",),            
            'header3' => array('label' => '','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'Vehicledetails',
                'after_element_html' => '<hr/><h3 class="virheader">Quote/Work Order</h3></td></tr></tbody></table><table cellspacing="0" class="newline"><tbody><tr><td></td><td>'),
            //DETAILS OF WORK TO BE PERFORMED
            'subheading3' => array('label' => 'DETAILS OF WORK TO BE PERFORMED','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'subheading3',
                'after_element_html' => '<br/><br/><br/></td></tr></tbody></table>'
                . '<div class="headinggroup"><div class="heading1">Description</div><div class="heading2">QTY</div><div class="heading3">PRICE</div></div>'
                . '<table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workordertyresdescription' => array('label' => $this->__('Tyres'),'input' => 'text','required' => false,'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'workordertyresqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workordertyresprice' => array('label' => '','input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '<br/><br/><br/></td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workorderbalances' => array('label' => $this->__('Balances'),'input' => 'label','required' => false,
                'onchange' => "updateText(this.id);",),
            'workorderbalancesoptionsteel' => array('label' => $this->__('steel'),'input' => 'checkbox','required' => false,
                'after_element_html' => '','onchange' => "updateCheck('workorderbalancesoptionsteel');",
                'checked'  => $this->_isChecked("workorderbalancesoptionsteel",$order),),
            'workorderbalancesoptionalloy' => array('label' => $this->__('alloy'),'input' => 'checkbox','required' => false,
                'after_element_html' => '','onchange' => "updateCheck('workorderbalancesoptionalloy');",
                'checked'  => $this->_isChecked("workorderbalancesoptionalloy",$order),),
            'workorderbalancesoptionpremium' => array('label' => $this->__('premium'),'input' => 'checkbox','required' => false,
                'after_element_html' => '','onchange' => "updateCheck('workorderbalancesoptionpremium');",
                'checked'  => $this->_isChecked("workorderbalancesoptionpremium",$order),),
            'workorderbalancesqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workorderbalancesprice' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workorderalign' => array('label' => $this->__('Alignment'),'input' => 'label','required' => false,),
            'workorderalignoptionstandard' => array('label' => $this->__('standard'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('workorderalignoptionstandard');",'checked'  => $this->_isChecked("workorderalignoptionstandard",$order),),
            'workorderalignoptionthrust' => array('label' => $this->__('thrust'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('workorderalignoptionthrust');",'checked'  => $this->_isChecked("workorderalignoptionthrust",$order),),
            'workorderalignoption4wheel' => array('label' => $this->__('4wheel'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('workorderalignoption4wheel');",'checked'  => $this->_isChecked("workorderalignoption4wheel",$order),),
            'workorderalignoption4wd' => array('label' => $this->__('4wd'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('workorderalignoption4wd');",'checked'  => $this->_isChecked("workorderalignoption4wd",$order),),
            'workorderalignqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workorderalignprice' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workorderwheelsdescription' => array('label' => $this->__('Wheels'),'input' => 'text','required' => false,'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'workorderwheelsqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workorderwheelsprice' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workorderbatreportdescription' => array('label' => $this->__('Battery Report'),'input' => 'text','required' => false,
                'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'workorderbatreportqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workorderbatreportprice' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workorderbrakesdescription' => array('label' => $this->__('Brakes'),'input' => 'text','required' => false,'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'workorderbrakesqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workorderbrakesprice' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workorderotherdescription' => array('label' => $this->__('Other'),'input' => 'text','required' => false,'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'workorderotherqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workorderotherprice' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'workorderpuncturedescription' => array('label' => $this->__('Puncture'),'input' => 'text','required' => false,'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            'workorderpunctureqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'workorderpunctureprice' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline"><tbody><tr><td></td><td>',),
            'custapprsubscribe' => array('label' => $this->__('I would like to receive service reminders and information about products or services')
                ,'input' => 'checkbox','required' => false
                ,'onchange' => "updateCheck('custapprsubscribe');"
                ,'checked'  => $this->_isChecked("custapprsubscribe",$order),),
            'custapprcollectdata' => array('label' => $this->__('For Auto Clubs Members only: Beaurepaires won\'t collect Auto Clubs member\'s data unless the member has opted in the tick box')
                ,'input' => 'checkbox','required' => false
                ,'onchange' => "updateCheck('custapprcollectdata');",'checked'  => $this->_isChecked("custapprcollectdata",$order),
                'after_element_html' => '</td></tr></tbody></table><div style="height: 20px;width: 99%;"></div><table cellspacing="0" class="newline"><tbody><tr><td></td><td>',),
            'subheading4' => array('label' => 'Please tick the preferred payment method:','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'subheading4',
                'after_element_html' => '<br/><br/><br/></td></tr></tbody></table>'
                . '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody><tr><td></td><td>',),
            'custapprpaycash' => array('label' => $this->__('cash'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('custapprpaycash');",'checked'  => $this->_isChecked("custapprpaycash",$order),
                'after_element_html' => '',),
            'custapprpayvisamaster' => array('label' => $this->__('Visa / Master'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('custapprpayvisamaster');",'checked'  => $this->_isChecked("custapprpayvisamaster",$order),
                'after_element_html' => '',),
            'custapprpayaccount' => array('label' => $this->__('account'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('custapprpayaccount');",'checked'  => $this->_isChecked("custapprpayaccount",$order),
                'after_element_html' => '',),
            'custapprpayother' => array('label' => $this->__('other'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('custapprpayother');",'checked'  => $this->_isChecked("custapprpayother",$order),
                'after_element_html' => '',),
            'custapprpayeftpos' => array('label' => $this->__('eftpos'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('custapprpayeftpos');",'checked'  => $this->_isChecked("custapprpayeftpos",$order),
                'after_element_html' => '',),
            'custapprpayamexdiners' => array('label' => $this->__('Amex / Diners'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('custapprpayamexdiners');",'checked'  => $this->_isChecked("custapprpayamexdiners",$order),
                'after_element_html' => '',),
            'custapprpayfinance' => array('label' => $this->__('finance'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('custapprpayfinance');",'checked'  => $this->_isChecked("custapprpayfinance",$order),
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline"><tbody><tr><td></td><td>',),
            'custapprcall' => array('label' => $this->__('Call When Vehicle is ready'),'input' => 'checkbox','required' => false,    
                'onchange' => "updateCheck('custapprcall');",'checked'  => $this->_isChecked("custapprcall",$order),
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline"><tbody><tr><td></td><td>',),
            'custapprsignatureimage' => array('label' => $this->__('custapprsignatureimage'),'input' => 'textarea','required' => false, 
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline"><tbody><tr><td></td><td>',),
            'workshopsignatureimage' => array('label' => $this->__('workshopsignatureimage'),'input' => 'textarea','required' => false,  
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            //Tyre Services Checklist
            /*
            'subheading4' => array('label' => '','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'subheading4',
                'after_element_html' => '</td></tr><tr><td colspan="2"><hr/><h2 class="virheader">Tyre Services Checklist</h2></td></tr></tbody></table>'
                . '<div class="heading1">Description</div>'
                . '<table cellspacing="0" class="newline"><tbody>'
                . '<tr><td colspan="3">BEFORE ADJUSTMENT</td><td colspan="3">AFTER ADJUSTMENT</td></tr>'
                . '<tr><td>FRONT</td><td>LEFT</td><td>RIGHT</td><td>FRONT</td><td>LEFT</td><td>RIGHT</td></tr>'
                . '<tr><td>CAMBER</td><td><div class="tablegroup"><table><tbody>'                
                . '',),           
            */
            'wheelalignfrontbeforeleftcamber' => array('label' => $this->__('CAMBER'),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",
                'after_element_html' => '',),
            'wheelalignfrontbeforeleftcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            //    'after_element_html' => '</tbody></table></div></td>'
            //    . '<td><br/></td><td><br/></td><td><br/></td><td><br/></td></tr></tbody></table>'
            //    . '<table cellspacing="0" class="newline"><tbody>',),
            'wheelalignfrontbeforerightcamber' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontbeforerightcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterleftcamber' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterleftcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterrightcamber' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterrightcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield', 
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            'wheelalignfrontbeforeleftcaster' => array('label' => $this->__('CASTER'),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontbeforeleftcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontbeforerightcaster' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontbeforerightcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterleftcaster' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterleftcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterrightcaster' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterrightcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',  
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            'wheelalignfrontbeforelefttoein' => array('label' => $this->__('TOE-IN'),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontbeforelefttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),             
            'wheelalignfrontbeforerighttoein' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontbeforerighttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),            
            'wheelalignfrontafterlefttoein' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterlefttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),            
            'wheelalignfrontafterrighttoein' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignfrontafterrighttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield', 
                'onchange' => "updateText(this.id);",
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            'wheelalignrearbeforeleftcamber' => array('label' => $this->__('CAMBER'),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforeleftcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforerightcamber' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforerightcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterleftcamber' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterleftcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterrightcamber' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterrightcamber2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield', 
                'onchange' => "updateText(this.id);",           
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            'wheelalignrearbeforeleftcaster' => array('label' => $this->__('CASTER'),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforeleftcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforerightcaster' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforerightcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterleftcaster' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterleftcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterrightcaster' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterrightcaster2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',  
                'onchange' => "updateText(this.id);",          
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            'wheelalignrearbeforelefttoein' => array('label' => $this->__('TOE-IN'),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforelefttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforerighttoein' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearbeforerighttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterlefttoein' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterlefttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterrighttoein' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",),
            'wheelalignrearafterrighttoein2' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'measurefield',
                'onchange' => "updateText(this.id);",            
                'after_element_html' => '</td></tr></tbody></table><div style="height: 20px;width: 99%;"></div>'
                . '<table cellspacing="0" class="newline textgroup type2"><tbody>',),
            
            'wheelalignchecksteerwheelstraight' => array('label' => $this->__('Steering Wheel Straight'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("wheelalignchecksteerwheelstraight",$order),),
            'wheelalignchecksteerwheelstraightvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '', 
                'onchange' => "updateText(this.id);",           
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup type2"><tbody>',),
            'wheelaligncheckwehicleroadtested' => array('label' => $this->__('Vehicle Road Tested'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('wheelaligncheckwehicleroadtested');",
                'checked'  => $this->_isChecked("wheelaligncheckwehicleroadtested",$order),),
            'wheelaligncheckwehicleroadtestedvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '', 
                'onchange' => "updateText(this.id);",           
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup type2"><tbody>',),
            'wheelaligncheckcovermatremoved' => array('label' => $this->__('Seat Cover/Floor Mat Removed'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('wheelaligncheckcovermatremoved');",
                'checked'  => $this->_isChecked("wheelaligncheckcovermatremoved",$order),),
            'wheelaligncheckcovermatremovedvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '',  
                'onchange' => "updateText(this.id);",          
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup type2"><tbody>',),
            'wheelaligncheckstickerinstalled' => array('label' => $this->__('Windscreen Sticket Installed'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('wheelaligncheckstickerinstalled');",
                'checked'  => $this->_isChecked("wheelaligncheckstickerinstalled",$order),),
            'wheelaligncheckstickerinstalledvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '',  
                'onchange' => "updateText(this.id);",          
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline"><tbody>',),
            'wheelaligncheckcompletedby' => array('label' => $this->__('Inspection completed by'),'input' => 'text','required' => false,  
                'onchange' => "updateText(this.id);",          
                'after_element_html' => '</td></tr></tbody></table><div style="height: 20px;width: 99%;"></div>'
                . '<table cellspacing="0" class="newline textgroup type2"><tbody>',),
            
            'tyrecheckwheelnutstorqued' => array('label' => $this->__('Wheel Nuts Torqued'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('tyrecheckwheelnutstorqued');",
                'checked'  => $this->_isChecked("tyrecheckwheelnutstorqued",$order),),
            'tyrecheckwheelnutstorquedvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '',    
                'onchange' => "updateText(this.id);",        
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup type2"><tbody>',),
            'tyrecheckmetalvalvecapsfitted' => array('label' => $this->__('Metal Valve Caps Fitted'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('tyrecheckmetalvalvecapsfitted');",
                'checked'  => $this->_isChecked("tyrecheckmetalvalvecapsfitted",$order),),
            'tyrecheckmetalvalvecapsfittedvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '',  
                'onchange' => "updateText(this.id);",          
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup type2"><tbody>',),
            'tyrechecktyresglosssed' => array('label' => $this->__('Tyres Glossed'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('tyrechecktyresglosssed');",
                'checked'  => $this->_isChecked("tyrechecktyresglosssed",$order),),
            'tyrechecktyresglosssedvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '', 
                'onchange' => "updateText(this.id);",           
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup type2"><tbody>',),            
            'tyrecheckbatterystickerfitted' => array('label' => $this->__('Battery Sticker Fitted'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('tyrecheckbatterystickerfitted');",
                'checked'  => $this->_isChecked("tyrecheckbatterystickerfitted",$order),),
            'tyrecheckbatterystickerfittedvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '', 
                'onchange' => "updateText(this.id);",           
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup type2"><tbody>',),
            'tyrechecksparetyrestickerfitted' => array('label' => $this->__('Spare Tyre Sticker Fitted'),'input' => 'checkbox','required' => false,
                'onchange' => "updateCheck('tyrechecksparetyrestickerfitted');",
                'checked'  => $this->_isChecked("tyrechecksparetyrestickerfitted",$order),),
            'tyrechecksparetyrestickerfittedvalue' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => '', 
                'onchange' => "updateText(this.id);",           
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline"><tbody>',),
            
            'tyrecheckcompletedby' => array('label' => $this->__('Inspection completed by'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'batterychecktestresult' => array('label' => $this->__('TEST RESULT'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'batterycheckterminals' => array('label' => $this->__('TERMINALS'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            
            'visualbrakefrontcallipers' => array('label' => $this->__('Front Callipers/Wheel Cylinders'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'visualbrakefrontbrakes' => array('label' => $this->__('Front Brakes % Worn'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'visualbrakefrontdiscs' => array('label' => $this->__('Front Disc/Drums'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'visualbrakerearcallipers' => array('label' => $this->__('Rear Callipers/Wheel Cylinders'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'visualbrakerearbrakes' => array('label' => $this->__('Rear Brakes % Worn'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'visualbrakereardiscs' => array('label' => $this->__('Rear Discs/Drums'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'visualbrakerearflexiblehoses' => array('label' => $this->__('Flexible Hydraulic Brakes Hoses'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),


            /**
             * Note: we have not included created_at or updated_at.
             * We will handle those fields ourself in the model before saving.
             */
        ));

        //$requestData = $this->_data["order"];        
        //$form->getElement('vehiclewheelLF')->setIsChecked(!empty($requestData['vehiclewheelLF']));
        //$form->getElement('vehiclewheelRF')->setIsChecked(!empty($requestData['vehiclewheelRF']));
        //$form->getElement('vehiclewheelLR')->setIsChecked(!empty($requestData['vehiclewheelLR']));
        //$form->getElement('vehiclewheelRR')->setIsChecked(!empty($requestData['vehiclewheelRR']));
        
        //$this->_setfieldsData($form,'vehiclewheelLF');
        
        return $this;
    }
    
    protected function _setfieldsData($form, $fieldname) 
    {
        $form->getElement($fieldname)->setIsChecked(!empty($this->_data["order"]['vehiclewheelLF']));
    }
    
    protected function _isChecked($fieldname,$order)
    {
        $isChecked = "";
        //$fieldvalue = "";
        
        //if ($order instanceof ApdInteract_Vir_Model_Order) {
            //$order = Mage::getModel('apdinteract_vir/order');
            if(!empty($this->_data["order"])) {
                $fieldvalue = $order[$fieldname];
                if($fieldvalue == "1") {
                    $isChecked = "checked";
                }
            }
        //}        
        return $isChecked;
    }

    /**
     * This method makes life a little easier for us by pre-populating
     * fields with $_POST data where applicable and wrapping our post data
     * in 'orderData' so that we can easily separate all relevant information
     * in the controller. You could of course omit this method entirely
     * and call the $fieldset->addField() method directly.
     */
    protected function _addFieldsToFieldset(Varien_Data_Form_Element_Fieldset $fieldset, $fields)
    {
        $requestData = new Varien_Object($this->getRequest()
            ->getPost('orderData'));
        
        //$fieldset = $form->addFieldset('testsignature', array('legend' => $helper->__('Signature')));
        //$fieldset->addType('Signature', 'ApdInteract_Vir_Block_Adminhtml_Order_Edit_Form_Renderer_Fieldset_Signature');
        //$fieldset->addField('testsignature', 'Signature', array('label' => $helper->__('Test Signature'),'name' => 'testsignature'));
        
        foreach ($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }

            // Wrap all fields with orderData group.
            $_data['name'] = "orderData[$name]";

            // Generally, label and title are always the same.
            $_data['title'] = $_data['label'];
            
            //$this->addClass('input-text');
            $_data['class'] = $_data['class'].' test';

            // If no new value exists, use the existing order data.
            if (!array_key_exists('value', $_data)) {
                $_data['value'] = $this->_getOrder()->getData($name);
            }
            
            

            // Finally, call vanilla functionality to add field.
            $fieldset->addField($name, $_data['input'], $_data);
        }
        
//        $fieldset->addField('time', 'time', array(
//          'label'     => 'Time',
//          'class'     => 'test',
//          'required'  => false,
//          'name'      => 'title',
//          'onclick' => "",
//          'onchange' => "",
//          'value'  => '12,04,15',
//          'disabled' => false,
//          'readonly' => false,
//          'after_element_html' => '',
//          'tabindex' => 1
//        ));
//        
//        $fieldset->addField('date', 'date', array(
//          'label'     => 'Date',
//          'after_element_html' => '<small>Comments</small>',
//          'tabindex' => 1,
//          'image' => $this->getSkinUrl('images/grid-cal.gif'),
//          'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)        
//        ));

        return $this;
    }

    /**
     * Retrieve the existing order for pre-populating the form fields.
     * For a new order entry, this will return an empty order object.
     */
    protected function _getOrder()
    {
        if (!$this->hasData('order')) {
            // This will have been set in the controller.
            $order = Mage::registry('current_order');

            // Just in case the controller does not register the order.
            if (!$order instanceof
                    ApdInteract_Vir_Model_Order) {
                $order = Mage::getModel(
                    'apdinteract_vir/order'
                );
            }

            $this->setData('order', $order);
        }

        return $this->getData('order');
    }
}

