<?php

//Update URL Rewrite
$installer = $this;
$installer->startSetup();
$setup = $installer->getConnection();
$store_id = Mage::app()->getStore()->getStoreId();

$rewrite = Mage::getModel('enterprise_urlrewrite/redirect');
$redirect_rewrite = Mage::getModel('enterprise_urlrewrite/url_rewrite');

$url_arr_val = array(
				array(
					  'request_path' => "tyres/south-australia",
					  'target_path'  => "store-locator/index/sa"
				)
	
		);

foreach ($url_arr_val as $url_item) {

	$rewrite_rpath = $rewrite->loadByRequestPath($url_item['request_path'], $store_id);

	if($rewrite_rpath->getRedirectId() == ""){ 
		$rewrite->setStoreId($store_id)
			->setOptions('')
			->setIdentifier($url_item['request_path'])
			->setTargetPath($url_item['target_path'])
			->setEntityType(Mage_Core_Model_Url_Rewrite::TYPE_CUSTOM)
			->save();
	
		$last_redirect_id = $rewrite->getRedirectId();
		
		$redirect_rewrite_rpath = $redirect_rewrite->loadByRequestPath($url_item['request_path'], $store_id);
		if($redirect_rewrite_rpath->getUrlRewriteId() == ""){
			
				$redirect_rewrite->setStoreId($store_id) 
				->setRequestPath($url_item['request_path'])
				->setIdentifier($url_item['request_path']) 
				->setTargetPath($url_item['target_path'])
				->setEntityType(Mage_Core_Model_Url_Rewrite::TYPE_CUSTOM)
				->setValueId($last_redirect_id)
				->save(); 
		 
				$last_redirect_rewrite = $redirect_rewrite->getUrlRewriteId();	
			
				$setup->insert($installer->getTable('enterprise_urlrewrite/redirect_rewrite'), array(
				'redirect_id' 	=> $last_redirect_id,
				'url_rewrite_id'    => $last_redirect_rewrite,			
			)); 
			
		}
		
	}
	
} 

$installer->endSetup();

