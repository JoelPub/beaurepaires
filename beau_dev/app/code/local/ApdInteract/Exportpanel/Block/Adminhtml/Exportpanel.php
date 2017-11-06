<?php

class ApdInteract_Exportpanel_Block_Adminhtml_Exportpanel extends Mage_Adminhtml_Block_Widget_Form
{
   const CUSTOMER_EXPORT_PATH = 'customers';
   const PRODUCT_EXPORT_PATH = 'products';
   const PRODUCT_REVIEW_EXPORT_PATH = 'product_reviews';
   const PRODUCT_INVENTORY_EXPORT_PATH = 'product_inventory';
   const SALES_ORDER_EXPORT_PATH = 'sales_order';

   /**
    * List  exported CSV from customer and vehicle details
    * @return array|bool
    *
    */
   public function loadExportCustomer(){
      return $this->listCSV(self::CUSTOMER_EXPORT_PATH);
   }

   /**
    * List exported CSV from products
    * @return array
    */
   public function loadExportProducts(){
      return $this->listCSV(self::PRODUCT_EXPORT_PATH);
   }

   /**
    * List exported CSV from products reviews
    * @return array
    */
   public function loadExportProductReviews(){
      return $this->listCSV(self::PRODUCT_REVIEW_EXPORT_PATH);
   }

   /**
    * List exported CSV from products reviews
    * @return array
    */
   public function loadExportProductInventory(){
      return $this->listCSV(self::PRODUCT_INVENTORY_EXPORT_PATH);
   }


   /**
    * List exported CSV from sales order
    * @return array
    *
    */
   public function loadExportSalesOrder(){

      return $this->listCSV(self::SALES_ORDER_EXPORT_PATH);
   }

   /**
    * @param $pathname
    * @return array
    * @throws Exception
    */
    public function listCSV($pathname){

       $file = new Varien_Io_File();
       $multi_arr = array();
       $dir = Mage::getBaseDir('var') . DS . 'export' . DS . $pathname . DS;

       if(is_dir($dir)){
          $file->open(array('path' => $dir));
          $lists = $file->ls();
          $items = array();

          if(count($lists) > 0 ){

             foreach($lists as $item){
                $items['path_location'] = $dir;
                $items['text'] = $item['text'];
                $items['mod_date'] = $item['mod_date'];
                $items['size'] = $this->_convertSize($item['size']);

                $multi_arr[] = $items;
             }
          }
       }
       return $multi_arr;
    }

   /**
    * Convert size
    *
    * @param $size
    * @return string
    */
   private function _convertSize($size){

      if ($size > 0) {
         $unit = intval(log($size, 1024));
         $units = array('B', 'KB', 'MB', 'GB');

         if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $size / pow(1024, $unit), $units[$unit]);
         }

         return $size;
      }
   }

   /**
    * @param null $path
    * @param null $type
    * @return string
    *
    */
   public function getRowUrl($type = null,$file = null){
      return $this->getUrl('*/*/download', array('type'=>$type,'file'=> $file));
   }
}