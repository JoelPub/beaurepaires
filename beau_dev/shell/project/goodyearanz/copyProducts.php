<?php

require_once(dirname(__FILE__)."/../../abstract.php");

/**
 * @category    Goodyear
 * @package     Goodyear_Shell
 * @author      Jagdeep Singh
 */

class anz_Shell_Goodyear_products extends Mage_Shell_Abstract {

    const BEAU_WEBSITE_CODE = 'default';
    const GOODYEAR_AU_WEBSITE_CODE = 'goodyear_au';
    const GOODYEAR_NZ_WEBSITE_CODE = 'goodyear_nz';

    /**
     * Run script
     *
     */
    public function run() {

        if( $i=$this->getArg('copyBeauProductsToGoodyearANZWebsite') ){
            echo "\n";
            echo $result = $this->copyBeauProductsToGoodyearANZWebsite();
            echo "\n";
        }else{
            echo $this->usageHelp();
        }

    }

    /**
     * Copy Beaureapaires products (with Brand : Goodyear) into Goodyear AU and NZ
     *
     */
    public function copyBeauProductsToGoodyearANZWebsite(){

        //get Magento productsIds matched with StoreFront JM Products.
        $productIdArray = $this->getGoodyearBrandProductIds();

        $productCount = count($productIdArray);
        echo "\nNumber of Goodyear Brancd Products : ".$productCount."\n";

        if($productCount<1)
            die("No Product assigned to Goodyear Brand \n");

        try {
            $goodyearAuWebsiteId = Mage::app()->getWebsite(self::GOODYEAR_AU_WEBSITE_CODE)->getId();
            $goodyearNzWebsiteId = Mage::app()->getWebsite(self::GOODYEAR_NZ_WEBSITE_CODE)->getId();
        }catch(Exception $e) {
            echo 'ERROR:: ' .$e->getMessage()."\n";
        }

        if(empty($goodyearAuWebsiteId))
            die("ERROR :: Website Id not found for Goodyear AU website with code : ".self::GOODYEAR_AU_WEBSITE_CODE."\n");

        if(empty($goodyearNzWebsiteId))
            die("ERROR:: Website Id not found for Goodyear NZ website with code : ".self::GOODYEAR_NZ_WEBSITE_CODE."\n");

        $websiteIdArray = array($goodyearAuWebsiteId,$goodyearNzWebsiteId);

        echo "\n Please wait ....... \n";

        try {
            Mage::getModel('catalog/product_website')->addProducts($websiteIdArray, $productIdArray);
        }catch(Exception $e) {
            echo 'ERROR:: ' .$e->getMessage()."\n";
            die();

        }
        echo "\n Done, Products Copy successfully from Beaurepaires to Goodyear AU and NZ. \n";

    }

    /**
     * Get products id with branch Goodyear from Beaurepaires
     * @return array
     */
    public function getGoodyearBrandProductIds(){
        $productIdArray = array();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query        = "select entity_id from catalog_product_entity_int where value = 278 and attribute_id=245";
        $rows       = $connection->fetchAll($query);
        $count = count($rows);
        if($count>0){
            foreach($rows as $row){
                $productIdArray[] = $row['entity_id'];
            }
        }

        return $productIdArray;

    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp() {

        return <<<USAGE
    This script will create following :
    Assign Goodyear produts from Beaureapaires website to Goodyear AU/NZ webiste who's Brand name is Goodyear.
    Use following command

        php copyProducts.php -copyBeauProductsToGoodyearANZWebsite


USAGE;

    }
}

$shell = new anz_Shell_Goodyear_products();
$shell->run();
