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
 * @package     Mage_Sitemap
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */


/**
 * Sitemap resource catalog collection model
 *
 * @category    Mage
 * @package     Mage_Sitemap
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_Sitemap_Model_Resource_Store_Storelocator extends Mage_Sitemap_Model_Resource_Catalog_Abstract
{
    /**
     * Init resource model (catalog/category)
     */
    protected function _construct()
    {
        $this->_init('catalog/category', 'entity_id');
    }

    /**
     * Get store location details collection array
     *
     * @param int $storeId
     * @return array
     */
    public function getCollection($storeId)
    {
        $store = Mage::app()->getStore($storeId);
        if (!$store) {
            return false;
        }
        $storeCollection = Mage::getModel('storelocator/stores')->getCollection()->addFieldToSelect('url');
        
        return $storeCollection;

    }

    /**
     * Prepare category
     *
     * @deprecated after 1.7.0.2
     *
     * @param array $categoryRow
     * @return Varien_Object
     */
    protected function _prepareCategory(array $categoryRow)
    {
        return $this->_prepareObject($categoryRow);
    }

    /**
     * Retrieve entity url
     *
     * @param array $row
     * @param Varien_Object $entity
     * @return string
     */
    protected function _getEntityUrl($row, $entity)
    {
        return !empty($row['request_path']) ? $row['request_path'] : 'catalog/category/view/id/' . $entity->getId();
    }

    /**
     * Loads category attribute by given attribute code.
     *
     * @param string $attributeCode
     * @return Mage_Sitemap_Model_Resource_Catalog_Abstract
     */
    protected function _loadAttribute($attributeCode)
    {
        $attribute = Mage::getSingleton('catalog/category')->getResource()->getAttribute($attributeCode);

        $this->_attributesCache[$attributeCode] = array(
            'entity_type_id' => $attribute->getEntityTypeId(),
            'attribute_id'   => $attribute->getId(),
            'table'          => $attribute->getBackend()->getTable(),
            'is_global'      => $attribute->getIsGlobal(),
            'backend_type'   => $attribute->getBackendType()
        );
        return $this;
    }
}
