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
 * @category    Tests
 * @package     Tests_Functional
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

namespace Mage\Page\Test\Block\Html;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class top menu navigation block.
 */
class Topmenu extends Block
{
    /**
     * Link with category name.
     *
     * @var string
     */
    protected $category = '//a[text()="%s"]';

    /**
     * Select category from top menu by name and click on it.
     *
     * @param string $categoryName
     * @return void
     */
    public function selectCategory($categoryName)
    {
        $this->_rootElement->find(sprintf($this->category, $categoryName), Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Check is visible category in top menu by name.
     *
     * @param string $categoryName
     * @return bool
     */
    public function isCategoryVisible($categoryName)
    {
        return $this->_rootElement->find(sprintf($this->category, $categoryName), Locator::SELECTOR_XPATH)->isVisible();
    }
}
