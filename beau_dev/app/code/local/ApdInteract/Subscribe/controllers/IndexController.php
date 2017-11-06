<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApdInteract_Subscribe_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * BFT-1915 Added subscription field in the Footer
     */
    public function newsletterAction()
    {
        $email = $this->getRequest()->getParam('email');  
        $subscriber = $this->_getNewsletterModel()->loadByEmail($email);
        if ($subscriber->getId() && (($subscriber->getSubscriberStatus() == '1') || ($subscriber->getSubscriberStatus() == '2'))) {    

            $response[] = array(
                'success' => 0,           
                'message' => 'This email address is already subscribed to receive Offers and News.'
            );
        }else{
            $this->_getNewsletterModel()->subscribe($email);
            $this->assignToGroup($email,2);

            $response[] = array(
                'success' => 1,           
                'message' => 'Thanks for subscribing.'
            );
        }
        echo json_encode($response);
    }
    
    private function _getNewsletterModel() {
        return Mage::getModel('newsletter/subscriber');
    }


    /**
     * @param $email
     * @param $goupId
     * @throws Mage_Core_Exception
     */
    private function assignToGroup($email,$goupId){

        $group = Mage::getModel( 'newslettergroup/group' )->load($goupId);
        if((boolean)$group->getId()){
            $subscribetoGroup = $this->_getNewsletterModel()->loadByEmail($email);
            $subscribetoGroup->setNewsletterGroupId($group->getId());
            $subscribetoGroup->save();
        }else{

            $error = "Newsletter Group Id does not exist";
            Mage::throwException($error);
        }

    }

    
}
