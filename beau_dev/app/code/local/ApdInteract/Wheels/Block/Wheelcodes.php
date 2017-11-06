<?php   
class ApdInteract_Wheels_Block_Wheelcodes extends Mage_Core_Block_Template {
   public function getWheelCollection() {
       
       $category_id = $this->_getWheelsCategory();
       
       $products = Mage::getModel('catalog/category')
        ->load($category_id)
        ->getProductCollection()
        ->addAttributeToFilter('status', 1)
        ->addAttributeToFilter('visibility', 4);
       
       return $products;
   }
   
   private function _getWheelsCategory() {
       
       $category = Mage::getModel('catalog/category')
        ->getCollection()
        ->addFieldToFilter('name', 'Wheels')
        ->getFirstItem();

        return $category->getId();
   }
   
   public function getChildProducts($configurable) {
       $configurable->getTypeInstance(true)->getUsedProducts(null, $configurable);
   }
   
   public function getChildIds($configurableProducts) {
        $Table=$configurableProducts->getTable('catalog/product_super_link');
        $parentIds=$configurableProducts->getAllIds(); 
        $ReadAdapter = Mage::getSingleton('core/resource')->getConnection('core_read');

        $select = $ReadAdapter->select()
            ->from(array('l' => $Table), array('product_id'))
            ->join(
                array('e' => $configurableProducts->getTable('catalog/product')),
                'e.entity_id = l.product_id AND e.required_options = 0',
                array()
            )
            ->where('parent_id IN(?) ', $parentIds)
            ->group('l.product_id')
            ;
        return $select;
   }
   
   private function _getCacheModel() {
       return Mage::app()->getCache();
   }


   public function setCacheSimple($string) {
       $this->_getCacheModel()->save($string, "wheel_simple_cache_value", array("wheel_cache"), 60*60*24); // 24 hours
   }
   
   public function getCacheSimple() {
       return $this->_getCacheModel()->load("wheel_simple_cache_value");
   }
   
   public function getChildCollection($configurableProducts) {
        $select = $this->getChildIds($configurableProducts);
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToFilter('type_id','simple');
        $collection->addIdFilter(array(new Zend_Db_Expr("{$select}")));
        return $collection;
   }
   
}