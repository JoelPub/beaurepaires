<?php
/**
 * Sales Order Observer to send Order Infromation to Costar
 *
 * @category	ApdInteract
 * @package		ApdInteract_Costar
 * @author		Jagdeep
 */


class ApdInteract_Costar_Model_Sales_Order_Observer {

    public function sendOrderToCostar(Varien_Event_Observer $observer){

        $_order = $observer->getEvent()->getOrder();

        if(!in_array($_order->getData('request_type'), array('BOOKING', 'PRICE REQUEST'))) {

            Mage::helper('costar/api')->log($_order->getData());

            //Prepare Costar Order Info
            $costarFieldsArray = Mage::helper('costar/api')->prepareCostarOrderInfo($_order);

            // Make a Costar SubmitOrder Api call
            $result = Mage::getModel('apdinteract_costar/api')->submitOrder($costarFieldsArray[0],$costarFieldsArray[1]);

            //Update Order status and History on API response
            if($result['error']){
                Mage::helper('costar/api')->costarRejectedHistory($_order,$result['message']);
            }else{
                Mage::helper('costar/api')->costarAcceptedHistory($_order,$result['message']);
            }
        }

    }

}