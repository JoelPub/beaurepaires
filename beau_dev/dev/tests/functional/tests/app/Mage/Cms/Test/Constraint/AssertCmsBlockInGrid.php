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

namespace Mage\Cms\Test\Constraint;

use Mage\Cms\Test\Fixture\CmsBlock;
use Mage\Cms\Test\Page\Adminhtml\CmsBlockIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created CMS block can be found in grid.
 */
class AssertCmsBlockInGrid extends AbstractConstraint
{
    /**
     * Assert that created CMS block can be found in grid via: title, identifier, status.
     *
     * @param CmsBlock $cmsBlock
     * @param CmsBlockIndex $cmsBlockIndex
     * @return void
     */
    public function processAssert(CmsBlock $cmsBlock, CmsBlockIndex $cmsBlockIndex)
    {
        $cmsBlockIndex->open();
        $data = $cmsBlock->getData();
        $filter = [
            'title' => $data['title'],
            'identifier' => $data['identifier'],
            'is_active' => $data['is_active'],
        ];

        $cmsBlockIndex->getCmsBlockGrid()->search($filter);

        \PHPUnit_Framework_Assert::assertTrue(
            $cmsBlockIndex->getCmsBlockGrid()->isRowVisible($filter, false, false)
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'CMS Block is present in grid.';
    }
}
