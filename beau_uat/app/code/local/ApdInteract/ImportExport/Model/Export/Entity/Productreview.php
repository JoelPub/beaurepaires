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
 * @package     Mage_ImportExport
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**ApdInteract_ImportExport_Model
 * Export entity customer model
 *
 * @category    Mage
 * @package     Mage_ImportExport
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_ImportExport_Model_Export_Entity_Productreview extends Mage_ImportExport_Model_Export_Entity_Abstract
{ 
    /**
     * Initialize website values.
     *
     * @return Mage_ImportExport_Model_Export_Entity_Customer
     */
    protected function _initWebsites()
    {
        /** @var $website Mage_Core_Model_Website */
        foreach (Mage::app()->getWebsites(true) as $website) {
            $this->_websiteIdToCode[$website->getId()] = $website->getCode();
        }
        return $this;
    }

    /**
     * Export process to get all product reviews.
     *
     * @return string
     */
    public function export()
    {
        $writer = $this->getWriter();
        $review = Mage::getModel('review/review')->getCollection()
                    ->addFieldToSelect('review_id')
                    ->addFieldToSelect('created_at')
                    ->addFieldToSelect('entity_pk_value', 'Product Id');

        $review->getSelect()
            ->join(array('review_store_alias' => 'review_store'),'main_table.review_id = review_store_alias.review_id','store_id as Website')
            ->where('review_store_alias.store_id <> 0')
            ->join(array('core_store_alias' => 'core_store'),'review_store_alias.store_id = core_store_alias.store_id','name as Website')
            ->join(array('review_status_alias' => 'review_status'),'main_table.status_id = review_status_alias.status_id','status_code as Status')
            ->joinLeft(array('customer_entity_alias' => 'customer_entity'),'detail.customer_id = customer_entity_alias.entity_id','email as Customer Email')
            ->join(array('catalog_product_entity_alias' => 'catalog_product_entity'),'main_table.entity_pk_value = catalog_product_entity_alias.entity_id','sku as SKU')
            ->join(array('catalog_product_entity_varchar_alias' => 'catalog_product_entity_varchar'),'catalog_product_entity_alias.entity_id = catalog_product_entity_varchar_alias.entity_id','value as Product Name')
            ->where('catalog_product_entity_varchar_alias.attribute_id = 71')
            ->group('review_id');     
        
        $headerCols = $review->getFirstItem()->getData();
        $headerCols['score'] = null;
        $writer->setHeaderCols(array_keys($headerCols));
        $collection = $review->getData();

        foreach($collection as $row){
            if ($row['Website'] == 'Beaurepaires'){
                // Get the Rating score by loading product ids inside the foreach since there is No Foreign key that will link 'review_entity_summary' to 'review' table to get rating_summary
                $summaryData = Mage::getModel('review/review_summary')->setStoreId(1)->load($row['Product Id']);
                $row['score'] = $summaryData['rating_summary'];

                if (($row['score']>=0) &&($row['score'] <=20)){
                    $row['score'] = 1;
                }elseif (($row['score']>20) &&($row['score'] <=40)){
                    $row['score'] = 2;
                }elseif (($row['score']>40) &&($row['score'] <=60)){
                    $row['score'] = 3;
                }elseif (($row['score']>50) &&($row['score'] <=70)){
                    $row['score'] = 4;
                }elseif (($row['score']>60) &&($row['score'] <=100)){
                    $row['score'] = 5;
                }

            }else{
                $row['score'] = 'Unkown score';
            }

            $writer->writeRow($row);
        }
        return $writer->getContents();

    }

    /**
     * Entity attributes collection getter.
     *
     * @return Mage_Customer_Model_Entity_Attribute_Collection
     */
    public function getAttributeCollection()
    {
        return Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter('website_id');
    }

    /**
     * EAV entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'product_review';
    }
    
     public function exportFile()
    {
        $this->export();

        $writer = $this->getWriter();

        return array(
            'rows'  => $writer->getRowsCount(),
            'value' => $writer->getDestination()
        );
    }
}
