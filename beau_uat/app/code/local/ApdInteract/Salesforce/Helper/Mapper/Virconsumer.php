<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Lead
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Virconsumer extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     * 
     * @param Mage_Customer_Model_Lead $input
     */
    public function map($input) {
        
        $order_date = date("c", strtotime($input['orderdate'] . " " . $input['timereq']));
        $result['batterycheckterminals__c'] = $input['batterycheckterminals'];
        $result['batterychecktestresult__c'] = $input['batterychecktestresult'];
        $result['Cust_Appr_Call__c'] = $input['custapprcall'];
        $result['Cust_Appr_Collect_Data__c'] = $input['custapprcollectdata'];
        $result['Cust_Appr_Pay_Account__c'] = $input['custapprpayaccount'];
        $result['Cust_Appr_Pay_Amexdiners__c'] = $input['custapprpayamexdiners'];
        $result['Cust_Appr_Pay_cash__c'] = $input['custapprpaycash'];
        $result['Cust_Appr_Pay_Eftpos__c'] = $input['custapprpayeftpos'];
        $result['Cust_Appr_Pay_Finance__c'] = $input['custapprpayfinance'];
        $result['Cust_Appr_Pay_Other__c'] = $input['custapprpayother'];
        $result['Cust_Appr_Pay_Visamaster__c'] = $input['custapprpayvisamaster'];
        $result['Cust_Appr_Subscribe__c'] = $input['custapprsubscribe'];
        $result['Customer_Address__c'] = $input['custaddress'];
        $result['Customer_Mail__c'] = $input['custemail'];
        $result['Customer_Name__c'] = $input['custname'];
        $result['Customer_Phone_Mobile__c'] = $input['custphonemobile'];
        $result['Customer_Phone_Number__c'] = $input['custphoneno'];
        $result['Customer_Postcode__c'] = $input['custpostcode'];
        $result['Customer_State__c'] = $input['custstate'];
        $result['Customer_Suburb__c'] = $input['custsuburb'];
        $result['Invoice_Number__c'] = $input['invoiceno'];

        if ($input['lastbalance'] == null)
            $result['Last_Balance__c'] = '0';
        else
            $result['Last_Balance__c'] = $input['lastbalance'];

        if ($input['lastwheelalignment'] == null)
            $result['Last_Wheel_Alignment__c'] = '0';
        else
            $result['Last_Wheel_Alignment__c'] = $input['lastwheelalignment'];

        $result['Magento_VIR_ID__c'] = $input['parent_id'];
        $result['Order_Date__c'] = $order_date;
        $result['Order_Number__c'] = $input['ordernumber'];
        $result['Status__c'] = $input['status'];
        if ($input['timereq'] != '00:00:00')
            $result['Time_Request__c'] = $order_date;
        $result['tyrecheckbatterystickerfitted__c'] = $input['tyrecheckbatterystickerfitted'];
        $result['tyrecheckbatterystickerfittedvalue__c'] = $input['tyrecheckbatterystickerfittedvalue'];
        $result['tyrecheckcompletedby__c'] = $input['tyrecheckcompletedby'];
        $result['tyrecheckmetalvalvecapsfitted__c'] = $input['tyrecheckmetalvalvecapsfitted'];
        $result['tyrecheckmetalvalvecapsfittedvalue__c'] = $input['tyrecheckmetalvalvecapsfittedvalue'];
        $result['tyrechecksparetyrestickerfitted__c'] = $input['tyrechecksparetyrestickerfitted'];
        $result['tyrechecksparetyrestickerfittedvalue__c'] = $input['tyrechecksparetyrestickerfittedvalue'];
        $result['tyrechecktyresglosssed__c'] = $input['tyrechecktyresglosssed'];
        $result['tyrechecktyresglosssedvalue__c'] = $input['tyrechecktyresglosssedvalue'];
        $result['tyrecheckwheelnutstorqued__c'] = $input['tyrecheckwheelnutstorqued'];
        $result['tyrecheckwheelnutstorquedvalue__c'] = $input['tyrecheckwheelnutstorquedvalue'];
        $result['Vehicle_Comments__c'] = $input['vehiclecomments'];
        $result['Vehicle_Comments_Image__c'] = $input['vehiclecommentsimage'];
        $result['Vehicle_Details__c'] = $input['vehicledetails'];
        $result['Vehicle_Dometer__c'] = $input['vehicleodometer'];
        $result['Vehicle_Make__c'] = $input['vehiclemake'];
        $result['Vehicle_Model__c'] = $input['vehiclemodel'];
        $result['Vehicle_Rego__c'] = $input['vehiclerego'];
        $result['Vehicle_Series__c'] = $input['vehicleseries'];

        if ($input['vehiclewheelLF'] != null)
            $result['Vehicle_Wheel_LF__c'] = 1;

        $result['Vehicle_Wheel_LF_Torque__c'] = $input['vehiclewheelLFtorque'];

        if ($input['vehiclewheelLR'] != null)
            $result['Vehicle_Wheel_LR__c'] = 1;

        $result['Vehicle_Wheel_LR_Torque__c'] = $input['vehiclewheelLRtorque'];

        if ($input['vehiclewheelRF'] != null)
            $result['Vehicle_Wheel_RF__c'] = 1;

        $result['Vehicle_Wheel_RF_Torque__c'] = $input['vehiclewheelRFtorque'];

        if ($input['vehiclewheelRR'] != null)
            $result['Vehicle_Wheel_RR__c'] = 1;

        $result['Vehicle_Wheel_RR_Torque__c'] = $input['vehiclewheelRRtorque'];

        if ($input['vehiclewheelSPARE'] != null)
            $result['Vehicle_Wheel_SPARE__c'] = 1;

        $result['Vehicle_Wheel_SPARE_Torque__c'] = $input['vehiclewheelSPAREtorque'];
        $result['Vehicle_Year__c'] = $input['vehicleyear'];
        $result['VIR_Type_ID__c'] = $input['vir_type'];
        $result['visualbrakefrontbrakes__c'] = $input['visualbrakefrontbrakes'];
        $result['visualbrakefrontcallipers__c'] = $input['visualbrakefrontcallipers'];
        $result['visualbrakefrontdiscs__c'] = $input['visualbrakefrontdiscs'];
        $result['visualbrakerearbrakes__c'] = $input['visualbrakerearbrakes'];
        $result['visualbrakerearcallipers__c'] = $input['visualbrakerearcallipers'];
        $result['visualbrakereardiscs__c'] = $input['visualbrakereardiscs'];
        $result['visualbrakerearflexiblehoses__c'] = $input['visualbrakerearflexiblehoses'];
        $result['wheelaligncheckcompletedby__c'] = $input['wheelaligncheckcompletedby'];
        $result['wheelaligncheckcovermatremoved__c'] = $input['wheelaligncheckcovermatremoved'];
        $result['wheelaligncheckcovermatremovedvalue__c'] = $input['wheelaligncheckcovermatremovedvalue'];
        $result['Wheel_Align_Check_Steer_Wheel_Straight__c'] = $input['wheelalignchecksteerwheelstraight'];
        $result['WheelAlignCheckSteerWheelStraightValue__c'] = $input['wheelalignchecksteerwheelstraightvalue'];
        $result['wheelaligncheckstickerinstalled__c'] = $input['wheelaligncheckstickerinstalled'];
        $result['wheelaligncheckstickerinstalledvalue__c'] = $input['wheelaligncheckstickerinstalledvalue'];
        $result['wheelaligncheckwehicleroadtested__c'] = $input['wheelaligncheckwehicleroadtested'];
        $result['wheelaligncheckwehicleroadtestedvalue__c'] = $input['wheelaligncheckwehicleroadtestedvalue'];
        $result['Wheel_Align_Front_After_Left_Camber__c'] = $input['wheelalignfrontafterleftcamber'];
        $result['Wheel_Align_Front_After_Left_Camber2__c'] = $input['wheelalignfrontafterleftcamber2'];
        $result['Wheel_Align_Front_After_Left_Caster__c'] = $input['wheelalignfrontafterleftcaster'];
        $result['Wheel_Align_Front_After_Left_Caster2__c'] = $input['wheelalignfrontafterleftcaster2'];
        $result['Wheel_Align_Front_After_Left_Toein__c'] = $input['wheelalignfrontafterlefttoein'];
        $result['Wheel_Align_Front_After_Left_Toein2__c'] = $input['wheelalignfrontafterlefttoein2'];
        $result['Wheel_Align_Front_After_Right_Camber__c'] = $input['wheelalignfrontafterrightcamber'];
        $result['Wheel_Align_Front_After_Right_Camber2__c'] = $input['wheelalignfrontafterrightcamber2'];
        $result['Wheel_Align_Front_After_Right_Caster__c'] = $input['wheelalignfrontafterrightcaster'];
        $result['Wheel_Align_Front_After_Right_Caster2__c'] = $input['wheelalignfrontafterrightcaster2'];
        $result['Wheel_Align_Front_After_Right_Toein__c'] = $input['wheelalignfrontafterrighttoein'];
        $result['Wheel_Align_Front_After_Right_Toein2__c'] = $input['wheelalignfrontafterrighttoein2'];
        $result['Wheel_Align_Front_Before_Left_Camber__c'] = $input['wheelalignfrontbeforeleftcamber'];
        $result['Wheel_Align_Front_Before_Left_Camber2__c'] = $input['wheelalignfrontbeforeleftcamber2'];
        $result['Wheel_Align_Front_Before_Left_Caster__c'] = $input['wheelalignfrontbeforeleftcaster'];
        $result['Wheel_Align_Front_Before_Left_Caster2__c'] = $input['wheelalignfrontbeforeleftcaster2'];
        $result['Wheel_Align_Front_Before_Left_Toein__c'] = $input['wheelalignfrontbeforerighttoein'];
        $result['Wheel_Align_Front_Before_Left_Toein2__c'] = $input['wheelalignfrontbeforerighttoein2'];
        $result['Wheel_Align_Front_Before_Right_Camber__c'] = $input['wheelalignfrontbeforerightcamber'];
        $result['Wheel_Align_Front_Before_Right_Camber2__c'] = $input['wheelalignfrontbeforerightcamber2'];
        $result['Wheel_Align_Front_Before_Right_Caster__c'] = $input['wheelalignfrontbeforerightcaster'];
        $result['Wheel_Align_Front_Before_Right_Caster2__c'] = $input['wheelalignfrontbeforerightcaster2'];
        $result['Wheel_Align_Front_Before_Right_Toein__c'] = $input['wheelalignfrontbeforerighttoein'];
        $result['Wheel_Align_Front_Before_Right_Toein2__c'] = $input['wheelalignfrontbeforerighttoein2'];
        $result['Wheel_Align_Near_After_Left_Camber__c'] = $input['wheelalignrearafterleftcamber'];
        $result['Wheel_Align_Near_After_Left_Camber2__c'] = $input['wheelalignrearafterleftcamber2'];
        $result['Wheel_Align_Near_After_Left_Caster__c'] = $input['wheelalignrearafterleftcaster'];
        $result['Wheel_Align_Near_After_Left_Caster2__c'] = $input['wheelalignrearafterleftcaster2'];
        $result['Wheel_Align_Near_After_Left_Toein__c'] = $input['wheelalignrearafterlefttoein'];
        $result['Wheel_Align_Near_After_Left_Toein2__c'] = $input['wheelalignrearafterlefttoein2'];
        $result['Wheel_Align_Near_After_Right_Camber__c'] = $input['wheelalignrearafterrightcamber'];
        $result['Wheel_Align_Near_After_Right_Camber2__c'] = $input['wheelalignrearafterrightcamber2'];
        $result['Wheel_Align_Near_After_Right_Caster__c'] = $input['wheelalignrearafterrightcaster'];
        $result['Wheel_Align_Near_After_Right_Caster2__c'] = $input['wheelalignrearafterrightcaster2'];
        $result['Wheel_Align_Near_After_Right_Toein__c'] = $input['wheelalignrearafterrighttoein'];
        $result['Wheel_Align_Near_After_Right_Toein2__c'] = $input['wheelalignrearafterrighttoein2'];
        $result['Wheel_Align_Near_Before_Left_Camber__c'] = $input['wheelalignrearbeforeleftcamber'];
        $result['Wheel_Align_Near_Before_Left_Camber2__c'] = $input['wheelalignrearbeforeleftcamber2'];
        $result['Wheel_Align_Near_Before_Left_Caster__c'] = $input['wheelalignrearbeforeleftcaster'];
        $result['Wheel_Align_Near_Before_Left_Caster2__c'] = $input['wheelalignrearbeforeleftcaster2'];
        $result['Wheel_Align_Near_Before_Left_Toein__c'] = $input['wheelalignrearbeforelefttoein'];
        $result['Wheel_Align_Near_Before_Left_Toein2__c'] = $input['wheelalignrearbeforelefttoein2'];
        $result['Wheel_Align_Near_Before_Right_Camber__c'] = $input['wheelalignrearbeforerightcamber'];
        $result['Wheel_Align_Near_Before_Right_Camber2__c'] = $input['wheelalignrearbeforerightcamber2'];
        $result['Wheel_Align_Near_Before_Right_Caster__c'] = $input['wheelalignrearbeforerightcaster'];
        $result['Wheel_Align_Near_Before_Right_Caster2__c'] = $input['wheelalignrearbeforerightcaster2'];
        $result['Wheel_Align_Near_Before_Right_Toein__c'] = $input['wheelalignrearbeforerighttoein'];
        $result['Wheel_Align_Near_Before_Right_Toein2__c'] = $input['wheelalignrearbeforerighttoein2'];
                	
        if($input['sketch_container']!='')
        $result['Sketch_Container__c'] = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'vir/view/index/type/consumer/image/sketch_container/id/'.$input['parent_id'];
        
        if($input['workshopsignatureimage']!='')
        $result['workshopsignatureimage__c'] = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'vir/view/index/type/consumer/image/workshopsignatureimage/id/'.$input['parent_id'];
        
        if($input['custapprsignatureimage']!='')
        $result['customersignatureimage__c'] = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).'vir/view/index/type/consumer/image/custapprsignatureimage/id/'.$input['parent_id'];

        if ($input['workorderalignoption4wd'] != null)
            $result['Work_Order_Align_Option_4wd__c'] = 1;

        if ($input['workorderalignoption4wheel'] != null)
            $result['Work_Order_Align_Option_4_Wheel__c'] = 1;

        if ($input['workorderalignoptionstandard'] != null)
            $result['Work_Order_Align_Option_Standard__c'] = 1;

        if ($input['workorderalignoptionthrust'] != null)
            $result['Work_Order_Align_Option_Thrust__c'] = 1;

        $result['Work_Order_Align_Price__c'] = $input['workorderalignprice'];
        $result['Work_Order_Align_Qty__c'] = $input['workorderalignqty'];

        if ($input['workorderbalancesoptionalloy'] != null)
            $result['Work_Order_Balances_Optional_Loy__c'] = 1;

        if ($input['workorderbalancesoptionpremium'] != null)
            $result['Work_Order_Balances_Option_Premium__c'] = 1;

        if ($input['workorderbalancesoptionsteel'] != null)
            $result['Work_Order_Balances_Option_Steel__c'] = 1;


        $result['Work_Order_Balances_Price__c'] = $input['workorderbalancesprice'];
        $result['Work_Order_Balances_Qty__c'] = $input['workorderbalancesqty'];
        $result['Work_Order_Batreport_Description__c'] = $input['workorderbatreportdescription'];
        $result['Work_Order_Batreport_Price__c'] = $input['workorderbatreportprice'];
        $result['Work_Order_Batreport_Qty__c'] = $input['workorderbatreportqty'];
        $result['Work_Order_Brakes_Description__c'] = $input['workorderbrakesdescription'];
        $result['Work_Order_Brakes_Price__c'] = $input['workorderbrakesprice'];
        $result['Work_Order_Brakes_Qty__c'] = $input['workorderbrakesqty'];
        $result['Work_Order_Other_Description__c'] = $input['workorderotherdescription'];
        $result['Work_Order_Other_Price__c'] = $input['workorderotherprice'];
        $result['Work_Order_Puncture_Description__c'] = $input['workorderpuncturedescription'];
        $result['Work_Order_Puncture_Price__c'] = $input['workorderpunctureprice'];
        $result['Work_Order_Puncture_Qty__c'] = $input['workorderpunctureqty'];
        $result['Work_Order_Tyres_Description__c'] = $input['workordertyresdescription'];
        $result['Work_Order_Tyres_Price__c'] = $input['workordertyresprice'];
        $result['Work_Order_Tyres_Qty__c'] = $input['workordertyresqty'];
        $result['Work_Order_Wheels_Description__c'] = $input['workorderwheelsdescription'];
        $result['Work_Order_Wheels_Price__c'] = $input['workorderwheelsprice'];
        $result['Work_Order_Wheels_Qty__c'] = $input['workorderwheelsqty'];
        $result['road_hazard_warranty_description__c'] = isset($input['road_hazard_warranty_description']) ? $input['road_hazard_warranty_description'] : '';
        $result['road_hazard_warranty_qty__c'] = isset($input['road_hazard_warranty_qty']) ?  $input['road_hazard_warranty_qty'] : '';
        $result['road_hazard_warranty_price__c'] = isset($input['road_hazard_warranty_price']) ? $input['road_hazard_warranty_price'] : '';

        $id = $this->getAccountSalesforceId($input['customerid']);
        $vir_id = $this->getVirConsumerSalesforceId($input['parent_id']);

        $result["Customer__c"] = $id;
        $result["salesforce_id"] = $vir_id;

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

}
