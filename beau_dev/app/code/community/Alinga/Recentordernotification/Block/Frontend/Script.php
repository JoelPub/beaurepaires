<?php
/**
 * Developed by Alinga ECommerce.
 * For more Information, please go to http://www.magentowebdesign.net.au/
 */

 class Alinga_Recentordernotification_Block_Frontend_Script extends Mage_Core_Block_Template{
	 
     function _toHtml(){
		
		if(!Mage::getStoreConfig('recentordernotification/settings/enable')){
			return;
		}
		
		$display_position=Mage::getStoreConfig('recentordernotification/settings/notification_position');
		$reappear_after_close=Mage::getStoreConfig('recentordernotification/settings/reappear_after_close');
		$java_script='<script>';
        $java_script.='function showLastOrder(){';
        $java_script.= ' var order_data=null;
			jQuery.get( "'.$this->getUrl('recentordernotify/index/index').'", { order_token:readPopupCookie("order_security_token")} )
			  .done(function( data ) {
				order_data= jQuery.parseJSON(data);
				if(order_data.token!=""){
					createPopupCookie("order_security_token",order_data.token,86400);
				}
				if(order_data.update){
					if(!readPopupCookie("hideOrderNotifcation")){
						jQuery.growl({ title: "Growl",duration:'.Mage::helper('recentordernotification')->getTimeDelay().',location:"'.$display_position.'" ,message: order_data.msg })
					};
				}
			});
			setTimeout("showLastOrder()",'.Mage::helper('recentordernotification')->getTimeDelay().' );
		}';
        $java_script.='jQuery(document).ready(function () {				
				setTimeout("showLastOrder()",'.Mage::getStoreConfig('recentordernotification/settings/on_first_page_load').');
	    });';
        $java_script.='window.order_nofication_cookie_timing="'.$reappear_after_close.'"; ';
		$java_script.='
			function createPopupCookie(name,value,sec) {
				if (sec) {
					var date = new Date();
					date.setTime(date.getTime()+(sec*60));
					var expires = "; expires="+date.toGMTString();
				}
				else var expires = "";
				document.cookie = name+"="+value+expires+"; path=/;";
			}

			function readPopupCookie(name) {
				var nameEQ = name + "=";
				var ca = document.cookie.split(";");
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==" ") c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
				}
				return null;
			}
			function eraseCookie(name) {
				createPopupCookie(name,"",-1);
			}';	
		$java_script.='</script>';

		$java_script.='<style>';
		$java_script.='.growl.growl-default{background:'.Mage::getStoreConfig('recentordernotification/set_colors/background_colour').';}';
		$java_script.='#growls .growl{ border-bottom: 2px solid '.Mage::getStoreConfig('recentordernotification/set_colors/border_colour').';}';		
		$java_script.='.notice-text,.growl-medium .time-ago,.growl-close{color:'.Mage::getStoreConfig('recentordernotification/set_colors/text_colour').';}';
		$java_script.='.growl-medium a,.bottom-line.price{color:'.Mage::getStoreConfig('recentordernotification/set_colors/link_colour').';}';
		$java_script.='.growl-medium a:hover{color:'.Mage::getStoreConfig('recentordernotification/set_colors/link_hover_colour').';}';

		if(!Mage::getStoreConfig('recentordernotification/settings/enable_mobile')){
			$java_script.='@media screen and (max-width: 768px) {#growls { display: none!important; }}';
		}
		$java_script.='</style>';
		return $java_script;
     }

 }