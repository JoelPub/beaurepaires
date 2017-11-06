<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	INSERT INTO `eav_entity_type` (`entity_type_id`, `entity_type_code`, `entity_model`, `attribute_model`, `entity_table`, `value_table_prefix`, `entity_id_field`, `is_data_sharing`, `data_sharing_key`, `default_attribute_set_id`, `increment_model`, `increment_per_store`, `increment_pad_length`, `increment_pad_char`, `additional_attribute_table`, `entity_attribute_collection`) VALUES (NULL, 'virconsumer', '', NULL, NULL, NULL, NULL, '1', 'default', '0', '', '0', '8', '0', '', NULL);
	INSERT INTO `eav_entity_type` (`entity_type_id`, `entity_type_code`, `entity_model`, `attribute_model`, `entity_table`, `value_table_prefix`, `entity_id_field`, `is_data_sharing`, `data_sharing_key`, `default_attribute_set_id`, `increment_model`, `increment_per_store`, `increment_pad_length`, `increment_pad_char`, `additional_attribute_table`, `entity_attribute_collection`) VALUES (NULL, 'vircommercial', '', NULL, NULL, NULL, NULL, '1', 'default', '0', '', '0', '8', '0', '', NULL);
	");
$installer->endSetup();