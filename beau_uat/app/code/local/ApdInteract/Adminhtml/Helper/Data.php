<?php
class ApdInteract_Adminhtml_Helper_Data extends Mage_Core_Helper_Abstract
{

    /* Check whether a registration is already being used by another existing Customer before Saving.
     * @param $customer object
     * @param @registration string
     * @return bolean
     */
    public function _isRegistrationUnique($customer,$registration){
        $collection = Mage::helper('apdinteract_vehicle')->checkIfVehicleIsExisting($customer->getId(),$registration);
        if ($collection->getSize() > 0){
            return false;
        }
        return true;
    }
}
