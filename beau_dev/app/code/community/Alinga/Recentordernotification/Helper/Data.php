<?php
/**

 * Developed by Alinga ECommerce.
 * For more Information, please go to http://www.magentowebdesign.net.au/

 */
class Alinga_Recentordernotification_Helper_Data extends Mage_Core_Helper_Data{

    function getTimeAgo($dattime){
        //$current_time = Mage::getModel('core/date')->timestamp(time());
        $current_time = time();
        $time =strtotime($dattime);
        $different_time = round(($current_time-$time)/60);
        $output_min='';
        $output_hour='';
        $output_day='';
        $output_sec='';
        $hour = round($different_time/60);
        $hour_mod = ($different_time % 60);
        $min = ($different_time % 60);
        $min_mod = $different_time % 60;
        $day = round($different_time/(60*24));
        $day_mod = $different_time % (60*24);
        $second = $current_time-$time;
        if($day > 1){
            $output_day= $day.' '.Mage::helper('recentordernotification')->__('days').' ';
        }
        if($day == 1){
            $output_day=Mage::helper('recentordernotification')->__('a day').' ';
        }
        if($day < 1){
            if($hour > 1){
                $output_hour= $hour.' '.Mage::helper('recentordernotification')->__('hours').' ';
            }
            if($hour == 1){
                $output_hour= Mage::helper('recentordernotification')->__('an hour').' ';
                if($hour_mod > 1){
                    $output_min=$hour_mod.' '.Mage::helper('recentordernotification')->__('minutes').' ';
                }
            }
            if($hour == 0){
                if($min > 10){
                    $output_min = $min.' '.Mage::helper('recentordernotification')->__('minutes').' ';
                }
                if($min > 1 && $min < 10){
                    $output_min = Mage::helper('recentordernotification')->__('a few minutes').' ';
                }
                if($min==1){
                    $output_min = Mage::helper('recentordernotification')->__('a minute').' ';
                }
                if($min==0){
                    $output_sec = ' '.Mage::helper('recentordernotification')->__('a few seconds').' ';
                }

            }
        }

        $output=$output_day.$output_hour.$output_min.$output_sec.Mage::helper('recentordernotification')->__('ago');

        return $output;
    }

    function getFirstTime(){
        if(Mage::getStoreConfig('recentordernotification/settings/on_first_page_load')){
            return Mage::getStoreConfig('recentordernotification/settings/on_first_page_load');
        }else{
            return 5000;
        }
    }

    function getTimeDelay(){
        if(Mage::getStoreConfig('recentordernotification/settings/time_delay')){
            return Mage::getStoreConfig('recentordernotification/settings/time_delay');
        }else{
            return 15000;
        }
    }
}