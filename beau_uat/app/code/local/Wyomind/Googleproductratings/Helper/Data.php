<?php

class Wyomind_GoogleProductRatings_Helper_Data extends Mage_Core_Helper_Data
{

    public function checkHeartbeat() 
    {

        $lastHeartbeat = $this->getLastHeartbeat();
        if ($lastHeartbeat === false) {
            // no cron task found
            Mage::getSingleton('core/session')->addError($this->__('No cron task found. <a href="https://www.wyomind.com/faq.html#How_do_I_fix_the_issues_with_scheduled_tasks" target="_blank">Check if cron is configured correctly.</a>'));
        } else {
            $timespan = $this->dateDiff($lastHeartbeat);
            if ($timespan <= 5 * 60) {
                Mage::getSingleton('core/session')->addSuccess($this->__('Scheduler is working. (Last cron task: %s minute(s) ago)', round($timespan / 60)));
            } elseif ($timespan > 5 * 60 && $timespan <= 60 * 60) {
                // cron task wasn't executed in the last 5 minutes. Heartbeat schedule could have been modified to not run every five minutes!
                Mage::getSingleton('core/session')->addNotice($this->__('Last cron task is older than %s minutes.', round($timespan / 60)));
            } else {
                // everything ok
                Mage::getSingleton('core/session')->addError($this->__('Last cron task is older than one hour. Please check your settings and your configuration!'));
            }
        }
    }

    public function getLastHeartbeat() 
    {

        $schedules = Mage::getModel('cron/schedule')->getCollection();
        /* @var $schedules Mage_Cron_Model_Mysql4_Schedule_Collection */
        $schedules->getSelect()->limit(1)->order('executed_at DESC');
        $schedules->addFieldToFilter('status', Mage_Cron_Model_Schedule::STATUS_SUCCESS);

        $schedules->load();
        if (count($schedules) == 0) {
            return false;
        }
        $executedAt = $schedules->getFirstItem()->getExecutedAt();
        $value = Mage::getModel('core/date')->date(NULL, $executedAt);
        return $value;
    }

    public function dateDiff($time1, $time2 = NULL) 
    {
        if (is_null($time2)) {
            $time2 = Mage::getModel('core/date')->date();
        }
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);

        return $time2 - $time1;
    }

    public function getDuration($time) 
    {
        if ($time < 60)
            $time = ceil($time) . ' sec. ';
        else
            $time = floor($time / 60) . ' min. ' . ($time % 60) . ' sec.';
        return $time;
    }

    

}
