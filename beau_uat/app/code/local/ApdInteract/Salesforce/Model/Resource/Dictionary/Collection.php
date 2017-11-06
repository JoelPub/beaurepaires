<?php
/**
 * History collection 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Model_Resource_Dictionary_Collection
extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	protected function _construct()
	{
		parent::_construct();

		/**
		 * Tell Magento the model and resource model to use for
		 * this collection. Because both aliases are the same,
		 * we can omit the second paramater if we wish.
		 */
		$this->_init(
			'apdinteract_salesforce/dictionary',
			'apdinteract_salesforce/dictionary'
		);
	}
	
	/**
	 * get dictionary via a magento model instance
	 * 
	 * @param Mage_Core_Model_Abstract $model
	 * @return ApdInteract_Salesforce_Model_Dictionary
	 */
	public function getDictionaryByModel($model) {
		$this->addFieldToFilter("entity_type", get_class($model));
		$this->addFieldToFilter("entity_id", $model->getId());
		return $this->load()->getFirstItem();
	}
}