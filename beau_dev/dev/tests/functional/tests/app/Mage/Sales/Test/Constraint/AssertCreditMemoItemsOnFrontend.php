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

namespace Mage\Sales\Test\Constraint;

use Mage\Sales\Test\Page\CreditMemoView;
use Magento\Mtf\ObjectManager;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\System\Event\EventManagerInterface;

/**
 * Assert that credit memo items is equal to data from fixture on 'My Account' page.
 */
class AssertCreditMemoItemsOnFrontend extends AbstractAssertSalesEntityItemsOnFrontend
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Entity type.
     *
     * @var string
     */
    protected $entityType = 'refund';

    /**
     * Special fields for verify.
     *
     * @var array
     */
    protected $specialFields = [
        'item_price',
        'item_subtotal',
        'item_discount',
        'item_row_total'
    ];

    /**
     * @constructor
     * @param ObjectManager $objectManager
     * @param EventManagerInterface $eventManager
     * @param CreditMemoView $creditMemoView
     */
    public function __construct(
        ObjectManager $objectManager,
        EventManagerInterface $eventManager,
        CreditMemoView $creditMemoView
    )
    {
        parent::__construct($objectManager, $eventManager);
        $this->salesTypeViewPage = $creditMemoView;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Credit memo items quantity is equal to data from fixture on "My Account" page.';
    }
}
