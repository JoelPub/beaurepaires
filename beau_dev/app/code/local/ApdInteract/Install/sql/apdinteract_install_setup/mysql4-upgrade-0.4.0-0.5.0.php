<?php
// update existing attribute *overall_weight* attribute code to overall_width

$attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','overall_weight');
if ($attributeId) {
	$attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
	$attribute->setAttributeCode('overall_width')->save();
}