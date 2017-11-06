<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Vircommercial
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Vircommercial extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     * 
     * @param Mage_Customer_Model_Lead $input
     */
    public function map($input) {

        $result['Address_Line_1__c'] = $input['addressline1'];
        $result['Address_Line_2__c'] = $input['addressline2'];
        $result['Balancestalloyltpowdercomments__c'] = $input['balancestalloyltpowdercomments'];
        $result['Balancestalloyltpowderpattern__c'] = $input['balancestalloyltpowderpattern'];
        $result['Balancestalloyltpowderqty__c'] = $input['balancestalloyltpowderqty'];
        $result['Balancestalloyltpowdersize__c'] = $input['balancestalloyltpowdersize'];
        $result['Bay_Number__c'] = $input['baynumber'];
        $result['Casing_Comments__c'] = $input['casingcomments'];
        $result['Casing_Pattern__c'] = $input['casingpattern'];
        $result['Casing_Qty__c'] = $input['casingqty'];
        $result['Casing_Size__c'] = $input['casingsize'];
        $result['Customer_Alert_Forklift_Nuts__c'] = $input['customeralertforkliftnuts'];
        $result['Customer_Alert_Nuts__c'] = $input['customeralertnuts'];
        $result['Customer_Alert_Other_Nuts__c'] = $input['customeralertothernuts'];
        $result['Customer_Name__c'] = $input['customername'];
        $result['Fitsteelalloylttrkcomments__c'] = $input['fitsteelalloylttrkcomments'];
        $result['Fitsteelalloylttrkpattern__c'] = $input['fitsteelalloylttrkpattern'];
        $result['Fitsteelalloylttrkqty__c'] = $input['fitsteelalloylttrkqty'];
        $result['Fitsteelalloylttrksize__c'] = $input['fitsteelalloylttrksize'];
        $result['Fleet_Number__c'] = $input['fleetnumber'];
        $result['Inspection_Date__c'] = date("c",strtotime($input['inspectiondate']));
        $result['Invoice_Number__c'] = $input['Invoicenumber'];
        $result['Magento_VIR_Commercial_ID__c'] = $input['parent_id'];
        $result['New_Drive_Comments__c'] = $input['newdrivecomments'];
        $result['New_Drive_Pattern__c'] = $input['newdrivepattern'];
        $result['New_Drive_Qty__c'] = $input['newdriveqty'];
        $result['New_Drive_Size__c'] = $input['newdrivesize'];
        $result['New_Steer_Comments__c'] = $input['newsteercomments'];
        $result['New_Steer_Pattern__c'] = $input['newsteerpattern'];
        $result['New_Steer_Qty__c'] = $input['newsteerqty'];
        $result['New_Steers_Size__c'] = $input['newsteersize'];
        $result['New_Trailer_Comments__c'] = $input['newtrailercomments'];
        $result['New_Trailer_Pattern__c'] = $input['newtrailerpattern'];
        $result['New_Trailer_Qty__c'] = $input['newtrailerqty'];
        $result['New_Trailer_Size__c'] = $input['newtrailersize'];
        $result['Nutstentioned_By__c'] = $input['nutstentionedby'];
        $result['Order_Number__c'] = $input['ordernumber'];
        $result['Payment_Type__c'] = $input['paymenttype'];
        $result['Phone_Mobile__c'] = $input['phonemobile'];
        $result['Phone_Number__c'] = $input['phonenumber'];
        $result['Postcode__c'] = $input['postcode'];
        $result['Puncture_Comments__c'] = $input['puncturecomments'];
        $result['Puncture_Pattern__c'] = $input['puncturepattern'];
        $result['Puncture_Qty__c'] = $input['punctureqty'];
        $result['Puncture_Size__c'] = $input['puncturesize'];
        $result['Rdt_Drive_Comments__c'] = $input['rdtdrivecomments'];
        $result['Rdt_Drive_Pattern__c'] = $input['rdtdrivepattern'];
        $result['Rdt_Drive_Qty__c'] = $input['rdtdriveqty'];
        $result['Rdt_Drive_Size__c'] = $input['rdtdrivesize'];
        $result['Rdt_Trailer_Comments__c'] = $input['rdttrailercomments'];
        $result['Rdt_Trailer_Pattern__c'] = $input['rdttrailerpattern'];
        $result['Rdt_Trailer_Qty__c'] = $input['rdttrailerqty'];
        $result['Rdt_Trailer_Size__c'] = $input['rdttrailersize'];
        $result['Rego_Number__c'] = $input['regonumber'];
        $result['Rotate_Comments__c'] = $input['rotatecomments'];
        $result['Rotate_Pattern__c'] = $input['rotatepattern'];
        $result['Rotate_Qty__c'] = $input['rotateqty'];
        $result['Rotate_Size__c'] = $input['rotatesize'];
        $result['Rust_Band_Comments__c'] = $input['rustbandcomments'];
        $result['Rust_Band_Pattern__c'] = $input['rustbandpattern'];
        $result['Rust_Band_Qty__c'] = $input['rustbandqty'];
        $result['Rust_Band_Size__c'] = $input['rustbandsize'];
        $result['Scrap_Comments__c'] = $input['scrapcomments'];
        $result['Scrap_Pattern__c'] = $input['scrappattern'];
        $result['Scrap_Qty__c'] = $input['scrapqty'];
        $result['Scrap_Size__c'] = $input['scrapsize'];
        
        if($input['sketch_container']!='')
        $result['Sketch_Container__c'] = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'vir/view/index/type/commercial/image/sketch_container/id/'.$input['parent_id'];
        
        if($input['customersignatureimage']!='')
        $result['customersignatureimage__c'] = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'vir/view/index/type/commercial/image/customersignatureimage/id/'.$input['parent_id'];
            
        $result['Speedo_Hubreading__c'] = $input['speedohubreading'];
        $result['State__c'] = $input['state'];
        $result['Status__c'] = $input['status'];
        $result['Suburb__c'] = $input['suburb'];
        $result['Tube_Comments__c'] = $input['tubecomments'];
        $result['Tube_Pattern__c'] = $input['tubepattern'];
        $result['Tube_Qty__c'] = $input['tubeqty'];
        $result['Tube_Size__c'] = $input['tubesize'];
        $result['Valveext_Comments__c'] = $input['valveextcomments'];
        $result['Valveext_Pattern__c'] = $input['valveextpattern'];
        $result['Valveext_Qty__c'] = $input['valveextqty'];
        $result['Valveext_Size__c'] = $input['valveextsize'];
        $result['Valvestem_Comments__c'] = $input['valvestemcomments'];
        $result['Valvestem_Pattern__c'] = $input['valvestempattern'];
        $result['Valvestem_Qty__c'] = $input['valvestemqty'];
        $result['Valvestem_Size__c'] = $input['valvestemsize'];
        $result['WheelA1__c'] = $this->_getBooleanValue($input['wheela1']);
        $result['WheelA10__c'] = $this->_getBooleanValue($input['wheela10']);
        $result['WheelA11__c'] = $this->_getBooleanValue($input['wheela11']);
        $result['WheelA12__c'] = $this->_getBooleanValue($input['wheela12']);
        $result['WheelA2__c'] = $this->_getBooleanValue($input['wheela2']);
        $result['WheelA3__c'] = $this->_getBooleanValue($input['wheela3']);
        $result['WheelA4__c'] = $this->_getBooleanValue($input['wheela4']);
        $result['WheelA5__c'] = $this->_getBooleanValue($input['wheela5']);
        $result['WheelA6__c'] = $this->_getBooleanValue($input['wheela6']);
        $result['WheelA7__c'] = $this->_getBooleanValue($input['wheela7']);
        $result['WheelA8__c'] = $this->_getBooleanValue($input['wheela8']);
        $result['WheelA9__c'] = $this->_getBooleanValue($input['wheela9']);
        $result['WheelB1__c'] = $this->_getBooleanValue($input['wheelb1']);
        $result['WheelB10__c'] = $this->_getBooleanValue($input['wheelb10']);
        $result['WheelB11__c'] = $this->_getBooleanValue($input['wheelb11']);
        $result['WheelB12__c'] = $this->_getBooleanValue($input['wheelb12']);
        $result['WheelB2__c'] = $this->_getBooleanValue($input['wheelb2']);
        $result['WheelB3__c'] = $this->_getBooleanValue($input['wheelb3']);
        $result['WheelB4__c'] = $this->_getBooleanValue($input['wheelb4']);
        $result['WheelB5__c'] = $this->_getBooleanValue($input['wheelb5']);
        $result['WheelB6__c'] = $this->_getBooleanValue($input['wheelb6']);
        $result['WheelB7__c'] = $this->_getBooleanValue($input['wheelb7']);
        $result['WheelB8__c'] = $this->_getBooleanValue($input['wheelb8']);
        $result['WheelB9__c'] = $this->_getBooleanValue($input['wheelb9']);
        $result['WheelP1__c'] = $this->_getBooleanValue($input['wheelp1']);
        $result['WheelP10__c'] = $this->_getBooleanValue($input['wheelp10']);
        $result['WheelP1a__c'] = $this->_getBooleanValue($input['wheelp1a']);
        $result['WheelP2__c'] = $this->_getBooleanValue($input['wheelp2']);
        $result['WheelP2a__c'] =$this->_getBooleanValue($input['wheelp2a']);
        $result['WheelP3__c'] = $this->_getBooleanValue($input['wheelp3']);
        $result['WheelP4__c'] = $this->_getBooleanValue($input['wheelp4']);
        $result['WheelP6__c'] = $this->_getBooleanValue($input['wheelp6']);
        $result['WheelP7__c'] = $this->_getBooleanValue($input['wheelp7']);
        $result['WheelP8__c'] = $this->_getBooleanValue($input['wheelp8']);
        $result['WheelP9__c'] = $this->_getBooleanValue($input['wheelp9']);
        $result['WheelP5__c'] = $this->_getBooleanValue($input['wheelp5']);
        $result['Work_Performed_And_Checked_By__c'] = $input['workperformedandcheckedby'];
        $result['Work_Require_Done__c'] = $input['workrequireddone'];



        $id = $this->getAccountSalesforceId($input['customerid']);
        $vir_id = $this->getVirCommercialSalesforceId($input['parent_id']);

        $result["Customer__c"] = $id;
        $result["salesforce_id"] = $vir_id;
        
        //var_dump($result);
        //exit();

        return $result;
    }

    public function getAccountSalesforceId($cid) {
        $customer = Mage::getModel("customer/customer")->load($cid);
        $dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
                ->getCollection()
                ->getDictionaryByModel($customer);
        return $dictionary->getData("salesforce_id");
    }

    public function getVirCommercialSalesforceId($vir_id) {
        $ordercommercial = Mage::getModel('apdinteract_vir/ordercommercial')->load($vir_id);
        $dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
                ->getCollection()
                ->getDictionaryByModel($ordercommercial);
        return $dictionary->getData("salesforce_id");
    }
    
    private function _getBooleanValue($input) {
        $array = array(false,true);
        return $array[$input];
    }

}
