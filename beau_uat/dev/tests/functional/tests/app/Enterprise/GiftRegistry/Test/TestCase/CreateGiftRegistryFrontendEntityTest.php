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

namespace Enterprise\GiftRegistry\Test\TestCase;

use Mage\Catalog\Test\Fixture\CatalogProductSimple;
use Mage\Customer\Test\Fixture\Customer;
use Mage\Customer\Test\Page\CustomerAccountIndex;
use Mage\Customer\Test\Page\CustomerAccountLogout;
use Enterprise\GiftRegistry\Test\Fixture\GiftRegistry;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryAddSelect;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryEdit;
use Enterprise\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Customer is registered.
 *
 * Steps:
 * 1. Go to frontend.
 * 2. Login as a customer.
 * 3. Go to My Account -> Gift Registry.
 * 4. Press "Add New" button.
 * 5. Choose Gift Registry type from dataset.
 * 6. Press "Next" button.
 * 7. Fill data from dataset.
 * 8. Perform asserts.
 *
 * @group Gift_Registry_(CS)
 * @ZephyrId MPERF-7547
 */
class CreateGiftRegistryFrontendEntityTest extends Injectable
{
    /**
     * Customer account logout page.
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

    /**
     * Customer account index page.
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * Gift registry index page.
     *
     * @var GiftRegistryIndex
     */
    protected $giftRegistryIndex;

    /**
     * Gift registry select type page.
     *
     * @var GiftRegistryAddSelect
     */
    protected $giftRegistryAddSelect;

    /**
     * Gift registry edit page.
     *
     * @var GiftRegistryEdit
     */
    protected $giftRegistryEdit;

    /**
     * Injection data.
     *
     * @param CustomerAccountLogout $customerAccountLogout
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryAddSelect $giftRegistryAddSelect
     * @param GiftRegistryEdit $giftRegistryEdit
     * @return void
     */
    public function __inject(
        CustomerAccountLogout $customerAccountLogout,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryAddSelect $giftRegistryAddSelect,
        GiftRegistryEdit $giftRegistryEdit
    ) {
        $this->customerAccountLogout = $customerAccountLogout;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->giftRegistryIndex = $giftRegistryIndex;
        $this->giftRegistryAddSelect = $giftRegistryAddSelect;
        $this->giftRegistryEdit = $giftRegistryEdit;
    }

    /**
     * Create customer and product.
     *
     * @param CatalogProductSimple $product
     * @param Customer $customer
     * @return array
     */
    public function __prepare(CatalogProductSimple $product, Customer $customer)
    {
        $product->persist();
        $customer->persist();

        return [
            'customer' => $customer,
            'product' => $product
        ];
    }

    /**
     * Create gift registry entity test.
     *
     * @param GiftRegistry $giftRegistry
     * @param Customer $customer
     * @return void
     */
    public function test(GiftRegistry $giftRegistry, Customer $customer)
    {
        // Steps
        $this->objectManager->create(
            'Mage\Customer\Test\TestStep\LoginCustomerOnFrontendStep',
            ['customer' => $customer]
        )->run();
        $this->customerAccountIndex->getAccountNavigationBlock()->openNavigationItem("Gift Registry");
        $this->giftRegistryIndex->getGiftRegistryList()->addNew();
        $this->giftRegistryAddSelect->getGiftRegistryEditForm()->selectGiftRegistryType($giftRegistry->getTypeId());
        $this->giftRegistryEdit->getGiftRegistryEditForm()->fillGiftRegistry($giftRegistry);
        $this->giftRegistryEdit->getGiftRegistryEditForm()->save();
    }

    /**
     * Log out after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->customerAccountLogout->open();
    }
}
