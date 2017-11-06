<?php
class ApdInteract_Vir_Block_Adminhtml_Ordercommercial_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        // Instantiate a new form to display our ordercommercial for editing.
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl(
                'apdinteract_vir_admin/ordercommercial/edit',
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
                'legend' => $this->__('Order Commercial Details')
            )
        );

        $ordercommercialSingleton = Mage::getSingleton(
            'apdinteract_vir/ordercommercial'
        );
        
        $order  = $this->_getOrdercommercial()->_data;

        // Add the fields that we want to be editable.
        $this->_addFieldsToFieldset($fieldset, array(
            //'vir_type' => array('label' => $this->__('vir_type'),'input' => 'text','required' => false,),
            'parent_id' => array('label' => '','input' => 'text','required' => false, 'class' => 'parent_id', 'name' => 'parent_id',
                'after_element_html' => ''),
            'Customerdetails' => array('label' => '','input' => 'label','required' => false, 'class' => 'header-label', 'name' => 'Customerdetails',
                'after_element_html' => '<script>dataname = "ordercommercial";</script>'
                . ''),
            'inspectiondate' => array('label' => 'Date','input' => 'text','required' => false, 'class' => 'datefieldhidden',
                'after_element_html' => '<input id="date1" name="inspectiondate[date1]" value="" class="datefield" title="Day" type="text" placeholder="DD" onchange="SaveDate(\'inspectiondate\');">'
                .'<span class="dateslash">/</span><input id="date2" name="inspectiondate[date2]" value="" class="datefield" title="Month" type="text" placeholder="MM" onchange="SaveDate(\'inspectiondate\');">'
                .'<span class="dateslash">/20</span><input id="date3" name="inspectiondate[date3]" value="" class="datefield" title="Year" '
                . 'type="text" placeholder="YY" onchange="SaveDate(\'inspectiondate\');") style="margin-left:0px;">'
                . '<span class="dateslash"> (DD/MM/YY) </span>'
                . '<script type="text/javascript">LoadDate(\'inspectiondate\');</script>',),
            //'inspectiondate' => array('label' => $this->__('inspectiondate'),'input' => 'text','required' => false,),
            'Invoicenumber' => array('label' => $this->__('Invoice number'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'baynumber' => array('label' => $this->__('Bay no'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'ordernumber' => array('label' => $this->__('order no'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'paymenttype' => array('label' => $this->__('Cash or Account'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'customername' => array('label' => $this->__('Customer'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);", 'class' => 'greyfield long',
                'after_element_html' => '<div id="customername_autocomplete_choices" class="autocomplete"></div>'),            
            'addressline' => array('label' => $this->__('Address'),'input' => 'text','required' => false, 'class' => 'greyfield long',
                'onchange' => "updateText(this.id);",),
            //'addressline1' => array('label' => $this->__('addressline1'),'input' => 'text','required' => false,),
            //'addressline2' => array('label' => $this->__('addressline2'),'input' => 'text','required' => false,),
            'suburb' => array('label' => $this->__('Suburb'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'phonenumber' => array('label' => $this->__('Phone'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'regonumber' => array('label' => $this->__('Rego No'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'fleetnumber' => array('label' => $this->__('Fleet No'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'speedohubreading' => array('label' => $this->__('Speedo/Hub'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",            
                'after_element_html' => '</td></tr></tbody></table><div style="height: 20px;width: 99%;"></div><table cellspacing="0" class="newline textgroup"><tbody>',),   
            // Row 1
            'space1' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'spacecheck',
                'after_element_html' => '<div class="spaceborder"> </div>',),
            'space2' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'spacecheck',
                'after_element_html' => '<div class="spaceborder"> </div>',),
            'vehiclewheelprime6' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime6",$order),
                'after_element_html' => '',),
            'vehiclewheelprime10' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime10",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera4' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera4",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera8' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera8",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera12' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera12",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb4' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb4",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb8' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb8",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb12' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',  
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb12",$order),          
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            
            //Row 2
            'vehiclewheel2' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheel2",$order),
                'after_element_html' => '',),
            'vehiclewheel2a' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheel2a",$order),
                'after_element_html' => '',),
            'vehiclewheelprime5' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime5",$order),
                'after_element_html' => '',),
            'vehiclewheelprime9' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime9",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera3' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera3",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera7' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera7",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera11' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera11",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb3' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb3",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb7' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb7",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb11' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb11",$order),           
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            // row 3
            'vehiclewheel1' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheel1",$order),
                'after_element_html' => '',),
            'vehiclewheel1a' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheel1a",$order),
                'after_element_html' => '',),
            'vehiclewheelprime4' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime4",$order),
                'after_element_html' => '',),
            'vehiclewheelprime8' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime8",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera2' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera2",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera6' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera6",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailera10' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera10",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb2' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb2",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb6' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb6",$order),
                'after_element_html' => '',),
            'vehiclewheeltrailerb10' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',   
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb10",$order),          
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            
            // Row 4
            'space3' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'spacecheck',
                'after_element_html' => '<div class="spaceborder"> </div>',),
            'space4' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'spacecheck',
                'after_element_html' => '<div class="spaceborder"> </div>',),
            //'vehicleaxleshow1' => array('label' => $this->__('vehicleaxleshow1'),'input' => 'checkbox','required' => false,),
            'vehiclewheelprime3' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime3",$order),
                'after_element_html' => '',),
            //'vehicleaxleshow2' => array('label' => $this->__('vehicleaxleshow2'),'input' => 'checkbox','required' => false,),
            'vehiclewheelprime7' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheelprime7",$order),
                'after_element_html' => '',),
            //'vehicleaxleshow3' => array('label' => $this->__('vehicleaxleshow3'),'input' => 'checkbox','required' => false,),
            'vehiclewheeltrailera1' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera1",$order),
                'after_element_html' => '',),
            //'vehicleaxleshow4' => array('label' => $this->__('vehicleaxleshow4'),'input' => 'checkbox','required' => false,),
            'vehiclewheeltrailera5' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera5",$order),
                'after_element_html' => '',),
            //'vehicleaxleshow5' => array('label' => $this->__('vehicleaxleshow5'),'input' => 'checkbox','required' => false,),
            'vehiclewheeltrailera9' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailera9",$order),
                'after_element_html' => '',),
            //'vehicleaxleshow6' => array('label' => $this->__('vehicleaxleshow6'),'input' => 'checkbox','required' => false,),
            'vehiclewheeltrailerb1' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb1",$order),
                'after_element_html' => '',),
            //'vehicleaxleshow7' => array('label' => $this->__('vehicleaxleshow7'),'input' => 'checkbox','required' => false,),
            'vehiclewheeltrailerb5' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck', 
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb5",$order),
                'after_element_html' => '',),
            //'vehicleaxleshow8' => array('label' => $this->__('vehicleaxleshow8'),'input' => 'checkbox','required' => false,),
            'vehiclewheeltrailerb9' => array('label' => $this->__(''),'input' => 'checkbox','required' => false,'class' => 'wheelcheck',    
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("vehiclewheeltrailerb9",$order),         
                'after_element_html' => '</td></tr></tbody>'
                . ''
                . '</table><table cellspacing="0" class="newline"><tbody>',),
            
            
            // New Grid
            'vehicledrawingimage' => array('label' => $this->__('vehicledrawingimage'),'input' => 'text','required' => false,    
                'onchange' => "updateText(this.id);",        
                'after_element_html' => '</td></tr></tbody></table>'
                . ''
                . '<table cellspacing="0" class="newline"><tbody>',),
            'workrequireddone' => array('label' => $this->__('workrequireddone'),'input' => 'text','required' => false,   
                'onchange' => "updateText(this.id);",         
                'after_element_html' => '</td></tr></tbody></table>'
                . '<div class="headinggroup"><div class="heading4"><br/></div><div class="heading4">Size</div><div class="heading4">Pattern</div><div class="heading2">QTY</div><div class="heading1">Comments</div></div>'
                . '<table cellspacing="0" class="newline textgroup"><tbody>',),
            'newsteersize' => array('label' => $this->__('newsteersize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'newsteerpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'newsteerqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'newsteercomments' => array('label' => $this->__(''),'input' => 'text','required' => false,            
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'newdrivesize' => array('label' => $this->__('newdrivesize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'newdrivepattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'newdriveqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'newdrivecomments' => array('label' => $this->__(''),'input' => 'text','required' => false,            
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'newtrailersize' => array('label' => $this->__('newtrailersize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'newtrailerpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'newtrailerqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'newtrailercomments' => array('label' => $this->__(''),'input' => 'text','required' => false,            
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'rdttrailersize' => array('label' => $this->__('rdttrailersize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rdttrailerpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rdttrailerqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'rdttrailercomments' => array('label' => $this->__(''),'input' => 'text','required' => false,            
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'rdtdrivesize' => array('label' => $this->__('rdtdrivesize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rdtdrivepattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rdtdriveqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'rdtdrivecomments' => array('label' => $this->__(''),'input' => 'text','required' => false,   
                'onchange' => "updateText(this.id);",         
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'casingsize' => array('label' => $this->__('casingsize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'casingpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'casingqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'casingcomments' => array('label' => $this->__(''),'input' => 'text','required' => false,            
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'puncturesize' => array('label' => $this->__('puncturesize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'puncturepattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'punctureqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'puncturecomments' => array('label' => $this->__(''),'input' => 'text','required' => false,   
                'onchange' => "updateText(this.id);",         
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'scrapsize' => array('label' => $this->__('scrapsize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'scrappattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'scrapqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'scrapcomments' => array('label' => $this->__(''),'input' => 'text','required' => false,   
                'onchange' => "updateText(this.id);",         
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'rotatesize' => array('label' => $this->__('rotatesize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rotatepattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rotateqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'rotatecomments' => array('label' => $this->__(''),'input' => 'text','required' => false,   
                'onchange' => "updateText(this.id);",         
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'tubesize' => array('label' => $this->__('tubesize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'tubepattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'tubeqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'tubecomments' => array('label' => $this->__(''),'input' => 'text','required' => false,            
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'fitsteelalloylttrksize' => array('label' => $this->__('fitsteelalloylttrksize'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",
                'class' => 'datefield longer',),
            'fitsteelalloylttrkpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'fitsteelalloylttrkqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'fitsteelalloylttrkcomments' => array('label' => $this->__(''),'input' => 'text','required' => false,     
                'onchange' => "updateText(this.id);",       
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',),
            'balancestalloyltpowdersize' => array('label' => $this->__('balancestalloyltpowdersize'),'input' => 'text',
                'onchange' => "updateText(this.id);",
                'required' => false,'class' => 'datefield longer',),
            'balancestalloyltpowderpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'balancestalloyltpowderqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'balancestalloyltpowdercomments' => array('label' => $this->__(''),'input' => 'text','required' => false,  
                'onchange' => "updateText(this.id);",          
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'rustbandsize' => array('label' => $this->__('rustbandsize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rustbandpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'rustbandqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'rustbandcomments' => array('label' => $this->__(''),'input' => 'text','required' => false,   
                'onchange' => "updateText(this.id);",         
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'valvestemsize' => array('label' => $this->__('valvestemsize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'valvestempattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'valvestemqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'valvestemcomments' => array('label' => $this->__(''),'input' => 'text','required' => false,    
                'onchange' => "updateText(this.id);",        
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline textgroup"><tbody>',
                'onchange' => "updateText(this.id);",),
            'valveextsize' => array('label' => $this->__('valveextsize'),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'valveextpattern' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield longer',
                'onchange' => "updateText(this.id);",),
            'valveextqty' => array('label' => $this->__(''),'input' => 'text','required' => false,'class' => 'datefield',
                'onchange' => "updateText(this.id);",),
            'valveextcomments' => array('label' => $this->__(''),'input' => 'text','required' => false,    
                'onchange' => "updateText(this.id);",        
                'after_element_html' => '</td></tr></tbody></table><table cellspacing="0" class="newline"><tbody>',),
            'workperformedandcheckedby' => array('label' => $this->__('Work Performed & Checked by'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'customeralertnuts' => array('label' => $this->__('Wheel nuts have been tensioned with a tension wrench'),
                'input' => 'checkbox','required' => false,  
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("customeralertnuts",$order),),
            'nutstentionedby' => array('label' => $this->__('Nuts tensioned by'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'nutstentionedbysignatureimage' => array('label' => $this->__('nutstentionedbysignatureimage'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'customeralertothernuts' => array('label' => $this->__('customeralertothernuts'),
                'input' => 'checkbox','required' => false,  
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("customeralertothernuts",$order),),
            'customeralertforkliftnuts' => array('label' => $this->__('customeralertforkliftnuts'),
                'input' => 'checkbox','required' => false,  
                'onchange' => "updateCheck(this.id);",
                'checked'  => $this->_isChecked("customeralertforkliftnuts",$order),),
            'customeralertothernutssignatureimage' => array('label' => $this->__('customeralertothernutssignatureimage'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),
            'customersignatureimage' => array('label' => $this->__('customersignatureimage'),'input' => 'text','required' => false,
                'onchange' => "updateText(this.id);",),


            /**
             * Note: we have not included created_at or updated_at.
             * We will handle those fields ourself in the model
       * before saving.
             */
        ));

        return $this;
    }
    
    protected function _isChecked($fieldname,$order)
    {
        $isChecked = "";
        
        //if ($order instanceof ApdInteract_Vir_Model_Order) {
            //$order = Mage::getModel('apdinteract_vir/order');
            if(!empty($this->_data["ordercommercial"])) {
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
     * in 'ordercommercialData' so that we can easily separate all relevant information
     * in the controller. You could of course omit this method entirely
     * and call the $fieldset->addField() method directly.
     */
    protected function _addFieldsToFieldset(
        Varien_Data_Form_Element_Fieldset $fieldset, $fields)
    {
        $requestData = new Varien_Object($this->getRequest()
            ->getPost('ordercommercialData'));

        foreach ($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }

            // Wrap all fields with ordercommercialData group.
            $_data['name'] = "ordercommercialData[$name]";

            // Generally, label and title are always the same.
            $_data['title'] = $_data['label'];

            // If no new value exists, use the existing ordercommercial data.
            if (!array_key_exists('value', $_data)) {
                $_data['value'] = $this->_getOrdercommercial()->getData($name);
            }

            // Finally, call vanilla functionality to add field.
            $fieldset->addField($name, $_data['input'], $_data);
        }

        return $this;
    }

    /**
     * Retrieve the existing ordercommercial for pre-populating the form fields.
     * For a new ordercommercial entry, this will return an empty ordercommercial object.
     */
    protected function _getOrdercommercial()
    {
        if (!$this->hasData('ordercommercial')) {
            // This will have been set in the controller.
            $ordercommercial = Mage::registry('current_ordercommercial');

            // Just in case the controller does not register the ordercommercial.
            if (!$ordercommercial instanceof
                    ApdInteract_Vir_Model_Ordercommercial) {
                $ordercommercial = Mage::getModel(
                    'apdinteract_vir/ordercommercial'
                );
            }

            $this->setData('ordercommercial', $ordercommercial);
        }

        return $this->getData('ordercommercial');
    }
}

