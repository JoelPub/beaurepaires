<?php
/**

 * Developed by Alinga ECommerce.
 * For more Information, please go to http://www.magentowebdesign.net.au/

 */
 class Alinga_Recentordernotification_IndexController extends Mage_Core_Controller_Front_Action{

     function indexAction(){
	 
	 	if(!Mage::getStoreConfig('recentordernotification/settings/enable')){
			$data=array("update"=>false,"msg"=>"","token"=>"");
			$this->getResponse()->setBody(json_encode($data));
			return;
		}
		
		$order_status=Mage::getStoreConfig('recentordernotification/settings/select_order_status');
		$storeId = Mage::app()->getStore()->getStoreId();
		$collection = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('store_id', $storeId)->addFieldToFilter('request_type', array('nin' => array('BOOKING', 'PRICE REQUEST')))->setOrder('increment_id','DESC')->setPageSize(5)->setCurPage(1);

		$msg_template=Mage::getStoreConfig('recentordernotification/settings/notificaion_msg_template');
		$data = array();
		$order_token='';
		$output='';
		$_product_exist_checker=false;
		foreach($collection as $c){ 
                                $storelocation = $c->getStorelocation();
				$order_token=$c->getIncrementId();
				$_items =  $c->getAllVisibleItems();
                foreach($_items as $_item){
                    $_product = Mage::getModel('catalog/product')->load($_item->getProductId());
					
					if(!$_product->getId()){ 
						continue; 
					}

					$order_shipping_address=$c->getBillingAddress();

					$_product_exist_checker=true;
                    $_img_url= Mage::helper('catalog/image')->init($_product, 'thumbnail')->resize(80);
					$_img = '<img src="'.$_img_url.'" alt="'.$_product->getName().'" title="'.$_product->getName().'" />';
					
					if(($order_shipping_address->getCity()=='' || $order_shipping_address->getCity()=='n/a') && $storelocation>0):
                                            $store_model = Mage::getModel('storelocator/stores');    
                                            $store = $store_model->load($storelocation);
                                            $city = $store->getCity(); 
                                            if($store->getRegionId()>0):
                                                $region_model = Mage::getModel('directory/region')->load($store->getRegionId());
                                                $region = $region_model->getCode();
                                            else:
                                                $region = $store->getRegion();
                                            endif;                                                                                                        
                                        else:
                                            $city = $order_shipping_address->getCity();    
                                        endif;
                                        
					$msg_template= str_replace("[city]",$city,$msg_template);

                    if($order_shipping_address->getRegion() && $order_shipping_address->getRegion()!="" && $order_shipping_address->getRegion()!="n/a"){
                        $region = $order_shipping_address->getRegion();
                    }
                        if(isset($region))
                        $msg_template=str_replace("[region]",$region,$msg_template);
                        else
                        $msg_template=str_replace("[region]",'',$msg_template);    
                        
                    if($order_shipping_address->getCountryId()){
						$country_name=Mage::app()->getLocale()->getCountryTranslation($order_shipping_address->getCountryId());
                        $msg_template=str_replace("[country]",$country_name,$msg_template);
                    }
					
					$msg_template=str_replace("[product_link]",'<a class="notice-product-link " href="'.$_product->getProductUrl().'">'.$_product->getName().'</a>',$msg_template);
					$msg_template=str_replace("[item_price]",'<div class="bottom-line price">'.Mage::helper('core')->currency($_item->getPrice(), true, false).'</div>',$msg_template);
					$msg_template=str_replace("[ordered_time]",'<div class="time-ago bottom-line">'.Mage::helper('recentordernotification')->getTimeAgo($c->getCreatedAt()).'</div>',$msg_template);
                    $output = '<div class="recentordernotification"><div class="notice-img"><a class="notice-product-link" href="'.$_product->getProductUrl().'">'.$_img.'</a></div><div class="notice-text">'.$msg_template.'</div></div>';
					break;
                }
				if($_product_exist_checker){				
					break;
				}
		}
		
		if(!$_product_exist_checker){
			$data=array("update"=>false,"msg"=>"","token"=>"");
		}else if(isset($_REQUEST['order_token']) && $_REQUEST['order_token']==md5($order_token))
		{
			$data=array("update"=>false,"msg"=>$output,"token"=>md5($order_token));
		}else{
			if(count($collection)!=0){
				$data=array("update"=>true,"msg"=>$output,"token"=>md5($order_token));
			}else{
				$data=array("update"=>false,"msg"=>$output,"token"=>md5($order_token));
			}
		}
		$this->getResponse()->setBody(json_encode($data));
     }
}