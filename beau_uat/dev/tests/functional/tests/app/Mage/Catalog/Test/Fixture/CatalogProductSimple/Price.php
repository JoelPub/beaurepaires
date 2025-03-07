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

namespace Mage\Catalog\Test\Fixture\CatalogProductSimple;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Repository\RepositoryFactory;

/**
 * Preset for price.
 *
 * Data keys:
 *  - preset (Price verification preset name)
 *  - value (Price value)
 *
 */
class Price extends DataSource
{
    /**
     * Current preset.
     *
     * @var string
     */
    protected $priceData;

    /**
     * @constructor
     * @param RepositoryFactory $repositoryFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(RepositoryFactory $repositoryFactory, array $params, $data = [])
    {
        $this->params = $params;
        $this->data = (!isset($data['dataset']) && !isset($data['value'])) ? $data : null;

        if (isset($data['value'])) {
            $this->data = $data['value'];
        }

        if (isset($data['dataset']) && isset($this->params['repository'])) {
            $this->priceData = $repositoryFactory->get($this->params['repository'])->get($data['dataset']);
        }
    }

    /**
     * Get price data for different pages.
     *
     * @return array|null
     */
    public function getPriceData()
    {
        return $this->priceData;
    }
}
