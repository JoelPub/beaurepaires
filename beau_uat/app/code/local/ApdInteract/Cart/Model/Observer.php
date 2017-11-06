<?php
class ApdInteract_Cart_Model_Observer
{

	const ROAD_HAZARD_PRODUCT = 'AS_6666997';

	const ROAD_HAZARD_PRODUCT_SUV = 'AS_6666971';

	/**
	 * @param Varien_Event_Observer $observer
	 */
	public function addCustomProduct(Varien_Event_Observer $observer)
	{

		$sessionData = array();

		$event = $observer->getEvent();
		$quote_item = $event->getQuoteItem();

		$requestParam = Mage::app()->getRequest()->getParams();
		if(isset($requestParam['extra-product'])) {
			if ($this->isCustomProductExist($requestParam['extra-product'], self::ROAD_HAZARD_PRODUCT)) {
				$quote_item->setRoadHazardWarranty(1);
			}

			if ($this->isCustomProductExist($requestParam['extra-product'], self::ROAD_HAZARD_PRODUCT_SUV)) {
				$quote_item->setRoadHazardWarrantySuv(1);
			}
		}
	}

	/**
	 * Remove Road hazard
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function deleteCustomProduct(Varien_Event_Observer $observer)
	{
		$QuoteItem = $observer->getEvent()->getQuoteItem();
		$quoteId = $QuoteItem->getQuoteId();
		$qty = $QuoteItem->getQty();

		if(!empty($QuoteItem->getRoadHazardWarranty()) && $QuoteItem->getProductType() == 'configurable'){
			$this->_removeFromCart($qty,self::ROAD_HAZARD_PRODUCT);
		}else if(!empty($QuoteItem->getRoadHazardWarrantySuv()) && $QuoteItem->getProductType() == 'configurable'){
			$this->_removeFromCart($qty,self::ROAD_HAZARD_PRODUCT_SUV);
		}
	}

	/**
	 * @param $item
	 * @param $field
	 * @return mixed
	 */
	protected function _countQty($item,$field){
		$collection = Mage::getModel('sales/quote_item')->getCollection()
			->addFieldToSelect('qty')
			->addFieldToFilter('quote_id', array('eq' => $item->getQuoteId()))
			->addFieldToFilter('product_type', array('eq' => 'configurable'))
			->addFieldToFilter($field,array('eq' => 1));
		$collection ->getSelect()->columns('SUM(qty) as qty')->group('quote_id');

		return $collection->getData()[0]['qty'];
	}

	/**
	 * @param Varien_Event_Observer $observer
	 */
	public function updateProductCart(Varien_Event_Observer $observer)
	{

		$quote = Mage::getSingleton('checkout/cart')->getQuote();
		foreach ($quote->getAllItems() as $item) {

			if($item->getSku() == self::ROAD_HAZARD_PRODUCT){
				$qty = (int) $this->_countQty($item,'road_hazard_warranty');
				$item->setQty($qty);
				$item->save();
			}elseif($item->getSku() == self::ROAD_HAZARD_PRODUCT_SUV){
				$qty = (int) $this->_countQty($item,'road_hazard_warranty4wd');
				$item->setQty($qty);
				$item->save();
			}
		}
		$quote->save();
	}

	/**
	 * @param $qty
	 * @param $item
	 */
	protected function _removeFromCart($qty,$extraItem)
	{
		$quote = Mage::getSingleton('checkout/cart')->getQuote();
		foreach ($quote->getAllItems() as $item) {

			if($item->getSku() == $extraItem){

				if($qty >= $item->getQty()){
					Mage::getSingleton('checkout/cart')->removeItem($item->getId())->save();
				}else{
					$qty = $item->getQty() - $qty;
					$item->setQty($qty);
					$item->save();
				}
			}
		}
		$quote->save();
	}

	/**
	 * @param $productIds
	 * @param $sku
	 * @return mixed
	 */
	private function isCustomProductExist($requestIds = array(),$sku){

		$productIds = array();
		foreach($requestIds as $id){
			$entity_id = explode('_',$id);
			$productIds[] = $entity_id[0];
		}

		$product = Mage::getModel('catalog/product')->getCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('entity_id', array('in' => $productIds))
			->addAttributeToFilter('sku', array('eq' => $sku))
			->load();

		return  $product->getSize();
	}
		
}
