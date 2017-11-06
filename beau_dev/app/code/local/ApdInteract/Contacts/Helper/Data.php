<?php
/*
 * BFT-2138 : As an Admin, I want the customer to state the type of enquiry in the Contact Us form so an email will be sent to the appropriate team
 */
class ApdInteract_Contacts_Helper_Data extends Mage_Core_Helper_Abstract {
    
    /**    
    * Will check config values for email (System->Configuration->General->Store Email Addresses)
    *   
    * @return  array    $debug      
    */
    public function getAllEnquiries() {
        
        $feedback = Mage::getStoreConfig('trans_email/feedbackgroup/feedback_email');
        $product = Mage::getStoreConfig('trans_email/productgroup/product_email');
        $services = Mage::getStoreConfig('trans_email/servicesgroup/services_email');
        $bookings = Mage::getStoreConfig('trans_email/bookingsgroup/bookings_email');
        $website = Mage::getStoreConfig('trans_email/websitegroup/website_email');
        $orders = Mage::getStoreConfig('trans_email/ordersgroup/orders_email');
        $general = Mage::getStoreConfig('trans_email/generalgroup/general_email');
        
        $enquiries = array('Bookings'=>$bookings, 
                           'Feedback'=>$feedback,
                           'Orders'=>$orders,                           
                           'Products'=>$product,
                           'Services'=>$services,                           
                           'Website'=>$website,
                           'Other'=>$general
                           );
        return $enquiries;
        
    }
}
