<?php

require_once(Mage::getModuleDir('controllers', 'Mage_Review') . DS . 'ProductController.php');

class Wyomind_Googleproductratings_ProductController extends Mage_Review_ProductController
{

    public function viewAction() 
    {

        $review = $this->_loadReview((int) $this->getRequest()->getParam('id'));

        if (!$review) {
            $this->_forward('noroute');
            return;
        }

        $product = $this->_loadProduct($review->getEntityPkValue());
        if (!$product) {

            $product = $this->_loadProduct($this->getRequest()->getParam('product_id'));
            if (!$product) {
                $this->_forward('noroute');
                return;
            }
        }

        $this->loadLayout();
        $this->_initLayoutMessages('review/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }

    protected function _loadReview($reviewId) 
    {
        if (!$reviewId) {
            return false;
        }

        $review = Mage::getModel('review/review')->load($reviewId);


        if (!$review->getId() || !$review->isApproved() || (!$review->isAvailableOnStore(Mage::app()->getStore()) && !$this->getRequest()->getParam('product_id') )) {
            return false;
        }

        Mage::register('current_review', $review);

        return $review;
    }

}
