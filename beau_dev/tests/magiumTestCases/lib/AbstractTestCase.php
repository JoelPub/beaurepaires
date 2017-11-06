<?php

namespace BeauLib;

use Magium\Magento\AbstractMagentoTestCase;

abstract class AbstractTestCase extends AbstractMagentoTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->switchThemeConfiguration('Magium\Magento\Themes\MagentoEE114\ThemeConfiguration');
    }
}