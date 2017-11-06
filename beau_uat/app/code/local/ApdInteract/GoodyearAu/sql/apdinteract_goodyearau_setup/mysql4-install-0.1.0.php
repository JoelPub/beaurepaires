<?php
/**
 * Setup new Website : Goodyear AU
 * Website Code : goodyear_au
 *
 */
$installer = $this;
$installer->startSetup();

// Base-name
$names = array(
	'goodyear_au' => array(
		'category_name' => 'Goodyear AU Category',
		'website_name' => 'Goodyear AU',
		'store_name' => 'Goodyear AU Store',
		'store_view_name' => 'Goodyear AU Store View',
	)
);


Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

foreach ($names as $code => $storeData) {

	$categoryName = $storeData['category_name'];
	$websiteName = $storeData['website_name'];
	$storeName = $storeData['store_name'];
	$storeViewName = $storeData['store_view_name'];

	// Load the website and make sure it doesn't exist
	$website = Mage::getModel('core/website')->load($code, 'code');
	if (!$website->getId()) {
		// Create the website
		$website->setData('name', $websiteName);
		$website->setData('code', strtolower($code));
		$website->setData('sort_order', 1);
		$website->setData('is_default', 0);
		$website->save();
		$websiteId = $website->getWebsiteId();

		// Create the group
		$group = Mage::getModel('core/store_group')->load($websiteId, 'website_id');
		$group->setData('website_id', $websiteId);
		$group->setData('name', $storeName);
		$group->setData('root_category_id', 2);
		$group->save();
		$groupId = $group->getGroupId();

		// Create the store
		$store = Mage::getModel('core/store');
		$store->setData('website_id', $websiteId);
		$store->setData('group_id', $groupId);
		$store->setData('name', $storeViewName);
		$store->setData('code', strtolower($code));
		$store->setData('increment_prefix', strtoupper($code));
		$store->setData('is_active', 0);
		$store->save();
		$storeId = $store->getStoreId();

		$orderEntityType = Mage::getModel('eav/entity_type')
			->getCollection()
			->addFilter('entity_type_code', 'order')
			->getFirstItem();

		if ($orderEntityType->getEntityTypeId() > 0) {
			$entityStoreConfig = Mage::getModel('eav/entity_store');
			$entityStoreConfig->setEntityTypeId($orderEntityType->getEntityTypeId())
				->setStoreId($storeId)
				->setIncrementPrefix(strtoupper($code))
				->save();
		}

		// Update the website
		$website->setData('default_group_id', $groupId);
		$website->save();

		// Update the group
		$group->setData('default_store_id', $storeId);
		$group->save();
	}

}


//Config country, timezone, locate, currency for US store
$website = Mage::getModel('core/website')->load('goodyear_au', 'code');
if ($website->getId()) {
	Mage::getConfig()->saveConfig('general/country/default', 'AU', 'websites', $website->getId());
	Mage::getConfig()->saveConfig('general/country/allow', 'AU', 'websites', $website->getId());
	Mage::getConfig()->saveConfig('general/locale/timezone', 'Australia/Melbourne', 'websites', $website->getId());
	Mage::getConfig()->saveConfig('general/locale/code', 'en_AU', 'websites', $website->getId());
	Mage::getConfig()->saveConfig('currency/options/base', 'AUD', 'websites', $website->getId());
	Mage::getConfig()->saveConfig('currency/options/default', 'AUD', 'websites', $website->getId());
	Mage::getConfig()->saveConfig('currency/options/allow', 'AUD', 'websites', $website->getId());

}

$installer->endSetup();
	 