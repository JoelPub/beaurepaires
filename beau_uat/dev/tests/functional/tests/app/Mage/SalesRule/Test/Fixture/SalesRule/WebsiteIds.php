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

namespace Mage\SalesRule\Test\Fixture\SalesRule;

use Mage\Adminhtml\Test\Fixture\Website;
use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Prepare WebsiteIds for SalesRule.
 */
class WebsiteIds extends DataSource
{

    /**
     * Websites fixtures.
     *
     * @var Website[]
     */
    protected $websites;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, $data = [])
    {
        $this->params = $params;
        foreach ($data as $dataset) {
            $website = $fixtureFactory->createByCode('website', ['dataset' => trim($dataset)]);
            /** @var Website $website */
            if (!$website->hasData('website_id')) {
                $website->persist();
            }
            $this->websites[] = $website;
            $this->data[] = $website->getName();
        }
    }

    /**
     * Return Websites array.
     *
     * @return Website[]
     */
    public function getWebsites()
    {
        return $this->websites;
    }
}
