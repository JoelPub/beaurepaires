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

namespace Enterprise\UrlRewrite\Test\Fixture\UrlRewrite;

use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Prepare target path.
 */
class TargetPath extends DataSource
{

    /**
     * Entity fixture.
     *
     * @var FixtureInterface|null
     */
    protected $entity = null;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params
     * @param array $data
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params, array $data)
    {
        $this->params = $params;
        if (isset($data['entity'])) {
            list($type, $entity) = explode('/', $data['entity']);
            list($fixture, $dataset) = explode('::', $entity);
            $this->entity = $fixtureFactory->createByCode($fixture, ['dataset' => $dataset]);
            $this->entity->persist();
            $this->data = sprintf("catalog/%s/view/id/%s", $type, $this->entity->getId());
        }
        if (isset($data['custom'])) {
            $this->data = $data['custom'];
        }
        if (isset($data['readyEntity'])) {
            $this->entity = $data['readyEntity'];
        }
    }

    /**
     * Return entity.
     *
     * @return FixtureInterface|null
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
