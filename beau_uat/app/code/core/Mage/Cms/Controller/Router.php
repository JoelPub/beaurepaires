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
 * @package     Mage_Cms
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */


/**
 * Cms Controller Router
 *
 * @category    Mage
 * @package     Mage_Cms
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Cms_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     */
    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();

        $front->addRouter('cms', $this);
    }

    /**
     * Validate and Match Cms Page and modify request
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $identifier = trim($request->getPathInfo(), '/');

        $condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue'   => true
        ));
        Mage::dispatchEvent('cms_controller_router_match_before', array(
            'router'    => $this,
            'condition' => $condition
        ));
        $identifier = $condition->getIdentifier();

        if ($condition->getRedirectUrl()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getRedirectUrl())
                ->sendResponse();
            $request->setDispatched(true);
            return true;
        }

        if (!$condition->getContinue()) {
            return false;
        }

        $page   = Mage::getModel('cms/page');
        $pageId = $page->checkIdentifier($identifier, Mage::app()->getStore()->getId());
        if (!$pageId) {
            return false;
        }

        $request->setModuleName('cms')
            ->setControllerName('page')
            ->setActionName('view')
            ->setParam('page_id', $pageId);
        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );

        return true;
    }
}
