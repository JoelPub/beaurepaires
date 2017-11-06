<?php
class ApdInteract_Addimage_Model_Observer
{
    static protected $_singletonFlag = FALSE;
    public function Addimage(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = TRUE;
            $product              = $observer->getEvent()->getProduct();
            $new_product          = Mage::getModel('catalog/product')->load($product->getId());
            $categoryIds          = $new_product->getCategoryIds();
            if (count($categoryIds))
                $firstCategoryId = $categoryIds[0];
            $brand_name   = $new_product->getAttributeText('brand');
            $style_name   = $new_product->getStyle();
            $product_name = str_replace($brand_name, '', $new_product->getName());
            $product_name = str_replace(strtoupper($brand_name), '', $product_name);
            #Mage::log('My log entry_'.$product_name, null, 'all.log');
            if (isset($firstCategoryId) && ($firstCategoryId == 42 || $firstCategoryId == 41) && $new_product->isConfigurable()) {
                if ($firstCategoryId == 42) {
                    $brand_id = Mage::helper('searchtyre')->getWheelBrandId($brand_name);
                    $style_id = Mage::helper('searchtyre')->getWheelStyleId($style_name);
                } else {
                    $brand_id = Mage::helper('searchtyre')->getTyreBrandId($new_product->getAttributeText('brand'));
                    #Mage::log('My log entry_'.$product->getImage().'_'.$product->getAttributeText('brand').'_'.$brand_id, null, 'category-tyres.log');
                }
                if ($new_product->getImage() == 'no_selection' || $new_product->getImage() == '') {
                    if ($firstCategoryId == 42) {
                        $type   = array(
                            'image',
                            'thumbnail',
                            'small_image',
                            ''
                        );
                        $images = Mage::helper('searchtyre')->getWheelImage($style_id, $brand_id, trim($product_name));
                        #Mage::log($images, null, 'images-1.log');					
                        #Mage::log($new_product->getStyle().'My log entry_'.$new_product->getId().'_'.$style_id.'_'.$brand_id.'_'.$product_name.'_'.$firstCategoryId.'_', null, 'wheels.log');					
                    } else {
                        $type   = array(
                            'thumbnail',
                            'small_image',
                            'image',
                            ''
                        );
                        $images = Mage::helper('searchtyre')->getTyreImage($brand_id, trim($product_name));
                        #Mage::log('My log entry_'.$new_product->getImage().'_'.$brand_id.'_'.$product_name.'_'.$firstCategoryId, null, 'tyres.log');					
                    }
                        $changed = false;
                        for ($i = 0; $i < count($images); $i++) {
                            $image_url = $images[$i]; //get external image url from csv
                            if ($image_url != '') {
                                #Mage::log($product_name . $images[$i], null, 'images.log');
                                $image_type = substr(strrchr($image_url, "."), 1); //find the image extension
                                $filename   = md5($image_url . $sku) . '.' . $image_type; //give a new name
                                $filepath   = Mage::getBaseDir('media') . DS . 'import' . DS . $filename; //path for temp storage folder: ./media/import/
                                file_put_contents($filepath, file_get_contents(trim($image_url))); //store the image from external url to the temp storage
                                $new_product->addImageToMediaGallery($filepath, $type[$i], false, false);
                                $changed = true;
                            }
                        }
                        #$product->getResource()->save($product);
                    if ($changed) {
                        $new_product->save();
                    }
                }
            }
        }
        self::$_singletonFlag = FALSE;
    }
}