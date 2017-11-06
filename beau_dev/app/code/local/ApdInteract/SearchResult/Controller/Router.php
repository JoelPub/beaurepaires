<?php
class ApdInteract_SearchResult_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    const NEW_PRODUCTS_URL_KEY = 'search-result';
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('apdinteract_searchresult', $this);
        return $this;
    }

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
        $urlKey = trim($request->getPathInfo(), '/');
        if ($urlKey == self::NEW_PRODUCTS_URL_KEY) {
            $request->setModuleName('searchresult')
                ->setControllerName('index')
                ->setActionName('index');
            $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                $urlKey
            );
            return true;
        }
        return false;
    }
}