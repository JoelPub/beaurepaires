<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Review
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Detailed Product Reviews
 *
 * @category   Mage
 * @package    Mage_Review
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_Review_Block_Product_View_List extends Mage_Review_Block_Product_View_List
{
    protected $_forceHasOptions = false;

    public function getProductId()
    {
        return Mage::registry('product')->getId();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($toolbar = $this->getLayout()->getBlock('product_review_list.toolbar')) {
            $toolbar->setCollection(Mage::helper('apdinteract_review')->getReviewsCollection());
            $this->setChild('toolbar', $toolbar);
        }

        return $this;
    }

    protected function _beforeToHtml()
    {
        Mage::helper('apdinteract_review')->getReviewsCollection()
            ->load()
            ->addRateVotes();
        return parent::_beforeToHtml();
    }

    public function getReviewUrl($id)
    {
        return Mage::getUrl('review/product/view', array('id' => $id));
    }

    public function getCustomerFullName($customerId){
        $customer = Mage::getModel('customer/customer')->load($customerId);
        if ($customer->getId()){
            return $customer->getName();
        }
        return false;
    }

    public function getVoteReviewUrl(){
        return Mage::getUrl('review/product/vote');
    }
}
