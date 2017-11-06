<?php
/**
 * ApdInteract
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.

 * @category    ApdInteract Mod
 * @package     ApdInteract_Smartcache
 * @author      ApdInteract Core Team
 * @copyright   Copyright (c) 2014 ApdInteract (http://www.apdinteract.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class ApdInteract_Smartcache_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $_skinLastChangedHash;
    
    public function getSkinLastChangedHash()
    {
        if (!isset($this->_skinLastChangedHash))
        {
            $dir = Mage::getBaseDir('skin');
            $lastChangedTime = filemtime($dir . '/frontend/polar/default/dist/static/app.css');
            $this->_skinLastChangedHash = md5($lastChangedTime);
        }        
        return $this->_skinLastChangedHash;
    }
}