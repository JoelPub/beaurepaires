<?php  

class ApdInteract_Bookings_Block_Adminhtml_Bookingsbackend extends Mage_Adminhtml_Block_Template {

    public function getHeader(){
        return 'Store Manager Console';
    }

    /**
     * Get Booking dashboard
     * @return mixed
     */
    public function getButtonDashbord(){

        $baseUrl = Mage::getBaseUrl();
        return $baseUrl . Mage::getStoreConfig('booking/buttons/dashboard');
    }

    /**
     * Get Button Link Webmail
     * @return mixed
     */
    public function getButtonWebmail(){
        return $configValue = Mage::getStoreConfig('booking/buttons/webmail');
    }

    /**
     * Get Button Link Costar
     * @return mixed
     */
    public function getButtonCostar(){
        return $configValue = Mage::getStoreConfig('booking/buttons/costar');
    }

    /**
     * Get Button Link Website
     * @return mixed
     */
    public function getButtonWebsite(){
        return $configValue = Mage::getStoreConfig('booking/buttons/website');
    }

    /**
     * Get Button Link Calendar
     * @return mixed
     */
    public function getButtonCalendar(){
        return $configValue = Mage::getStoreConfig('booking/buttons/booking_calendar');
    }

}