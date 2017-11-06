
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
 * Product View block
 *
 * @category Mage
 * @package  Mage_Catalog
 * @module   Catalog
 * @author   Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View {

    private $_configurableBlock;

    public function getConfigurableBlock() {
        if (!isset($this->_configurableBlock)) {
            $this->_configurableBlock = Mage::app()->getLayout()->createBlock('apdinteract_catalog/product_view_type_configurable');
        }
        return $this->_configurableBlock;
    }

    public function checkAttr($code) {

        $m = Mage::getModel('catalog/resource_eav_attribute')->loadByCode('catalog_product', $code);

        if (null === $m->getId()) {
            return false;
        } else {
            return true;
        }
    }

    public function getPriceJsonConfigLite() {
        // A lot lighter than the proper way - Mage_Catalog_Block_Product_View :: getJsonConfig()
        // Can do this while there's no pricing on the site.
        $_product_id = $this->getProduct()->getId();
        return '{"productId":"' . $_product_id . '","priceFormat":{"pattern":"$%s","precision":2,"requiredPrecision":2,"decimalSymbol":".","groupSymbol":",","groupLength":3,"integerRequired":1},"includeTax":"false","showIncludeTax":false,"showBothPrices":false,"productPrice":0,"productOldPrice":0,"priceInclTax":0,"priceExclTax":0,"skipCalculate":1,"defaultTax":0,"currentTax":0,"idSuffix":"_clone","oldPlusDisposition":0,"plusDisposition":0,"plusDispositionTax":0,"oldMinusDisposition":0,"minusDisposition":0,"tierPrices":[],"tierPricesInclTax":[]}';
    }

    public function prodModel() {
        $_product = $this->getProduct();
        return Mage::getModel('catalog/product')->load($_product->getId());
    }

    public function getRrp() {
        $productPrice = $this->prodModel()->getPrice();
        $rrp = Mage::helper('core')->currency($productPrice, true, false);

        return $rrp;
    }

    public function getOnlinePrice() {
        $productSpecialPrice = $this->prodModel()->getFinalPrice();
        $onlinePrice = Mage::helper('core')->currency($productSpecialPrice, true, false);

        return $onlinePrice;
    }

    public function getCategoryImageUrl() {
        $prod = $this->prodModel()->getCategoryIds();
        foreach ($prod as $category_id) {
            $categoryImage = Mage::getModel('catalog/category')->load($category_id)->getImageUrl();
        }

        if (!empty($categoryImage)) {
            // IMAGE RESIZE
            $imageUrl = $categoryImage;
            // create folder
            if (!file_exists("./media/catalog/category/resized")) {
                mkdir("./media/catalog/category/resized", 0777);
            }

            // get image name
            $imageName = substr(strrchr($imageUrl, "/"), 1);

            // resized image path (media/catalog/category/resized/IMAGE_NAME)
            $imageResized = Mage::getBaseDir('media') . DS . "catalog" . DS . "category" . DS . "resized" . DS . $imageName;

            // changing image url into direct path
            $dirImg = Mage::getBaseDir() . str_replace("/", DS, strstr($imageUrl, '/media'));

            // if resized image doesn't exist, save the resized image to the resized directory
            if (!file_exists($imageResized) && file_exists($dirImg)) :
                $imageObj = new Varien_Image($dirImg);
                $imageObj->constrainOnly(true);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepFrame(false);
                $imageObj->keepTransparency(true);
                $imageObj->resize(146, 31);
                $imageObj->save($imageResized);
            endif;

            $newImageUrl = Mage::getBaseUrl('media') . "catalog/category/resized/" . $imageName;

            return $newImageUrl;
        }
    }

    public function getProductImages() {
        $_images = $this->prodModel()->getMediaGalleryImages();
        return $_images;
    }

    public function getOverlay() {

        if ($this->checkAttr('overlay')) {
            $_product = $this->getProduct();
            $overlay = $_product->getResource()->getAttribute('overlay');
            $attribute_value = $overlay->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }
        return $attribute_value;
    }

    public function getCategorization() {

        if ($this->checkAttr('categorization')) {
            $_product = $this->getProduct();
            $categorization = $_product->getResource()->getAttribute('categorization');
            $attribute_value = $categorization->getFrontend()->getValue($_product);
            $attribute_value = "";
        } else {
            $attribute_value = '';
        }
        return $attribute_value;
    }

    public function getBenefitsDescription() {
        if ($this->checkAttr('benefits_description')) {
            $_product = $this->getProduct();
            $benefitsDescription = $_product->getResource()->getAttribute('benefits_description');
            $attribute_value = $benefitsDescription->getFrontend()->getValue($_product);
            $attribute_value = "";
        } else {
            $attribute_value = '';
        }
        return $attribute_value;
    }

    public function getWidth() {
        if ($this->checkAttr('width')) {
            $_product = $this->getProduct();
            $width = $_product->getResource()->getAttribute('width');
            $attribute_value = $width->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }
        return $attribute_value;
    }

    public function getAspectRatio() {

        if ($this->checkAttr('aspect_ratio')) {
            $_product = $this->getProduct();
            $aspectRatio = $_product->getResource()->getAttribute('aspect_ratio');
            $attribute_value = $aspectRatio->getFrontend()->getValue($_product);
            //$attribute_value = $width ->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }

        return $attribute_value;
    }

    public function getRim() {

        if ($this->checkAttr('rim')) {
            $_product = $this->getProduct();
            $rim = $_product->getResource()->getAttribute('rim');
            $attribute_value = $rim->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }

        return $attribute_value;
    }

    public function getPositionWheels() {
        if ($this->checkAttr('position_wheels')) {
            $_product = $this->getProduct();
            $positionWheels = $_product->getResource()->getAttribute('position_wheels');
            $attribute_value = $positionWheels->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }

        return $attribute_value;
    }

    public function getMeasuringRim() {
        if ($this->checkAttr('measuring_rim')) {
            $_product = $this->getProduct();
            $measuringRim = $_product->getResource()->getAttribute('measuring_rim');
            $attribute_value = $measuringRim->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }

        return $attribute_value;
    }

    public function getTreadDepth() {
        if ($this->checkAttr('tread_depth')) {
            $_product = $this->getProduct();
            $treadDepth = $_product->getResource()->getAttribute('tread_depth');
            $attribute_value = $treadDepth->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }

        return $attribute_value;
    }

    public function getOverallWidth() {
        if ($this->checkAttr('overall_width')) {
            $_product = $this->getProduct();
            $overallWidth = $_product->getResource()->getAttribute('overall_width');
            $attribute_value = $overallWidth->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }
        return $attribute_value;
    }

    public function getSpeedRating() {
        if ($this->checkAttr('speed_rating')) {
            $_product = $this->getProduct();
            $speedRating = $_product->getResource()->getAttribute('speed_rating');
            $attribute_value = $speedRating->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }

        return $attribute_value;
    }

    public function getLoadIndex() {
        if ($this->checkAttr('load_index')) {
            $_product = $this->getProduct();
            $loadIndex = $_product->getResource()->getAttribute('load_index');
            $attribute_value = $loadIndex->getFrontend()->getValue($_product);
        } else {
            $attribute_value = '';
        }

        return $attribute_value;
    }

    public function getWheelAlignmentProduct() {
        return Mage::getModel('catalog/product')->loadByAttribute('sku', 'AS_6639996');
    }

    public function getWheelAlignmentPrice() {
        $formattedPrice = Mage::helper('checkout')->formatPrice($this->getWheelAlignmentProduct()->getPrice());
        return $formattedPrice;
    }

    public function getWheelAlignmentTooltip() {
        $tooltip = Mage::helper('apdinteract_tooltip')->getTooltip($this->getWheelAlignmentProduct()->getName());
        return $tooltip;
    }

    public function _isTyrePage($_product) {
        $categoryIds = $_product->getCategoryIds();
        $category = 41;
        $isInCategory = in_array($category, $categoryIds);

        return $isInCategory;
    }

    public function _isBatteryPage($_product) {
        $categoryIds = $_product->getCategoryIds();
        $category = 43;
        $isInCategory = in_array($category, $categoryIds);
        return $isInCategory;
    }


    public function _isWheelsPage($_product)
    {
        $categoryIds = $_product->getCategoryIds();
        $category = 42;
        return in_array($category, $categoryIds);
    }

    public function get4wdWheelAlignmentProduct() {
        return Mage::getModel('catalog/product')->loadByAttribute('sku', 'AS_6540008');
    }

    public function get4wdWheelAlignmentPrice() {
        $formattedPrice = Mage::helper('checkout')->formatPrice($this->get4wdWheelAlignmentProduct()->getPrice());
        return $formattedPrice;
    }

    public function displayExtra($_product) {
        $helper = Mage::helper('apdinteract_catalog');
        $active = false;
        $data = array();
        if ($this->_isTyrePage($_product)):
            if ($this->getWheelAlignmentProduct() && $helper->checkIfSuvOr4wd($_product)):
                $active = true;
                $data['formatted_price'] = $this->getWheelAlignmentPrice();
                $data['price'] = $this->getWheelAlignmentProduct()->getPrice();
                $data['name'] = $this->getWheelAlignmentProduct()->getName();
                $data['id'] = $this->getWheelAlignmentProduct()->getId();
                $data['tooltip'] = $this->getWheelAlignmentTooltip();
            elseif ($this->get4wdWheelAlignmentProduct()):
                $active = true;
                $data['formatted_price'] = $this->get4wdWheelAlignmentPrice();
                $data['price'] = $this->get4wdWheelAlignmentProduct()->getPrice();
                $data['name'] = $this->get4wdWheelAlignmentProduct()->getName();
                $data['id'] = $this->get4wdWheelAlignmentProduct()->getId();
                $data['tooltip'] = $this->getWheelAlignmentTooltip();
            endif;
        endif;

        return array('active' => $active, 'data' => $data);
    }

    public function displayExtraNew($_product) {
        $skus = $this->_getExtras();
        $checkout_helper = Mage::helper('checkout');
        $catalog = Mage::getModel('catalog/product');
        $active = false;
        $data = array();
        foreach ($skus as $sku):
            $productBySku = $catalog->load($catalog->getIdBySku($sku['sku']));

            if ($productBySku):
                $on = false;
                $conditions = $sku['condition'];
                $count = count($conditions);
                
                if ($count > 0 && $this->_isTyrePage($_product)):
                    for ($i = 0; $i <= $count - 1; $i++):
                        $on = false;
                        if (strpos($_product->getAttributeText('application'), $conditions[$i]) !== false):
                            $on = true;
                            break;
                        endif;
                    endfor;
                elseif (($this->_isBatteryPage($_product) && $sku['category'] == 43) || 
                    ($this->_isTyrePage($_product) && $count <= 0 && $sku['category'] == 41) || ($this->_isWheelsPage($_product) && $count <=0 && $sku['type'] == 'wheel-align')):
                    $on = true;
                endif;

                if ($on && !in_array($sku['type'], $data)):
                    $active = true;
                    $data[$sku['type']] = array('formatted_price' => $checkout_helper->formatPrice($productBySku->getPrice()),
                        'price' => $productBySku->getPrice(),
                        'name' => $productBySku->getName(),
                        'id' => $productBySku->getId(),
                        'type' => $sku['type'],
                        'multiply' => $sku['multiply'],
                        'tooltip' => $this->_getTip($sku['type'])
                    );
                endif;

            endif;
        endforeach;
        return array('active' => $active, 'data' => $data);
    }

    public function getAttributeId() {
        $_product = $this->getProduct();
        if ($_product->getTypeId() == 'configurable'):
            $_attributes = $_product->getTypeInstance(true)->getConfigurableAttributes($_product);
            $attr_array = $_attributes->getData();
            $attr_id = $attr_array[0]['attribute_id'];
        endif;

        return isset($attr_id) ? $attr_id : '';
    }

    private function _getExtras() {
        $skus = array(
            array('sku' => 'AS_6639996',
                'condition' => array(),
                'category' => 41,
                'type' => 'wheel-align',
                'multiply' => 'no',
                'title' => 'Wheel Alignment'
            ),
            array('sku' => 'AS_6540008',
                'condition' => array('4WD', 'SUV'),
                'category' => 41,
                'type' => 'wheel-align',
                'multiply' => 'no',
                'title' => 'Wheel Alignment'
            ),
            array('sku' => 'AS_6666997',
                'condition' => array('Passenger', 'Sport'),
                'category' => 41,
                'type' => 'road-hazard',
                'multiply' => 'yes',
                'title' => 'Road Hazard Warranty (per tyre)'
            ),
            array('sku' => 'AS_6666971',
                'condition' => array('4WD', 'SUV', 'Light'),
                'category' => 41,
                'type' => 'road-hazard',
                'multiply' => 'yes',
                'title' => 'Road Hazard Warranty (per tyre)'
            ),
            array('sku' => 'AS_6969991',
                'condition' => array(),
                'category' => 43,
                'type' => 'battery-fitting',
                'multiply' => 'yes',
                'title' => 'Battery'
            )
        );

        return $skus;
    }

    private function _getTip($type) {

         $tooltip = Mage::helper('apdinteract_tooltip');
                 
        $tips = array('road-hazard' => $tooltip->getTooltip('Road Hazard Warranty (per tyre)'),
            'wheel-align' => $tooltip->getTooltip('Wheel Alignment'),'battery-fitting' => $tooltip->getTooltip('Battery Fitting'));
        return $tips[$type];
    }
     public function getBackLink() {
        
        $urls = explode('/',$this->getProduct()->getProductUrl());
        return  '/'.$urls[3].'/';

    }

    /**
     * Google Map API Key
     * @return string
     */
    public function getGoogleMapAPI(){
        return 'AIzaSyCrRqZVusPL4m3zMTz7Jxuwmmp38tK4eTA';
    }


    /**
     * @return int
     */
    public function getStoreLocationId(){

        $storeId = Mage::getSingleton('core/session')->getStorelocation();
        if(empty($storeId)){
            $storeId = Mage::getSingleton("core/session")->getdefaultStoreId();
            if(empty($storeId)){
                $storeId = Mage::getModel('core/cookie')->get('store_id');
            }
        } 

        return $storeId;
    }


}
