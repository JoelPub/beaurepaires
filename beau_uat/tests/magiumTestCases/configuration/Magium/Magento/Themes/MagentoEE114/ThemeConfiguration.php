<?php

/**
 *  Basse Theme Configuration Path :
 *  tests/magiumTestCases/vendor/magium/magento/lib/Magento/Themes/MagentoEE114/ThemeConfiguration.php
 */
    /**
     * @var string The base URL of the installation
     */
    //$this->baseUrl = 'http://magento-demo.lexiconn.com/';
     $this->baseUrl = 'https://www.beaurepaires.com.au/';

    /**
     * @var string The Xpath string that finds the base of the navigation menu
     */
    $this->navigationBaseXPathSelector  = '//nav[@class="top-bar"]/section/ul';

    /**
     * @var string A simple, default path to use for categories.
     */
    $this->navigationPathToSimpleProductCategory      = '{{Batteries}}/{{Battery Finder}}';
    $this->navigationPathToConfigurableProductCategory      = '{{Tyres}}/{{Passenger Car Tyres}}';

