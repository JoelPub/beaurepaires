<?php
class ApdInteract_Tooltip_Helper_Data extends Mage_Core_Helper_Abstract
{
	     
	const WHEEL_ALIGNMENT = 'Wheel Alignment';
	const SUSPENSION = 'Road Hazard Warranty (per tyre)';
	const SIZE = 'Size';
        const FITTING = 'Battery Fitting';
	
	/**
	 * Get tooltip content
	 *
	 * @return ApdInteract_Tooltip_Helper_Data
	 */
	public function getTooltip($title)
	{
		if ($title === self::WHEEL_ALIGNMENT)
			return Mage::getStoreConfig('tooltip/tooltipsetting/wheel_alignment');	
		elseif ($title === self::SUSPENSION)
			return Mage::getStoreConfig('tooltip/tooltipsetting/suspension');	
		elseif ($title === self::SIZE)
			return Mage::getStoreConfig('tooltip/tooltipsetting/size');		
                elseif ($title === self::FITTING)
			return Mage::getStoreConfig('tooltip/tooltipsetting/fitting');		
		else
			return false;
		
	}
	
	public function getOutofStockTooltip()
	{
		return Mage::getStoreConfig('tooltip/stock_levels/out_of_stock');
	}
	
	public function getXdaysToArriveTooltip()
	{
		return Mage::getStoreConfig('tooltip/stock_levels/x_days_to_arrive');
	}
        
        public function getNotAvailableTooltip()
	{
		return Mage::getStoreConfig('tooltip/stock_levels/not_available');
	}
}
