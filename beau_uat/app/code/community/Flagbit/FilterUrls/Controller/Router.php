<?php

/**
 * This file is part of the Flagbit_FilterUrls project.
 *
 * Flagbit_FilterUrls is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Michael Türk <michael.tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */

/**
 * Router that translates FilterUrls URLs into the default Zend_Framework router's version.
 * FilterUrls URLs have a pre-defined structure
 * <category-rewrite-without-suffix>/<option_label_1>-<option_label_2><url-suffix>
 *
 * The router tries to parse the given pathinfo using the parser model and sets the parameters if the parsing was
 * successful. On success the whole request is dispatched and the routing process is complete.
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Michael Türk <michael.tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Controller_Router extends IWD_StoreLocator_Controller_Router { // Mage_Core_Controller_Varien_Router_Standard

    /**
     * Helper function to register the current router at the front controller.
     *
     * @param Varien_Event_Observer $observer The event observer for the controller_front_init_routers event
     * @event controller_front_init_routers
     */
    public function addFilterUrlsRouter($observer) {
        $front = $observer->getEvent()->getFront();

        $filterUrlsRouter = new Flagbit_FilterUrls_Controller_Router();
        $front->addRouter('filterurls', $filterUrlsRouter);

        $this->_checkForVehicleFilter($front);
    }

    private function _updateTyreVehiclePostValues($arx, $json_tyre) {
                        
        //SETTING POST DATA THIS FUNCTION OCCURS EVEN NOT COMING FROM POST DATA
        $_POST['make-tyres'] = $json_tyre->Make->Id;
        $_POST['year-tyres'] = $json_tyre->ModelYear;
        $_POST['model-tyres'] = $json_tyre->Model->Id;
        $_POST['series-tyres'] = $arx[4];
        $_POST['type'] = "tyres";
        $_POST['section'] = "vehicles";
        $_POST['category'] = "41";
        $_POST['tyre-vehicle-btn'] = "Search";
        $_POST['tyre-series-name'] = $json_tyre->Name;
        
        // Set wheel sizes to override wheel size session values if set        
        Mage::getSingleton('core/session')->setWidthF($json_tyre->OEFitmentData->FrontTyres->SectionWidth);
        Mage::getSingleton('core/session')->setProfileF($json_tyre->OEFitmentData->FrontTyres->AspectRatio);
        Mage::getSingleton('core/session')->setDiameterF($json_tyre->OEFitmentData->FrontTyres->RimDiameter);
        	
    }
	
	
    private function _updateWheelsVehiclePostValues($arx, $json_tyre) {
        unset($_POST);
        $_POST['make-tyres'] = $json_tyre->Make->Id;
        $_POST['year-tyres'] = $json_tyre->ModelYear;
        $_POST['model-tyres'] = $json_tyre->Model->Id;
        if(isset($arx[4]))
        $_POST['series-tyres'] = $arx[4];
        
        $_POST['type'] = "wheels";
        $_POST['section'] = "vehicles";
        $_POST['category'] = "42";
        $_POST['tyre-vehicle-btn'] = "Search";
        $_POST['tyre-series-name'] = $json_tyre->Name;
                        
        if(isset($arx[4]))        
        Mage::getSingleton('core/session')->setSeriesF(intval($arx[4]));
        
        Mage::getSingleton('core/session')->setTMakeF($json_tyre->Make->Id);
        Mage::getSingleton('core/session')->setTModelF($json_tyre->Model->Id);
        Mage::getSingleton('core/session')->setTYearF($json_tyre->ModelYear);
                
    }	
	 
    
    private function _updateTyreSizePostValues($arx) {                
        if (!empty($arx[2])) {
            $sizes = explode('_', $arx[2]);
            $_POST['width-tyres'] = $sizes[0];
            $_POST['profile-tyres'] = $sizes[1];
            $_POST['diameter-tyres'] = $sizes[2];
            $_POST['type'] = "tyres";
            $_POST['section'] = "size";
            $_POST['category'] = "41";
            $_POST['tyre-vehicle-btn'] = "Search";
        }
    }
    
    private function _checkForVehiclefilter($front) {
		//Brands to CMS automation
		Mage::helper('apdinteract_catalog')->automatebrands();
        $session = Mage::getSingleton('core/session');
        $request = $front->getRequest();
        $request_uri = $request->getRequestUri();
        $arx = explode("/", $request_uri);
        $_POST['productcodetyres'] = Mage::getSingleton('core/session')->getProductCodeTyres();
        $_POST['productcodewheels'] = Mage::getSingleton('core/session')->getProductCodeWheels();
                        
        if ($arx[1] == 'tyresearch') {
            
            $json_tyre = Mage::helper('searchtyre')->getCacheTyreData($arx[4]);
            $arx[6] = intval($arx[6]);
            if ($arx[6] >= 1990) {
                $json_tyre->ModelYear = $arx[6];
                Mage::getSingleton('core/session')->setTYearF($arx[6]);
            }
            
            $this->_updateTyreVehiclePostValues($arx, $json_tyre); 
            $request->setRequestUri("searchresult/index/index/type/tyres/section/vehicle");            
            
            
        } else if ($arx[1] == 'wheels' && (!isset($arx[2]) || $arx[2] == '')) {            
            if (Mage::getSingleton('core/session')->getSeriesF() != '') {
                $json_tyre = Mage::helper('searchtyre')->getCacheTyreData(Mage::getSingleton('core/session')->getSeriesF()); 
                
                $year = intval(Mage::getSingleton('core/session')->getTYearF());
                if ($year >= 1990) {
                    $json_tyre->ModelYear = $year;
                }
                
                $arx[4] = Mage::getSingleton('core/session')->getSeriesF();
                
               
                $this->_updateWheelsVehiclePostValues($arx, $json_tyre); 
                
                $_POST['type']=='wheels';
                               
                Mage::app()->getFrontController()->getResponse()->setRedirect("/searchresult/index/index/type/wheels/section/vehicle");
            }
        } else if ($arx[1] == 'tyres' && (!isset($arx[2]) || $arx[2] == '')) {
            if (Mage::getSingleton('core/session')->getSeriesF() != '') {
                $json_tyre = Mage::helper('searchtyre')->getCacheTyreData(Mage::getSingleton('core/session')->getSeriesF()); 
                
                $year = intval(Mage::getSingleton('core/session')->getTYearF());
                if ($year >= 1990) {
                    $json_tyre->ModelYear = $year;
                }
                
                $arx[4] = Mage::getSingleton('core/session')->getSeriesF();
                $this->_updateTyreVehiclePostValues($arx, $json_tyre); 
                Mage::app()->getFrontController()->getResponse()->setRedirect("/searchresult/index/index/type/tyres/section/vehicle");
            }
        }
        
        
        else if ($arx[1] == 'tyresize') {
            $this->_updateTyreSizePostValues($arx);
            $request->setRequestUri("searchresult/index/index/type/tyres/section/size");
            
        } 
        else if ($arx[1] == 'wheelsearch' && isset($arx[4])) {
           
            if (Mage::getSingleton('core/session')->getSeriesF() != '') {
                $json_tyre = Mage::helper('searchtyre')->getCacheTyreData(Mage::getSingleton('core/session')->getSeriesF());              
            } else {                 
                $json_tyre = Mage::helper('searchtyre')->getCacheTyreData($arx[4]);
            }
            
            $arx[6] = intval($arx[6]);
            if ($arx[6] >= 1990) {
                $json_tyre->ModelYear = $arx[6];
                Mage::getSingleton('core/session')->setTYearF($arx[6]);
            }
            
            Mage::getSingleton('core/session')->setTSeriesNameF($json_tyre->Name);
            
            $this->_updateWheelsVehiclePostValues($arx, $json_tyre); 
            $request->setRequestUri("searchresult/index/index/type/wheels/section/vehicle");
           // $request->setRequestUri("searchresult/index/index/type/wheels/section/vehicle");
            
        }

        else if ($arx[1] == 'wheelsearch' && !isset($arx[4])) {        
            $request->setRequestUri("wheels");
        }
		
		else if (isset($arx[7]) && $arx[7] == 'vehicle') {
            if (Mage::getSingleton('core/session')->getSeriesF() != '') {
                $json_tyre = Mage::helper('searchtyre')->getCacheTyreData(Mage::getSingleton('core/session')->getSeriesF());
                $arx[4] = Mage::getSingleton('core/session')->getSeriesF();
                $json_tyre->ModelYear = Mage::getSingleton('core/session')->getTYearF();
                if($arx[5]=='wheels')
                $this->_updateWheelsVehiclePostValues($arx, $json_tyre); 
                else
                $this->_updateTyreVehiclePostValues($arx, $json_tyre);    
            }
        } 
		
		else if (isset($arx[7]) && $arx[7] == 'size') {	
		$_POST['width-tyres'] = Mage::getSingleton('core/session')->getWidthF();
		$_POST['profile-tyres'] = Mage::getSingleton('core/session')->getProfileF();
		$_POST['diameter-tyres'] = Mage::getSingleton('core/session')->getDiameterF();
		$_POST['section'] = 'size';
        } 		
		
    }

    /**
     * Rewritten function of the standard controller. Tries to match the pathinfo on url parameters.
     *
     * @see Mage_Core_Controller_Varien_Router_Standard::match()
     * @param Zend_Controller_Request_Http $request The http request object that needs to be mapped on Action Controllers.
     */
    public function match(Zend_Controller_Request_Http $request) {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                    ->setRedirect(Mage::getUrl('install'))
                    ->sendResponse();
            exit;
        }

        $identifier = trim($request->getPathInfo(), '/');

        // try to gather url parameters from parser.
        /* @var $parser Flagbit_FilterUrls_Model_Parser */
        $parser = Mage::getModel('filterurls/parser');
        $parsedRequestInfo = $parser->parseFilterInformationFromRequest($identifier, Mage::app()->getStore()->getId());

        if (!$parsedRequestInfo) {
            return false;
        }

        // Canonical URLs
        // eg /tyres/filter1-filter2-filter3
        // Canonical should be   <link rel="canonical" href="/tyres/filter1" />
        // $identifier = "batteries/filter1-filter2-filter3"
        // see also Block/Category/View.php

        $pattern = '/([^\/]+\/[^-]+).*/';
        $matches = array();
        preg_match($pattern, $identifier, $matches);
        $canonical_link = $matches[1]; // '/tyres/goodyear';
        Mage::register('canonical_link', $canonical_link);

        Mage::register('filterurls_active', true);

        // if successfully gained url parameters, use them and dispatch ActionController action
        $request->setRouteName('catalog')
                ->setModuleName('catalog')
                ->setControllerName('category')
                ->setActionName('view')
                ->setParam('id', $parsedRequestInfo['categoryId']);
        $pathInfo = 'catalog/category/view/id/' . $parsedRequestInfo['categoryId'];
        $requestUri = '/' . $pathInfo . '?';

        foreach ($parsedRequestInfo['additionalParams'] as $paramKey => $paramValue) {
            $requestUri .= $paramKey . '=' . $paramValue . '&';
        }

        $controllerClassName = $this->_validateControllerClassName('Mage_Catalog', 'category');
        $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $this->getFront()->getResponse());

        $request->setRequestUri(substr($requestUri, 0, -1));
        $request->setPathInfo($pathInfo);

        // dispatch action
        $request->setDispatched(true);
        $controllerInstance->dispatch('view');

        $request->setAlias(
                Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $identifier
        );

        return true;
    }

}
