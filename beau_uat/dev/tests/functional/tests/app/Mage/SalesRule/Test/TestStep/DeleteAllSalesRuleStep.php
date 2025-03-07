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

namespace Mage\SalesRule\Test\TestStep;

use Mage\SalesRule\Test\Page\Adminhtml\PromoQuoteEdit;
use Mage\SalesRule\Test\Page\Adminhtml\PromoQuoteIndex;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Delete all sales rules on backend.
 */
class DeleteAllSalesRuleStep implements TestStepInterface
{
    /**
     * Promo Quote index page.
     *
     * @var PromoQuoteIndex
     */
    protected $promoQuoteIndex;

    /**
     * Promo Quote edit page.
     *
     * @var PromoQuoteEdit
     */
    protected $promoQuoteEdit;

    /**
     * @constructor
     * @param PromoQuoteIndex $promoQuoteIndex
     * @param PromoQuoteEdit $promoQuoteEdit
     */
    public function __construct(PromoQuoteIndex $promoQuoteIndex, PromoQuoteEdit $promoQuoteEdit)
    {
        $this->promoQuoteIndex = $promoQuoteIndex;
        $this->promoQuoteEdit = $promoQuoteEdit;
    }

    /**
     * Delete all sales rules on backend.
     *
     * @return array
     */
    public function run()
    {
        $this->promoQuoteIndex->open();
        $this->promoQuoteIndex->getPromoQuoteGrid()->resetFilter();
        while ($this->promoQuoteIndex->getPromoQuoteGrid()->isFirstRowVisible()) {
            $this->promoQuoteIndex->getPromoQuoteGrid()->openFirstRow();
            $this->promoQuoteEdit->getFormPageActions()->delete();
        }
    }
}
