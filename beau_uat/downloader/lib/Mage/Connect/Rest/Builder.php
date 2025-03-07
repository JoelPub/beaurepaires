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
 * @package     Mage_Connect
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Class for retrieve adapter to work with remote REST interface
 *
 * @category    Mage
 * @package     Mage_Connect
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Connect_Rest_Builder
{
    /**
     * Rest adapter factory
     *
     * @var Mage_Connect_Rest_Factory
     */
    protected static $_adapterFactory;

    /**
     * Retrieve adapter factory
     *
     * @return Mage_Connect_Rest_Factory
     */
    protected static function _getAdapterFactory()
    {
        if (self::$_adapterFactory === null) {
            self::$_adapterFactory = new Mage_Connect_Rest_Factory();
        }
        return self::$_adapterFactory;
    }

    /**
     * Define rest adapter factory
     *
     * @param Mage_Connect_Rest_Factory $adapterFactory
     */
    public static function setAdapterFactory(Mage_Connect_Rest_Factory $adapterFactory)
    {
        self::$_adapterFactory = $adapterFactory;
    }

    /**
     * Retrieve rest adapter
     *
     * @param string $protocol
     * @return Mage_Connect_Rest
     */
    public static function getAdapter($protocol = "https")
    {
        return self::_getAdapterFactory()->getAdapter($protocol);
    }
}
