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

namespace Mage\Bundle\Test\Constraint;

use Mage\Bundle\Test\Fixture\BundleProduct;
use Mage\Catalog\Test\Constraint\AssertProductPage;

/**
 * Verify displayed product price on product page(front-end) equals passed from fixture.
 */
class AssertBundleProductPage extends AssertProductPage
{
    /**
     * Verify displayed product price on product page(front-end) equals passed from fixture.
     *
     * @return string|null
     */
    protected function verifyPrice()
    {
        $errors = [];
        $priceData = $this->product->getDataFieldConfig('price')['source']->getPriceData();
        $priceBlock = $this->catalogProductView->getBundleViewBlock()->getPriceBlock();
        $priceLow = ($this->product->getPriceView() == 'Price Range')
            ? $priceBlock->getPriceFrom()
            : $priceBlock->getRegularPrice();
        $priceTo = $priceBlock->getPriceTo();

        if ($priceData['price_from'] != $priceLow) {
            $errors[] = "Bundle price 'From' on product view page is not correct:"
                . "\n$priceLow != {$priceData['price_from']}";
        }
        if ($this->product->getPriceView() == 'Price Range' && $priceData['price_to'] != $priceTo) {
            $errors[] = "Bundle price 'To' on product view page is not correct:"
                . "\n$priceTo != {$priceData['price_to']}";
        }

        return empty($errors) ? null : implode("\n", $errors);
    }
}
