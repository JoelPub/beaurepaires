<?php
class ApdInteract_Headings_Block_Onepage_Review extends Mage_Checkout_Block_Onepage_Review
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('review', array(
            'label'     => Mage::getStoreConfig('headings/headings/apdinteract_review'),
            'is_show'   => $this->isShow()
        ));
        #parent::_construct();

        $this->getQuote()->collectTotals()->save();
    }
}
