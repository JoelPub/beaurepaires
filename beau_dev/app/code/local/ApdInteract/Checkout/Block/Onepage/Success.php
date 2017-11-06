<?php

class ApdInteract_Checkout_Block_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    /*
     * Return the Product Name and Url key from the Ordered item.
     * @param array $items
     * @return array
     */
    public function getDetails($items){
        foreach($items as $item){
            $simple = $this->getProductModel()->loadByAttribute('sku',$item->getSku());
                $category = $simple->getCategoryIds();
                if (isset($category[0]) && $category[0] == '43'){ // batteries
                    $link = $this->getProductLink($category[0],$simple->getUrlKey());
                    $data = array('product_name'=> $simple->getName(),
                                  'product_link'=> $link);
                }else{
                    $parentId = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($simple->getId());
                    $parent = $this->getProductModel()->load($parentId[0]);
                    $category = $parent->getCategoryIds();
                    $link = $this->getProductLink($category[0],$parent->getUrlKey());
                    $data = array('product_name'=> $parent->getName(),
                                  'product_link'=> $link);
                }
        }
        return $data;
    }

    /*
     * Return the Product Link of the Ordered item
     * @param int $catId
     * @param string $prodUrl
     * @return string
     */
    public function getProductLink($catId,$prodUrl){
        $url = Mage::getBaseUrl();
        switch ($catId) {
            case '41':
                $link = $url . 'tyres/' . $prodUrl;
                break;
            case '42':
                $link = $url . 'wheels/' . $prodUrl;
                break;
            case '43':
                $link = $url . 'batteries/' . $prodUrl;
                break;
            default:
                $link = $url;
                break;
        }
        return $link;
    }

    /*
     * Return the website url without the https/http
     * @return string
     */
    public function getWebsiteUrl(){
        $websiteUrl = '';
        $url =  preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', Mage::getBaseUrl()); // Remove / and : from https://
        if (strpos($url, 'http') !== false) {
            $websiteUrl = str_replace("http","",$url);
        }elseif (strpos($url, 'https') !== false) {
            $websiteUrl = str_replace("https","",$url);
        }
        return $websiteUrl;
    }

    /*
     * @return Sales Order model
     */
    public function getSalesModel(){
        return Mage::getModel('sales/order');
    }

    /*
     * @return Catalog Product model
     */
    public function getProductModel(){
        return Mage::getModel('catalog/product');
    }
}
