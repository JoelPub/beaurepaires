<?php
class ApdInteract_WhoAlsoView_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	/**
	 * Get 'customers also viewed'-product collection
	 *
	 * @param     $productId
	 * @param int $limit
	 *
	 * @return Mage_Catalog_Model_Resource_Product_Collection
	 */
	public function _getCustomersAlsoViewedProducts($productId, $limit)
	{
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$productIds = $connection->fetchCol(
				sprintf('
            SELECT DISTINCT A.product_id FROM
                report_viewed_product_index A,
                report_viewed_product_index B,
                report_viewed_product_index C
            WHERE
                C.product_id = %1$d AND
                B.visitor_id = C.visitor_id AND
                A.product_id = B.product_id AND
                A.product_id != %1$d
            ORDER BY RAND() LIMIT %2$d;
        ', $productId, $limit)
		);
		$collection = Mage::getModel('catalog/product')->getCollection();
		$collection->addIdFilter($productIds);
		$collection->addAttributeToSelect('*');
		$collection->addAttributeToFilter('visibility', 4);
		return $collection;
	}
}
	 