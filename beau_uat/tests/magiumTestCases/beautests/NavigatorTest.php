<?php

namespace BeauTests;

use BeauLib\AbstractTestCase;
use Magium\Magento\Navigators\BaseMenu;
use Magium\Magento\Navigators\Catalog\DefaultSimpleProductCategory;
use Magium\Magento\Navigators\Catalog\DefaultConfigurableProductCategory;


class NavigatorTest extends AbstractTestCase
{

    public function testNavigator()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(BaseMenu::NAVIGATOR)->navigateTo("Tyres/Passenger Car Tyres");
    }

    /*public function testStaticSimpleProductNavigator()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(DefaultSimpleProductCategory::NAVIGATOR)->navigateTo();

    }*/

   /* public function testConfigurableProductNavigator()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(DefaultConfigurableProductCategory::NAVIGATOR)->navigateTo();

    }*/
}