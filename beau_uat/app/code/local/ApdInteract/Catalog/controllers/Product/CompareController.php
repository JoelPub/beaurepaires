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
 * @package     Mage_Catalog
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */


/**
 * Catalog comapare controller
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
// require_once Mage::getModuleDir('controllers', 'Mage_Catalog').DS.'Product' .DS.'CompareController.php';
require 'Mage/Catalog/controllers/Product/CompareController.php';
class ApdInteract_Catalog_Product_CompareController extends Mage_Catalog_Product_CompareController
{

    /**
     * Add item to compare list
     */
	public function addAction()
	{
		
		if (!$this->_validateFormKey()) {
			$this->_redirectReferer();
			return;
		}
	
		$productId = (int) $this->getRequest()->getParam('product');
		if ($productId
				&& (Mage::getSingleton('log/visitor')->getId() || Mage::getSingleton('customer/session')->isLoggedIn())
		) {
			$product = Mage::getModel('catalog/product')
			->setStoreId(Mage::app()->getStore()->getId())
			->load($productId);
	
			if ($product->getId()/* && !$product->isSuper()*/) {
				Mage::getSingleton('catalog/product_compare_list')->addProduct($product);
				Mage::getSingleton('catalog/session')->addSuccess(
						$this->__('The product <strong>%s</strong> has been added to ' . $this->getLayout()->createBlock('core/template')->setTemplate('compare/button.phtml')->toHtml(), Mage::helper('core')->escapeHtml($product->getName()))
				);
				Mage::dispatchEvent('catalog_product_compare_add_product', array('product'=>$product));
			}
	
			Mage::helper('catalog/product_compare')->calculate();
		}
	
		$this->_redirectReferer();
	}

}
