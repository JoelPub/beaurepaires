<?php
/**
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Model_Resource_Dictionary
extends Mage_Core_Model_Resource_Db_Abstract
{
	protected function _construct()
	{
		/**
		 * Tell Magento the database name and primary key field to persist
		 * data to. Similar to the _construct() of our model, Magento finds
		 * this data from config.xml by finding the <resourceModel/> node
		 * and locating children of <entities/>.
		 *
		 * In this example:
		 * - apdinteract_vir is the model alias
		 * - order is the entity referenced in config.xml
		 * - parent_id is the name of the primary key column
		 *
		 * As a result, Magento will write data to the table
		 * 'apdinteract_vir_order' and any calls
		 * to $model->getId() will retrieve the data from the
		 * column named 'parent_id'.
		 */
		$this->_init('apdinteract_salesforce/dictionary', 'id');
	}
}

