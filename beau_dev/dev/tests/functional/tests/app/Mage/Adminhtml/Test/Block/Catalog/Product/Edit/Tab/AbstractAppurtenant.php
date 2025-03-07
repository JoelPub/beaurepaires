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

namespace Mage\Adminhtml\Test\Block\Catalog\Product\Edit\Tab;

use Mage\Adminhtml\Test\Block\Widget\Grid;
use Mage\Adminhtml\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Element\SimpleElement as Element;

/**
 * Base class for appurtenant products tab.
 */
abstract class AbstractAppurtenant extends Tab
{
    /**
     * Type appurtenant products.
     *
     * @var string
     */
    protected $type = '';

    /**
     * Select related products.
     *
     * @param array $data
     * @param Element|null $element
     * @return $this
     */
    public function fillFormTab(array $data, Element $element = null)
    {
        if (isset($data[$this->type]['value'])) {
            $context = $element ? $element : $this->_rootElement;
            $relatedBlock = $this->getGrid($context);

            foreach ($data[$this->type]['value'] as $product) {
                $relatedBlock->searchAndSelect(['sku' => $product['sku']]);
            }
        }

        return $this;
    }

    /**
     * Get data of tab.
     *
     * @param array|null $fields
     * @param Element|null $element
     * @return array
     */
    public function getDataFormTab($fields = null, Element $element = null)
    {
        $relatedBlock = $this->getGrid($element);
        $columns = [
            'entity_id' => '.="ID"',
            'name' => '.="Name"',
            'sku' => '.="SKU"',
        ];
        $relatedProducts = $relatedBlock->getRowsData($columns);

        return [$this->type => $relatedProducts];
    }

    /**
     * Return related products grid.
     *
     * @param Element $element
     * @return Grid
     */
    abstract protected function getGrid(Element $element = null);
}
