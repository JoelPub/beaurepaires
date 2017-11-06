<?php


class ApdInteract_Sitemap_Model_Observer extends Mage_Sitemap_Model_Observer {
    // This file is needed for the sitemap cron generation to work
    // Without it, you will get this error in the cron exception logs:
    // "Invalid callback: sitemap/observer::scheduledGenerateSitemaps does not exist"
}

