<?php
/**
 * Catalog Product Bestseller Block
 *
 * @author APD Team
 */
class Mage_Catalog_Block_Product_Bestseller extends Mage_Catalog_Block_Product_Abstract
{
    public function getCollection()
    {

        $storeId = Mage::app()->getStore()->getId();
        $cacheId = 'home_popular_product_'.$storeId;
        $results = array();        

       if(false !== ($data = Mage::app()->getCache()->load($cacheId))){
           $results = unserialize($data);
        }else {
            $date = new Zend_Date();
            $toDate = $date->setDay(1)->getDate()->get('Y-MM-dd');
            $fromDate = $date->subMonth(2)->getDate()->get('Y-MM-dd'); 
            $collection = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addStoreFilter()
                ->addPriceData()
                ->addTaxPercents()
                ->addUrlRewrite()
                ->setPageSize($this->getLimit());

            $collection->getSelect()
                ->joinLeft(
                    array('aggregation' => $collection->getResource()->getTable('sales/bestsellers_aggregated_monthly')),
                    "e.entity_id = aggregation.product_id AND aggregation.store_id={$storeId} AND aggregation.period BETWEEN '{$fromDate}' AND '{$toDate}'",
                    array('SUM(aggregation.qty_ordered) AS sold_quantity')
                )
                ->group('e.entity_id')
                ->order(array('sold_quantity DESC', 'e.created_at'));

            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
            foreach ($collection as $product) {
                
                
                $data = array();
                $data['product_id'] = $product->getId();
                $product_data = Mage::getModel('catalog/product')->load($product->getId());
                $data['product_url'] = Mage::helper("apdwidgets")->getFullProductUrl($product);
                $data['overlay'] = $product->getAttributeText('overlay');
                $data['name'] = $product->getName();
                //$th = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $product_data->getImage();
                $data['thumbnail'] = (string)Mage::helper('catalog/image')->init($product_data, 'image')->resize(100,100);
                $data['brand'] = $product->getAttributeText('brand');
                
                // Pricing
                $minimumPriceFromSimple = Mage::helper('apdinteract_catalog')->shouldDisplayMinimalPrice($product);
                $data['rrp'] = Mage::helper('core')->currency($minimumPriceFromSimple['rrp_price']);
                $data['special_price'] = $minimumPriceFromSimple['special_price'];
                $data['online_price'] = Mage::helper('core')->currency($minimumPriceFromSimple['online_price']);
                $data['from'] = Mage::helper('apdinteract_catalog')->shouldDisplayFromText($product);
                $data['is_configurable'] = Mage::helper('apdinteract_catalog')->isConfigurable($product);

                //Compare
                $data['add_compare_url'] =  Mage::helper('catalog/product_compare')->getAddUrl($product);


                $results[] = $data;
            }

           Mage::app()->getCache()->save(serialize($results), $cacheId);
        }
                        
        return $results;

    }
}
