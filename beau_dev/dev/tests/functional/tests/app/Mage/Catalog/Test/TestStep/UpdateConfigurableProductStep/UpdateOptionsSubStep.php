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

namespace Mage\Catalog\Test\TestStep\UpdateConfigurableProductStep;

/**
 * Update options sub step.
 */
class UpdateOptionsSubStep extends AbstractSubStep
{
    /**
     * Update configurable options.
     *
     * @return void
     */
    public function run()
    {
        $this->prepareOptionsForEdit();
        $this->fillAttributes($this->currentConfigurableOptionsData);
    }

    /**
     * Return arguments from sub step.
     *
     * @return array
     */
    public function returnArguments()
    {
        return [
            'currentConfigurableOptionsData' => $this->currentConfigurableOptionsData
        ];
    }

    /**
     * Prepare options for edit.
     *
     * @return void
     */
    protected function prepareOptionsForEdit()
    {
        foreach ($this->configurableOptionsEditData['updateOptions'] as $editOption) {
            $attributeKey = 'attribute_key_' . $editOption['attributeIndex'];
            $optionKey = 'option_key_' . $editOption['optionIndex'];
            $this->currentConfigurableOptionsData[$attributeKey]['options'][$optionKey] = array_replace(
                $this->currentConfigurableOptionsData[$attributeKey]['options'][$optionKey],
                $editOption['value']
            );
        }
    }
}
