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
* Class Model
*
* @category   Mage
* @package    Mage_Connect
* @copyright  Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class Maged_Model
{

    /**
    * Internal cache
    *
    * @var array
    */
    protected $_data;

    /**
    * Constructor
    */
    public function __construct()
    {
        $args = func_get_args();
        if (empty($args[0])) {
            $args[0] = array();
        }
        $this->_data = $args[0];

        $this->_construct();
    }

    /**
    * Constructor for covering
    */
    protected function _construct()
    {

    }

    /**
    * Retrieve controller
    * @return Maged_Controller
    */
    public function controller()
    {
        return Maged_Controller::singleton();
    }

    /**
    * Set value for key
    *
    * @param string $key
    * @param mixed $value
    * @return Maged_Model
    */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
    * Get value by key
    *
    * @param string $key
    * @return mixed
    */
    public function get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }
}
