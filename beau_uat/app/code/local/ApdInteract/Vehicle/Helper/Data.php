<?php
class ApdInteract_Vehicle_Helper_Data extends Mage_Core_Helper_Abstract
{

    /*
    * List all vehicle collection
    *
    * @return object
    */
    public function vehicleCollection(){

        $customerData = Mage::getSingleton('customer/session')->getCustomer();

        $collection = Mage::getModel('apdinteract_vehicle/vehicle')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id',$customerData->getId())
            ->addFieldToFilter('website_id',$customerData->getWebsiteId());


        return $collection;
    }

    /*
     * Load vehicle by vehicle ID
     *
     * @param int
     * @return object
     */
    public function vehicleLoadById($vehicle_id){

        if($vehicle_id){

            $customerData = Mage::getSingleton('customer/session')->getCustomer();

            $store = Mage::app()->getStore();


            $collection = Mage::getModel('apdinteract_vehicle/vehicle')->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('vehicle_id',$vehicle_id)
                ->addFieldToFilter('customer_id',$customerData->getId())
                ->addFieldToFilter('website_id',$customerData->getWebsiteId());

            return $collection->getFirstItem();
        }

    }


    public function getVehicleCollection($customerEmail,$rego = null){

        $vehicleData = "";
        try{

            $customer = Mage::getModel('customer/customer')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('email', $customerEmail);
            if($rego != null){
                $customer->getSelect()->joinLeft(array("vehicles" => 'customer_vehicle'), "e.entity_id = vehicles.customer_id",
                    array("*"))->where("vehicles.registration = '{$rego}' && vehicles.details != ''");
            }else{
                $customer->getSelect()->joinLeft(array("vehicles" => 'customer_vehicle'), "e.entity_id = vehicles.customer_id",
                    array("*"))->where("vehicles.registration != '' && vehicles.details != ''");;
            }
            $vehicleData = $customer->getData();

        }catch (Exception $e){
            Mage::throwException($e->getMessage());
        }


        return $vehicleData;

    }


    /*
     * Check If entry is unique
     *
     * @param int, varchar
     * @return object

     */
    public function checkIfVehicleIsExisting($customerId,$registration) {

        $collection = Mage::getModel('apdinteract_vehicle/vehicle')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id',$customerId)
            ->addFieldToFilter('registration',$registration);

        return $collection;
    }


    /*
     * Encode vehicle information
     *
     * @param int
     * @return json
     */
    public function jsonDetails($v_id){

        $vehicle = array();
        $item = Mage::getModel('apdinteract_vehicle/vehicle')->load($v_id);

        $vehicle['make-tyres'] = $item->getMake();
        $vehicle['year-tyres'] = $item->getManufactureYear();
        $vehicle['model-tyres'] = $item->getModel();
        $vehicle['series-tyres'] = $item->getSeries();


        return json_encode($vehicle);

    }

}
?>