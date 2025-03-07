<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Sphinx Search Ultimate
 * @version   2.3.4
 * @build     1372
 * @copyright Copyright (C) 2016 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_MstCore_Helper_Config extends Mage_Core_Helper_Data
{
    const UPDATES_FEED_URL    = 'http://mirasvit.com/blog/category/updates/feed/';
    const EXTENSIONS_FEED_URL = 'http://mirasvit.com/pc/feed/';
    const STORE_URL           = 'http://mirasvit.com/estore/';
    const DEVELOPER_IP        = 'mstcore/logger/developer_ip';
    const NOTIFICATION_STATUS = 'mstcore/notification/status';

    public function getDeveloperIp()
    {
        $ips = explode(',', Mage::getStoreConfig(self::DEVELOPER_IP));

        return $ips;
    }

    /**
     * Is Mirasvit notifications enabled
     *
     * @return int
     */
    public function isNotificationsEnabled()
    {
        return (int)Mage::getStoreConfig(self::NOTIFICATION_STATUS);
    }
}

if (!function_exists('pr')) {
    function pr($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}