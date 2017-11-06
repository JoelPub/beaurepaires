<?php

class Wyomind_Googleproductratings_Model_Observer
{

    public function generateFeeds($schedule) 
    {


        $log = array();
        $log[] = "-------------------- CRON PROCESS --------------------";

        $error=false;
        $cnt = 0;
        $configs = array();
        Mage::app()->setCurrentStore(0);
        $storeId = Mage::app()->getWebsite()->getDefaultGroup()->getDefaultStoreId();
        $updated_at = Mage::getStoreConfig('googleproductratings/storage/updated_at');
        $file_path = Mage::getStoreConfig("googleproductratings/storage/file_path");
        $file_name = Mage::getStoreConfig("googleproductratings/storage/file_name");
        $cron = Mage::getStoreConfig("googleproductratings/schedule/cron");
        $io = new Varien_Io_File();

        if (!$io->fileExists(Mage::getBaseDir() . "/" . $file_path . "/default/" . $file_name . ".xml", false)) {
            $updated_at = 1;
        }
        $configs[] = array("website" => false, "store" => false, "profile" => "default", "store_id" => $storeId, "updated_at" => $updated_at, "cron" => $cron);

        foreach (Mage::app()->getWebsites() as $website) {
            $updated_at = $website->getConfig("googleproductratings/storage/updated_at");
            $file_name = $website->getConfig("googleproductratings/storage/file_name");
            $file_path = $website->getConfig("googleproductratings/storage/file_path");
            $cron = $website->getConfig("googleproductratings/schedule/cron");

            if (!$io->fileExists(Mage::getBaseDir() . "/" . $file_path . "/default/" . $website->getCode() . "/" . $file_name . ".xml", false)) {
                $updated_at = 1;
            }
            $configs[] = array("website" => $website->getCode(), "store" => false, "profile" => "default/" . $website->getCode(), "store_id" => $website->getDefaultGroup()->getDefaultStoreId(), "updated_at" => $updated_at, "cron" => $cron);

            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    $updated_at = $store->getConfig("googleproductratings/storage/updated_at");
                    $file_name = $store->getConfig("googleproductratings/storage/file_name");
                    $file_path = $store->getConfig("googleproductratings/storage/file_path");
                    $cron = $store->getConfig("googleproductratings/schedule/cron");

                    if (!$io->fileExists(Mage::getBaseDir() . "/" . $file_path . "/default/" . $website->getCode() . "/" . $store->getCode() . "/" . $file_name . ".xml", false)) {
                        $updated_at = 1;
                    }
                    $configs[] = array("website" => $website->getCode(), "profile" => "default/" . $website->getCode() . "/" . $store->getCode(), "store" => $store->getCode(), "store_id" => $store->getId(), "updated_at" => $updated_at, "cron" => $cron);
                }
            }
        }



        try {
            foreach ($configs as $config) {
                $cron = array();
                $log[] = "--> Running profile : '" . $config['profile'] . " <--'";


                $cron['curent']['localDate'] = Mage::getSingleton('core/date')->date('l Y-m-d H:i:s');
                $cron['curent']['gmtDate'] = Mage::getSingleton('core/date')->gmtDate('l Y-m-d H:i:s');
                $cron['curent']['localTime'] = Mage::getSingleton('core/date')->timestamp();
                $cron['curent']['gmtTime'] = Mage::getSingleton('core/date')->gmtTimestamp();

                Mage::getStoreConfig("googleproductratings/options/product_mpn");

                $cron['file']['localDate'] = Mage::getSingleton('core/date')->date('l Y-m-d H:i:s', $config['updated_at']);

                $cron['file']['localTime'] = Mage::getSingleton('core/date')->timestamp($config['updated_at']);
                $cron['file']['gmtDate'] = Mage::getSingleton('core/date')->gmtDate('l Y-m-d H:i:s', $cron['file']['localTime']);
                $cron['file']['gmtTime'] = $config['updated_at'];

                /* Magento getGmtOffset() is bugged and doesn't include daylight saving time, the following workaround is used */
                // date_default_timezone_set(Mage::app()->getStore()->getConfig('general/locale/timezone'));
                // $date = new DateTime();
                //$cron['offset'] = $date->getOffset() / 3600;
                $cron['offset'] = Mage::getSingleton('core/date')->getGmtOffset("hours");



                $log[] = '   * Last update : ' . $cron['file']['gmtDate'] . " GMT / " . $cron['file']['localDate'] . ' GMT' . $cron['offset'];
                $log[] = '   * Current date : ' . $cron['curent']['gmtDate'] . " GMT / " . $cron['curent']['localDate'] . ' GMT' . $cron['offset'];


                $cronExpr = json_decode($config['cron']);
                $i = 0;
                $done = false;

                foreach ($cronExpr->days as $d) {

                    foreach ($cronExpr->hours as $h) {
                        $time = explode(':', $h);
                        if (date('l', $cron['curent']['gmtTime']) == $d) {
                            $cron['tasks'][$i]['localTime'] = strtotime(Mage::getSingleton('core/date')->date('Y-m-d')) + ($time[0] * 60 * 60) + ($time[1] * 60);
                            $cron['tasks'][$i]['localDate'] = date('l Y-m-d H:i:s', $cron['tasks'][$i]['localTime']);
                        } else {
                            $cron['tasks'][$i]['localTime'] = strtotime("last " . $d, $cron['curent']['localTime']) + ($time[0] * 60 * 60) + ($time[1] * 60);
                            $cron['tasks'][$i]['localDate'] = date('l Y-m-d H:i:s', $cron['tasks'][$i]['localTime']);
                        }
                        $log[] = '   * Scheduled : ' . ($cron['tasks'][$i]['localDate'] . " GMT" . $cron['offset']);
                        if ($cron['tasks'][$i]['localTime'] >= $cron['file']['localTime'] && $cron['tasks'][$i]['localTime'] <= $cron['curent']['localTime'] && $done != true) {



                            if (Mage::getModel("googleproductratings/feeds")->generate($config['website'], $config['store'])) {

                                $done = true;
                                $cnt++;
                                $log[] = '   * EXECUTED!';
                            }
                        }

                        $i++;
                    }
                }
                if (!$done) {
                    $log[] = '   * SKIPPED!';
                }
            }
        } catch (Exception $e) {
            $error=true;
            $log[] = '   * ERROR! ' . ($e->getMessage());
        }



        if (Mage::getStoreConfig("googleproductratings/schedule/schedule_report")) {
            
            foreach (explode(',', Mage::getStoreConfig("googleproductratings/schedule/report_recipient")) as $email) {
               
                try {
                    if ($cnt || $error)
                        mail($email, Mage::getStoreConfig("googleproductratings/schedule/report_title"), "\n" . implode($log, "\n"));
                } catch (Exception $e) {
                    $log[] = '   * EMAIL ERROR! ' . ($e->getMessage());
                }
            }
        };
        if (isset($_GET['gpr']))
            echo "<br/>" . implode($log, "<br/>");
        Mage::log("\n" . implode($log, "\n"), null, "GoogleProductRatings-cron.log");

       
    }

}
