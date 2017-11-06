<?php

class ApdInteract_Customreindex_Model_Observer extends Mage_Core_Model_Observer
{
    public function runindexer(Varien_Event_Observer $observer = null)
    {
        $indexed_something = false;
        
		$indexer_info = array('catalog_category_flat','catalog_product_flat','cataloginventory_stock','catalog_product_price','catalog_url_category','catalog_url_product',
							  'url_redirect','catalog_category_product','catalogsearch_fulltext','catalog_product_attribute','tag_summary');

        foreach ($indexer_info as $code) {

            $process = Mage::getModel('index/indexer')->getProcessByCode($code);

            if ($process) {
                $process->reindexAll(); 
                Mage::log('sample log :' . $code . ' ' . $process->getStatus(), null, 'reindex.log');
                $indexed_something = true;
            }
        }
        
        return $indexed_something;
    }
    
    public function executeIndexer(){
    	define('MAGENTO_ROOT', getcwd());
    	$indexer = MAGENTO_ROOT . "/shell/indexer.php --reindexall";
    	$output = shell_exec('php ' . $indexer);
    }
}
