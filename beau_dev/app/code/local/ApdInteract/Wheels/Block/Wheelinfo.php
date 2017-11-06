<?php   
class ApdInteract_Wheels_Block_Wheelinfo extends Mage_Core_Block_Template {
    private $_product_model;
    private $_child_product;
    private $_parent_product;
    
    public function getProduct() {
        if (!isset($this->_child_product)) {
            $sku = $this->_getSku();            
            $this->_child_product = $this->_getProductModel()->loadByAttribute('sku', $sku);   
        }
        return $this->_child_product;
    }
    
    public function getProductParent() {
        if (!isset($this->_parent_product)) {            
            $child_product = $this->getProduct();
            if (!$child_product) {
                return false;
            }
            $this->_parent_product = $this->_getParent($child_product);
            
        }
        return $this->_parent_product;
    }

    private function _getParent($product) {
        list( $parent_id ) = Mage::getModel('catalog/product_type_configurable')
                ->getParentIdsByChild( $product->getId() );
        return $this->_getProductModel()->load($parent_id);  
    }
    
    private function _getProductModel() {
        if (!isset($this->_product_model)) {
            $this->_product_model = Mage::getModel('catalog/product');
        }
        return $this->_product_model;
    }
    
    private function _getSku() {
        return Mage::app()->getRequest()->getParam('sku');
    }    
    
    public function getAttributeValue($attribute_code) {
        $product = $this->getProductParent();
        $value = $product->getData($attribute_code . '_value');
        if (empty($value)) {
            $value = $product->getAttributeText($attribute_code);
        }
        return $value;
    }
    
    public function getBrand() {
        return $this->getAttributeValue('brand');
    }
    
    public function getOverlay() {
        return $this->getAttributeValue('overlay');
    }
    
    public function getProductImage() { 
        $_product = $this->getProductParent(); // ->getSmallImage() 
        $img = $_product->getSmallImage();
        if (empty($img)) {
            return null;
        }
        return (string)Mage::helper('catalog/image')->init($_product, 'small_image')->resize(75);
        
        // return Mage::getBaseUrl('media'). $this->getProductParent()->getSmallImage(); 
    }
    
    public function toUrlKey($string) {
        // Convert SoME tHing => some-thing
        return Mage::helper('catalog/product_url')->format($string);
    }
    
    private function _overlayToConfigKey($overlay_value) {
        // Best Seller => bestseller
        // New Arrival => new
        // On Sale => onsale
        $find = array(' ', 'Arrival');
        return strtolower(str_replace($find, '', $overlay_value));
    }
    
    public function getOverlayImage($large = true) {
        $overlay = $this->getOverlay();        
        if ($large) {
            $overlay .= 'th';
        }
        
        $url_key = Mage::getStoreConfig('badges/badges/' . $this->_overlayToConfigKey($overlay));
        if (empty($url_key)) {
            return null;
        }
        return Mage::getBaseUrl('media').'theme/' . $url_key;        
    }
    
    public function getBrandImage() {        
        return Mage::getBaseUrl('skin'). 'frontend/polar/default/images/brands/' . strtolower($this->getBrand()) . '-logo.png'; 
    }
    
    public function getPrice($type = false) {
        $_product = $this->getProduct();
        if (!$type) {
            return Mage::helper('core')->currency($_product->getFinalPrice(),true,false);
        }
        return Mage::helper('core')->currency($_product->getData($type),true,false);
    }

    public function getAttributeIcon($product){

        $attributes_array = Mage::helper('apdinteract_catalog')->getTyreAttributesArray();
        $attribute_values = Mage::helper('apdinteract_catalog')->getProductAttributeTextFromAttributesArray($product, $attributes_array);
        $attributes = Mage::helper('apdinteract_hierarchy')->getSortedAttributes($attribute_values, $product);
        $tooltipCode = 'tw';

        $attributeToDisplay = [];
        if (isset($attributes)){
            foreach ($attributes as $attribute){
                if ($attribute_values[$attribute] > 0){
                    $class = Mage::helper('apdinteract_hierarchy')->getAttributeIcon($attribute);
                    $code = Mage::helper('apdinteract_hierarchy')->getAttributeCode($attribute);
                    $text = Mage::helper('apdinteract_hierarchy')->getAttributeText($attribute);
                    $attributeToDisplay[] = array(
                        'text' => $text,
                        'class' => $class,
                        'tooltip' => Mage::helper('apdwidgets')->getAltforIcon($code,$tooltipCode),
                    );
                }

            }
        }
        return $attributeToDisplay;
    }
}