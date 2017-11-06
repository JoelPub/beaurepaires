<?php
class ApdInteract_Syncer_Model_Importer extends Wisepricer_Syncer2_Model_Importer
{
 

    /**
     * Read csv file and prepares array for future reprice.
     *
     * @param $csvFile
     * @return array
     * @throws Exception
     * @throws Mage_Core_Exception
     */
    protected function prepareProductsForReprice($csvFile)
    {        
        
        $this->getHelper()->log("Reading downloaded CSV file: $csvFile");
        $products = array();

        $io = new Varien_Io_File();
        $io->streamOpen($csvFile, 'r');

        $line = 1;
        $attributes = $this->getAttributesFromCsvFile($io);
        while ($csvData = $io->streamReadCsv()) {
            $sku  = $csvData[$attributes['sku']];

            if (isset($products[$sku])) {
                $io->streamClose();
                $this->getHelper()->exception("Duplicate sku '$sku' found in ".basename($csvFile).":$line");
            }
			
			/*$_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$_sku);
			$flag = (int)$_product->getNoupdatebywiser();*/
			
			
			
			$product = Mage::getModel('catalog/product');
			$id = Mage::getModel('catalog/product')->getResource()->getIdBySku($sku);			
			$_product = $product->load($id);
			$flag = (int)$_product->getNoupdatebywiser();
			//$this->getHelper()->log("SKU: $sku -".$flag);
            $data = array();
            
            if($flag<=0) {            	       
            	
				foreach ($attributes as $attributeCode => $index) {
            	
            	
	                if ($attributeCode === 'sku') {
	                    continue;
	                }
	                $data[$attributeCode] = $csvData[$index];
	            }

	            $products[$sku] = $data;

	            $line++;	
			} 
            
        }

        $io->streamClose();
        $this->getHelper()->log("Finished reading downloaded CSV file: $csvFile");

        return $products;
    }

    

}
