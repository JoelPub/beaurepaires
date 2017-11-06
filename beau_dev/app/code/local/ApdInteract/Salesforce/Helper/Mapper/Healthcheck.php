<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Lead
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Healthcheck extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     * 
     * @param Mage_Customer_Model_Lead $input
     */
    public function map($input) {
        
                $order_date = date("c", strtotime($input['date'] . " " . $input['time']));
		$result['Date_Time__c'] = $order_date;
		$result['Customer_Name__c'] = $input['customer_name'];
		$result['Store_Name__c'] = $input['store_name'];
		$result['Store_Manager__c'] = $input['store_manager'];
		$result['Vehicle_Registration__c'] = $input['vehicle_registration'];
		$result['Vehicle_Make__c'] = $input['vehicle_make'];
		$result['Vehicle_Model__c'] = $input['vehicle_model'];
		$result['Odometer__c'] = $input['odometer'];
		$result['LF_Tread_Depth__c'] = $input['lf_tread_depth'];
		$result['LF_Tyre_Pressure__c'] = $input['lf_tyre_pressure'];
		$result['LF_Tread_Wear__c'] = $input['lf_treadwear'];
		$result['LR_Tread_Depth__c'] = $input['lr_tread_depth'];
		$result['LR_Tyre_Pressure__c'] = $input['lr_tyre_pressure'];
		$result['LR_Tread_Wear__c'] = $input['lr_treadwear'];
		$result['Spare_Tread_Depth__c'] = $input['spare_tread_depth'];
		$result['Spare_Tyre_Pressure__c'] = $input['spare_tyre_pressure'];
		$result['Spare_Tread_Wear__c'] = $input['spare_treadwear'];
		$result['RF_Tread_Depth__c'] = $input['rf_tread_depth'];
		$result['RF_Tyre_Pressure__c'] = $input['rf_tyre_pressure'];
		$result['RF_Tread_Wear__c'] = $input['rf_treadwear'];
		$result['RR_Tread_Depth__c'] = $input['rr_tread_depth'];
		$result['RR_Tyre_Pressure__c'] = $input['rr_tyre_pressure'];
		$result['RR_Tread_Wear__c'] = $input['rr_treadwear'];
		$result['Comments__c'] = $input['comments'];
		$result['Magento_ID__c'] = $input['healthcheckid'];

		/*Radio Buttons*/
		$result['LF_has_six_years__c'] = $this->_convertToBoolean($input['lf_hassixyears']);
		$result['LR_has_six_years__c'] = $this->_convertToBoolean($input['lr_hassixyears']);
		$result['Spare_has_six_years__c'] = $this->_convertToBoolean($input['spare_hassixyears']);
		$result['Spare_has_six_years__c'] = $this->_convertToBoolean($input['rf_hassixyears']);
		$result['RR_has_six_years__c'] = $this->_convertToBoolean($input['rr_hassixyears']);
		$result['Battery__c'] = $this->_convertToBoolean($input['battery']);
		$result['Wiper_Blades__c'] = $this->_convertToBoolean($input['wiper_blades']);
		$result['Steering__c'] = $this->_convertToBoolean($input['steering']);
		$result['Brake_Lights__c'] = $this->_convertToBoolean($input['brake_lights']);
		$result['Indicator_Lights__c'] = $this->_convertToBoolean($input['indicator_lights']);
		$result['Head_Lights__c'] = $this->_convertToBoolean($input['head_lights']);
		$result['Interior_Lights__c'] = $this->_convertToBoolean($input['interior_lights']);
		$result['Boot_lights__c'] = $this->_convertToBoolean($input['boot_lights']);
		$result['Tail_Lights__c'] = $this->_convertToBoolean($input['tail_lights']);
		$result['Hazard_Lights__c'] = $this->_convertToBoolean($input['hazard_lights']);
		$result['Front_Callipers__c'] = $this->_convertToBoolean($input['front_callipers']);
		$result['Front_Brakes__c'] = $this->_convertToBoolean($input['front_brakes']);
		$result['Front_Discs__c'] = $this->_convertToBoolean($input['front_discs']);
		$result['Rear_Callipers__c'] = $this->_convertToBoolean($input['rear_callipers']);
		$result['Rear_Brakes__c'] = $this->_convertToBoolean($input['rear_brakes']);
		$result['Rear_Discs__c'] = $this->_convertToBoolean($input['rear_discs']);
		$result['Flexible_Hydraulic_Brakes_Hoses__c'] = $this->_convertToBoolean($input['flexible_hydraulic_brakes_hoses']);

        
                $vir_id = $this->getVirConsumerSalesforceId($input['vir_id']);

                $result["VIR_Consumer_Sf_ID__c"] = $vir_id;
                $result['salesforce_id'] = $input['salesforce_id'];

        return $result;
    }

    public function getAccountSalesforceId($cid) {
        $customer = Mage::getModel("customer/customer")->load($cid);
        $dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
                ->getCollection()
                ->getDictionaryByModel($customer);
        return $dictionary->getData("salesforce_id");
    }

    public function getVirConsumerSalesforceId($vir_id) {
        $consumer = Mage::getModel('apdinteract_vir/order')->load($vir_id);
        $dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
                ->getCollection()
                ->getDictionaryByModel($consumer);
        return $dictionary->getData("salesforce_id");
    }
    
    private function _convertToBoolean($value) {
        return $value==1? true : false;
    }

}
