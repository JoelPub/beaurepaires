<?php

class Wyomind_Googleproductratings_Block_Adminhtml_System_Config_Form_Field_Cron extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) 
    {
        
        $html = "";

        $html .= "<input class=' input-text'  type='hidden' id='" . $element->getHtmlId() . "' name='" . $element->getName() . "' value='" . $element->getEscapedValue() . "' '" . $element->serialize($element->getHtmlAttributes()) . "/>";
        
        $html .= "
<script>
    document.observe('dom:loaded', function(){
       
        if(!$('" . $element->getHtmlId() . "').value.isJSON())$('" . $element->getHtmlId() . "').value='{\"days\":[],\"hours\":[]}';
         cron=$('" . $element->getHtmlId() . "').value.evalJSON();
       
        
        cron.days.each(function(d){
            if($('d-'+d)){
                $('d-'+d).checked=true;
                $('d-'+d).ancestors()[0].addClassName('checked');
            }
            
        })
        cron.hours.each(function(h){
            if( $('h-'+h.replace(':',''))){
                $('h-'+h.replace(':','')).checked=true;
                $('h-'+h.replace(':','')).ancestors()[0].addClassName('checked');
            }
        })
        
        $$('.cron-box').each(function(e){
            e.observe('click',function(){
                
                if(e.checked)e.ancestors()[0].addClassName('checked');
                else e.ancestors()[0].removeClassName('checked');
               
                d=new Array
                $$('.cron-d-box INPUT').each(function(e){
                    if(e.checked) d.push(e.value)
                })
                h=new Array;
                $$('.cron-h-box INPUT').each(function(e){
                    if(e.checked) h.push(e.value)
                })
                
                $('" . $element->getHtmlId() . "').value=Object.toJSON({days:d,hours:h})
               
            }) 
        })
    })
    
</script>
";

        $html .= "
<style>
    .morning .cron-h-box{
        border: 1px solid #AFAFAF;
        border-radius: 3px 3px 3px 3px;
        margin: 2px;
        padding: 0 3px;
        background:#efefef;
    }
    .afternoon .cron-h-box{
        border: 1px solid #AFAFAF;
        border-radius: 3px 3px 3px 3px;
        margin: 2px;
        padding: 0 3px;
        background:#efefef;
    }
    .morning-half .cron-h-box{
        border: 1px solid #AFAFAF;
        border-radius: 3px 3px 3px 3px;
        margin: 2px;
        padding: 0 3px;
        background:#efefef;
    }
    .afternoon-half .cron-h-box{

        border: 1px solid #AFAFAF;
        border-radius: 3px 3px 3px 3px;
        margin: 2px;
        padding: 0 3px;
        background:#efefef;
    }

    .cron-d-box{

        background:#efefef;
        border: 1px solid #AFAFAF;
        border-radius: 3px 3px 3px 3px;
        margin: 2px;
        padding: 0 3px;
    }
    .checked{
        background-color: #EFFFF0!important;
    }
</style>";


        $html .= "<table style='width:600px !important'>
            <thead> 
                <tr><th>Days of the week</th><th width='20'></th><th colspan='4'>Hours of the day</th></tr>
            </thead>
            <tr>
                <td width='300'>
                    <div class='cron-d-box'><input class='cron-box' value='Monday' id='d-Monday' type='checkbox'/> Monday</div>
                    <div class='cron-d-box'><input class='cron-box' value='Tuesday' id='d-Tuesday' type='checkbox'/> Tuesday</div>
                    <div class='cron-d-box'><input class='cron-box' value='Wednesday' id='d-Wednesday' type='checkbox'/> Wednesday</div>
                    <div class='cron-d-box'><input class='cron-box' value='Thursday' id='d-Thursday' type='checkbox'/> Thursday</div>
                    <div class='cron-d-box'><input class='cron-box' value='Friday' id='d-Friday' type='checkbox'/> Friday</div>
                    <div class='cron-d-box'><input class='cron-box' value='Saturday' id='d-Saturday' type='checkbox'/> Saturday</div>
                    <div class='cron-d-box'><input class='cron-box' value='Sunday' id='d-Sunday' type='checkbox'/> Sunday</div>
                </td>
                <td></td>
                <td width='150' class='morning-half'>
                    <div class='cron-h-box'><input class='cron-box' value='00:00' id='h-0000'  type='checkbox'/> 00:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='01:00' id='h-0100' type='checkbox'/> 01:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='02:00' id='h-0200' type='checkbox'/> 02:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='03:00' id='h-0300' type='checkbox'/> 03:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='04:00' id='h-0400' type='checkbox'/> 04:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='05:00' id='h-0500' type='checkbox'/> 05:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='06:00' id='h-0600'  type='checkbox'/> 06:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='07:00' id='h-0700' type='checkbox'/> 07:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='08:00' id='h-0800'  type='checkbox'/> 08:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='09:00' id='h-0900' type='checkbox'/> 09:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='10:00' id='h-1000' type='checkbox'/> 10:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='11:00' id='h-1100' type='checkbox'/> 11:00 AM</div>

                </td>
                <td width='150' class='morning'>
                    <div class='cron-h-box'><input class='cron-box' value='00:30' id='h-0030' type='checkbox'/> 00:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='01:30' id='h-0130' type='checkbox'/> 01:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='02:30' id='h-0230' type='checkbox'/> 02:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='03:30' id='h-0330' type='checkbox'/> 03:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='04:30' id='h-0430' type='checkbox'/> 04:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='05:30' id='h-0530' type='checkbox'/> 05:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='06:30' id='h-0630' type='checkbox'/> 06:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='07:30' id='h-0730' type='checkbox'/> 07:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='08:30' id='h-0830' type='checkbox'/> 08:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='09:30' id='h-0930' type='checkbox'/> 09:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='10:30' id='h-1030' type='checkbox'/> 10:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='11:30' id='h-1130' type='checkbox'/> 11:30 AM</div>




                </td>
                <td width='150' class='afternoon-half'>
                    <div class='cron-h-box'><input class='cron-box' value='12:00' id='h-1200' type='checkbox'/> 12:00 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='13:00' id='h-1300' type='checkbox'/> 01:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='14:00' id='h-1400' type='checkbox'/> 02:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='15:00' id='h-1500' type='checkbox'/> 03:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='16:00' id='h-1600' type='checkbox'/> 04:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='17:00' id='h-1700' type='checkbox'/> 05:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='18:00' id='h-1800' type='checkbox'/> 06:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='19:00' id='h-1900' type='checkbox'/> 07:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='20:00' id='h-2000' type='checkbox'/> 08:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='21:00' id='h-2100' type='checkbox'/> 09:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='22:00' id='h-2200' type='checkbox'/> 10:00 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='23:00' id='h-2300' type='checkbox'/> 11:00 PM</div>

                </td>
                <td width='150' class='afternoon'>
                    <div class='cron-h-box'><input class='cron-box' value='12:30' id='h-1230' type='checkbox'/> 12:30 AM</div>
                    <div class='cron-h-box'><input class='cron-box' value='13:30' id='h-1330' type='checkbox'/> 01:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='14:30' id='h-1430' type='checkbox'/> 02:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='15:30' id='h-1530' type='checkbox'/> 03:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='16:30' id='h-1630' type='checkbox'/> 04:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='17:30' id='h-1730' type='checkbox'/> 05:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='18:30' id='h-1830' type='checkbox'/> 06:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='19:30' id='h-1930' type='checkbox'/> 07:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='20:30' id='h-2030' type='checkbox'/> 08:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='21:30' id='h-2130' type='checkbox'/> 09:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='22:30' id='h-2230' type='checkbox'/> 10:30 PM</div>
                    <div class='cron-h-box'><input class='cron-box' value='23:30' id='h-2330' type='checkbox'/> 11:30 PM</div>


                </td>
            </tr>
        </table>";

        $html .= $element->getAfterElementHtml();
        return $html;
    }

}
