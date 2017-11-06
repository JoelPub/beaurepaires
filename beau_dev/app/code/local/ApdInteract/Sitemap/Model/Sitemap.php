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
 * @package     Mage_Sitemap
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */


/**
 * Sitemap model
 *
 * @method Mage_Sitemap_Model_Resource_Sitemap _getResource()
 * @method Mage_Sitemap_Model_Resource_Sitemap getResource()
 * @method string getSitemapType()
 * @method Mage_Sitemap_Model_Sitemap setSitemapType(string $value)
 * @method string getSitemapFilename()
 * @method Mage_Sitemap_Model_Sitemap setSitemapFilename(string $value)
 * @method string getSitemapPath()
 * @method Mage_Sitemap_Model_Sitemap setSitemapPath(string $value)
 * @method string getSitemapTime()
 * @method Mage_Sitemap_Model_Sitemap setSitemapTime(string $value)
 * @method int getStoreId()
 * @method Mage_Sitemap_Model_Sitemap setStoreId(int $value)
 *
 * @category    Mage
 * @package     Mage_Sitemap
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_Sitemap_Model_Sitemap extends Mage_Sitemap_Model_Sitemap
{
    /**
     * Real file path
     *
     * @var string
     */
    protected $_filePath;    
    protected $_lastModified;
    protected $_changeFrequency;
    protected $_priority;
    protected $_urls;
    protected $urls = array();

    /**
     * Init model
     */
    protected function _construct()
    {
        $this->_init('sitemap/sitemap');
    }

    protected function _beforeSave()
    {
        $io = new Varien_Io_File();
        $realPath = $io->getCleanPath(Mage::getBaseDir() . '/' . $this->getSitemapPath());

        /**
         * Check path is allow
         */
        if (!$io->allowedPath($realPath, Mage::getBaseDir())) {
            Mage::throwException(Mage::helper('sitemap')->__('Please define correct path'));
        }
        /**
         * Check exists and writeable path
         */
        if (!$io->fileExists($realPath, false)) {
            Mage::throwException(Mage::helper('sitemap')->__('Please create the specified folder "%s" before saving the sitemap.', Mage::helper('core')->escapeHtml($this->getSitemapPath())));
        }

        if (!$io->isWriteable($realPath)) {
            Mage::throwException(Mage::helper('sitemap')->__('Please make sure that "%s" is writable by web-server.', $this->getSitemapPath()));
        }
        /**
         * Check allow filename
         */
        if (!preg_match('#^[a-zA-Z0-9_\.]+$#', $this->getSitemapFilename())) {
            Mage::throwException(Mage::helper('sitemap')->__('Please use only letters (a-z or A-Z), numbers (0-9) or underscore (_) in the filename. No spaces or other characters are allowed.'));
        }
        if (!preg_match('#\.xml$#', $this->getSitemapFilename())) {
            $this->setSitemapFilename($this->getSitemapFilename() . '.xml');
        }

        $this->setSitemapPath(rtrim(str_replace(str_replace('\\', '/', Mage::getBaseDir()), '', $realPath), '/') . '/');

        return parent::_beforeSave();
    }

    /**
     * Return real file path
     *
     * @return string
     */
    protected function getPath()
    {
        if (is_null($this->_filePath)) {
            $this->_filePath = str_replace('//', '/', Mage::getBaseDir() .
                $this->getSitemapPath());
        }
        return $this->_filePath;
    }

    /**
     * Return full file name with path
     *
     * @return string
     */
    public function getPreparedFilename()
    {
        return $this->getPath() . $this->getSitemapFilename();
    }

    private function _xmlComment($string, $io) {
        $string = ucfirst($string);
        $io->streamWrite("\n<!-- {$string} -->\n");
    }
    
    private function _getConfigValue($section_name, $type) {
        $storeId = $this->getStoreId();
        return (string)Mage::getStoreConfig('sitemap/'.$section_name.'/'.$type, $storeId);
    }
    
    private function _getChangeFrequency($section_name) {
        return $this->_getConfigValue($section_name, 'changefreq');
    }
    
    private function _getPriority($section_name) {
        return $this->_getConfigValue($section_name, 'priority');
    }
       
    private function _getLastMod() {
        if (!isset($this->_lastModified)) {
           $this->_lastModified = Mage::getSingleton('core/date')->gmtDate('Y-m-d'); 
        }
        return $this->_lastModified;
    }
    
    private function _addSitemapEntry($url, $section_name, $io) {
            if(!is_array($this->urls))
                $this->urls = array();
                
        if (!in_array($url, $this->urls)) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($url),
                $this->_getLastMod(),
                $this->_getChangeFrequency($section_name),
                $this->_getPriority($section_name)
            );
            $io->streamWrite($xml);
            $this->urls[] = $url;
        }

        
    }
    
    /**
     * Generate XML file
     *
     * @return Mage_Sitemap_Model_Sitemap
     */
    public function generateXml()
    {
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));

        if ($io->fileExists($this->getSitemapFilename()) && !$io->isWriteable($this->getSitemapFilename())) {
            Mage::throwException(Mage::helper('sitemap')->__('File "%s" cannot be saved. Please, make sure the directory "%s" is writeable by web server.', $this->getSitemapFilename(), $this->getPath()));
        }

        $io->streamOpen($this->getSitemapFilename());

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        $io->streamWrite('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');

        $storeId = $this->getStoreId();
//        $date    = Mage::getSingleton('core/date')->gmtDate('Y-m-d');        
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
        
        /**
         * Generate home page sitemap
         */        
        $section_name = 'homepage';
        $this->_xmlComment($section_name, $io);       
        $this->_addSitemapEntry($baseUrl, $section_name, $io);

        /**
         * Generate categories sitemap
         */        
        $section_name = 'category';
        $this->_xmlComment($section_name, $io);                    

        $collection = Mage::getResourceModel('sitemap/catalog_category')->getCollection($storeId);
        $categories = new Varien_Object();
        $categories->setItems($collection);
        Mage::dispatchEvent('sitemap_categories_generating_before', array(
            'collection' => $categories
        ));
        foreach ($categories->getItems() as $item) {
            $this->_addSitemapEntry($baseUrl . $item->getUrl(), $section_name, $io);
        }
        unset($categories);
        
        /**
         * Generate subcategories sitemap
         */        
        $section_name = 'subcategories';
        $this->_xmlComment($section_name, $io);         
        /* @var $collection Flagbit_FilterUrls_Model_Resource_Mysql4_Url_Collection */
        $collection = Mage::getModel('filterurls/url')->getCollection();
        $collection->addFieldToFilter('store_id', $storeId);        
        
        foreach ($collection as $item) {
            $this->_addSitemapEntry($baseUrl . $item->getRequestPath() . $item->getUrl(), $section_name, $io);
        }
        unset($collection);
        
        /**
         * Generate store locator sitemap
         */        
        $section_name = 'storelocator';
        $this->_xmlComment($section_name, $io); 
        $storeLocatorDefaultPath = (string)Mage::getStoreConfig('storelocator/global/route', $storeId);
        $this->_addSitemapEntry($baseUrl . $storeLocatorDefaultPath, $section_name, $io);
        
        /**
         * Generate store locator details sitemap
         */        
        $section_name = 'storelocatordetails';
        $this->_xmlComment($section_name, $io); 
        // $storeLocatorDefaultPath   = (string)Mage::getStoreConfig('storelocator/global/route', $storeId);
        $collection = Mage::getResourceModel('sitemap/store_storelocator')->getCollection($storeId);
        foreach ($collection->getItems() as $item) {
            $this->_addSitemapEntry($baseUrl . $storeLocatorDefaultPath . '/' . $item->getUrl(), $section_name, $io);
        }
        unset($collection);

        /**
         * Generate products sitemap
         */        
        $section_name = 'product';
        $this->_xmlComment($section_name, $io);
        $collection = Mage::getResourceModel('sitemap/catalog_product')->getCollection($storeId);
        $products = new Varien_Object();
        $products->setItems($collection);
        Mage::dispatchEvent('sitemap_products_generating_before', array(
            'collection' => $products
        ));
        foreach ($products->getItems() as $item) {
            $this->_addSitemapEntry($baseUrl . $item->getUrl(), $section_name, $io);
        }
        unset($collection);

        /**
         * Generate cms pages sitemap
         */        
        $section_name = 'page';
        $this->_xmlComment($section_name, $io);        
        $collection = Mage::getResourceModel('sitemap/cms_page')->getCollection($storeId);
        foreach ($collection as $item) {
            $this->_addSitemapEntry($baseUrl . $item->getUrl(), $section_name, $io);
        }
        unset($collection);

        
        $io->streamWrite('</urlset>');
        $io->streamClose();

        $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }
}
