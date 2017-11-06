<?php

class ApdInteract_Adminhtml_Block_Customer_Edit_Tab_Newsletter extends Mage_Adminhtml_Block_Customer_Edit_Tab_Newsletter
{
    /*
     * BFT-1803
     * Modified Newsletter page in Customer Edit Form to sync with the newsletter section found in the Customer MyAccount Section
     * General Subscription => Communications
     * General Newsletter => Communications
     * Product News => SMS
     * Special Offers and Rewards => SMS
     */

    public function initForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_newsletter');
        $customer = Mage::registry('current_customer');
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByCustomer($customer);
        Mage::register('subscriber', $subscriber);

        if ($customer->getWebsiteId() == 0) {
            $this->setForm($form);
            return $this;
        }

        $_groups = $this->_getGroups();
        $_subscribersGroupIds = $subscriber->getNewsletterGroupId();
        $_groupIds = explode( ',', $_subscribersGroupIds );
        foreach ($_groups as $_group){
            if ( $_group->getSubGroups() ){
                $newsletter = $form->addFieldset($_group->getGroupName().'_fieldset', array('legend'=>Mage::helper('customer')->__($_group->getGroupName())));
                foreach ( $_group->getSubGroups() as $_child ){
                    in_array($_child->getId(), $_groupIds) ? $_checked = true : $_checked = false;
                    $groupName = Mage::helper('newslettergroup')->hidePrefixFromNewsletterName($_child->getGroupName());
                    $name = str_replace(' ', '_', strtolower($groupName));
                    $newsletter->addField($name, 'checkbox',
                        array(
                            'label' => Mage::helper('customer')->__($groupName),
                            'name'  => $name,
                            'value' => $_child->getId()
                        )
                    );
                    if ($customer->isReadonly()) {
                        $form->getElement($name)->setReadonly(true, true);
                    }
                    $form->getElement($name)->setIsChecked($_checked);
                }
            }
        }

        /* Commented out (backup) this portion to implement changes made by BFT-1803
        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('customer')->__('Newsletter Information')));

        $fieldset->addField('subscription', 'checkbox',
             array(
                    'label' => Mage::helper('customer')->__('Subscribed to Newsletter?'),
                    'name'  => 'subscription'
             )
        );

        if ($customer->isReadonly()) {
            $form->getElement('subscription')->setReadonly(true, true);
        }

        $form->getElement('subscription')->setIsChecked($subscriber->isSubscribed());


        if($changedDate = $this->getStatusChangedDate()) {
             $fieldset->addField('change_status_date', 'label',
                 array(
                        'label' => $subscriber->isSubscribed() ? Mage::helper('customer')->__('Last Date Subscribed') : Mage::helper('customer')->__('Last Date Unsubscribed'),
                        'value' => $changedDate,
                        'bold'  => true
                 )
            );
        }
        */
        $this->setForm($form);
        return $this;
    }

    /**
     * Get newsletter groups
     * @return object
     */
    private function _getGroups()
    {
        // Add filter
        $collection = Mage::getResourceModel('newslettergroup/group_collection')
            ->addFieldToFilter('visible_in_frontend', array("eq" => 1))
            ->load();

        return $collection->getItems();
    }

}
