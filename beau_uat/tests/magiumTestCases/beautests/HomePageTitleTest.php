<?php

namespace BeauTests;

use BeauLib\AbstractTestCase;

class HomePageTitleTest extends AbstractTestCase
{

    public function testTitle()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->assertTitleEquals("Beaurepaires Australia");
    }

    public function testBadTitleNotExists()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->assertNotTitleEquals("Test..");

    }

}