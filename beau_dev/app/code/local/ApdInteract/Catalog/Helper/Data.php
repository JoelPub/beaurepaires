<?php
// Display Attribute icons

class ApdInteract_Catalog_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Retrieves product configuration options
	 *
	 * @param Mage_Catalog_Model_Product_Configuration_Item_Interface $item
	 * @return array
	 *
	 * Modified base helper of Magento Mage::helper('catalog/product_configuration') to include price on the return array
	 * SD 7/21/2015
	 */
	public function getCustomOptions(Mage_Catalog_Model_Product_Configuration_Item_Interface $item)
	{
		$product = $item->getProduct();
		$parentProduct = Mage::getModel('catalog/product')->load($product->getId());
		$options = array();
		$optionIds = $item->getOptionByCode('option_ids');
		if ($optionIds) {
			$options = array();
			foreach (explode(',', $optionIds->getValue()) as $optionId) {
				$option = $product->getOptionById($optionId);
				if ($option) {
					$itemOption = $item->getOptionByCode('option_' . $option->getId());
					$group = $option->groupFactory($option->getType())
						->setOption($option)
						->setConfigurationItem($item)
						->setConfigurationItemOption($itemOption);

					foreach ($option->getValues() as $values) {

						if ($values->getId() == $itemOption['value']) {
							$price = $values->getPrice();
						}
					}

					if ('file' == $option->getType()) {
						$downloadParams = $item->getFileDownloadParams();
						if ($downloadParams) {
							$url = $downloadParams->getUrl();
							if ($url) {
								$group->setCustomOptionDownloadUrl($url);
							}
							$urlParams = $downloadParams->getUrlParams();
							if ($urlParams) {
								$group->setCustomOptionUrlParams($urlParams);
							}
						}
					}

					$options[] = array(
						'label' => $option->getTitle(),
						'value' => $group->getFormattedOptionValue($itemOption->getValue()),
						'print_value' => $group->getPrintableOptionValue($itemOption->getValue()),
						'option_id' => $option->getId(),
						'option_type' => $option->getType(),
						'custom_view' => $group->isCustomizedView(),
						'price' => $price, //added price
						'parent_category' => $parentProduct->getAttributeText('category_main'),
					);
				}
			}
		}

		$addOptions = $item->getOptionByCode('additional_options');
		if ($addOptions) {
			$options = array_merge($options, unserialize($addOptions->getValue()));
		}

		return $options;
	}

	public function categoryTitle($attribute_title){

		$title = array(
					'Tyres' => '/tyre',
					'Wheels'=> '/wheel',
					'Batteries' => '/battery'
				);

		return $title[$attribute_title];
	}

	public function getAttributesValue($_product, $attribute) {
		$attribute_text = $_product->getData($attribute . '_value');
		if($attribute_text == "" || $_product->getData($attribute)){
			$attribute_text = $_product->getAttributeText($attribute);

			// get the Admin attribute text/label ; this is will be used in Heirarchy Sorting of attributes
			if ($attribute == 'commercial_categorization'){

				$attributeId = Mage::getResourceModel('eav/entity_attribute')
					->getIdByCode('catalog_product', 'commercial_categorization');

				// get all the attribute options
				$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeId);
				foreach ( $attribute->getSource()->getAllOptions(true, true) as $option){
					$attributeArray[$option['value']] = $option['label'];

				}

				// get the option ID
				$attr = $_product->getResource()->getAttribute("commercial_categorization");
				if ($attr->usesSource()) {
					$optionId = $attr->getSource()->getOptionId($attribute_text);
				}

				// search the array using the option Id and get the Admin attribute text
				$search_array = $attributeArray;
				if (array_key_exists($optionId, $search_array)) {
					$attribute_text = $search_array[$optionId];
				}

			}
		}
		return $attribute_text;
	}

	public function getProductAttributeTextFromAttributesArray($_product, $attributes_array) {
		$attribute_text_array = array();
		foreach ($attributes_array as $attribute) {
			$attribute_text_array[$attribute] = $this->getAttributesValue($_product, $attribute);
		}
		return $attribute_text_array;
	}

	public function getRatingScore($attribute,$attribute_values){
		switch ($attribute) {
			case 'durability':
				return $attribute_values['durability'];
			case 'even_ware':
				return $attribute_values['even_ware'];
			case 'fuel_saver':
				return $attribute_values['fuel_saver'];
			case 'superior_braking_grip':
				return $attribute_values['superior_braking_grip'];
			case 'handling':
				return $attribute_values['handling'];
			case 'mileage':
				return $attribute_values['mileage'];
			case 'sports_performance_handling':
				return $attribute_values['sports_performance_handling'];
			case 'performance':
				return $attribute_values['performance'];
			case 'quiet_comfort':
				return $attribute_values['quiet_comfort'];
			case 'cycling':
				return $attribute_values['cycling'];
			default:
				return false;
		}
	}

	public function getSizeOfSearchedProduct($front,$rear){
		if (empty($front) && empty($rear)) {
			return;
		}
		$product = Mage::getModel('catalog/product')->getCollection()
			->addAttributeToSelect('size')
			->addAttributeToFilter('front',array('eq' => $front))
			->addFieldToFilter('rear',array('eq' => $rear))
			->getFirstItem();

		return $product->getSize();

	}

	public function setToVariable($var)
	{
		$var = strtolower($var);
		$var_new = str_replace(" ","_",$var);
		return $var_new;
	}

	public function getProductSession()
	{
		$_product = Mage::registry('current_product');
		//tyres
		$productcodes =   $_POST['productcodetyres'];
		$pc = $productcodes[$this->setToVariable($_product->getName())];
		if(count($pc)!=0)
		{
			$front = $pc['front'];
			$rear = $pc['rear'];
			return $pc;
		}
		//wheels
		$productcodesx =  $_POST['productcodewheels'];
		$pc = $productcodesx[$this->setToVariable($_product->getName())];
		if(count($pc)!=0)
		{
			$front = $pc['front'];
			$rear = $pc['rear'];
			return $pc;
		}

	}
	private function checkifbrandexist($urlslug)
	{
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$query = "SELECT * FROM cms_page WHERE identifier='$urlslug'";
		$results = $readConnection->fetchAll($query);
		return count($results);
	}
	public function getSlugurl($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}
	public function automatebrands()
	{

		$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'brand');
		foreach ($attribute->getSource()->getAllOptions(true, true) as $instance) {
			$identifier = "brand-".$this->getSlugurl($instance['label']);
			$count_cms = $this->checkifbrandexist($identifier);
			if(trim($instance['label'])!='' && $count_cms==0) {
				$cmsPageData = array(
					'title' => $instance['label'],
					'root_template' => 'one_column',
					'meta_keywords' => $instance['label'],
					'meta_description' => $instance['label'],
					'identifier' => $identifier,
					'content_heading' => $instance['label'],
					'stores' => array(0),//available for all store views
					'content' => "<p>Lorem ipsum dolor sit amet, ignota detraxit insolens ut pri, ut cibo laoreet ceteros vim. Agam rebum nominati mea ne. Cu menandri maluisset nam, facilis detracto quaestio pri an. Ex oblique accommodare cum, duo in sint noluisse, eum ad case gloriatur.<br /><br />Et pri omittam convenire. Ne usu vocent omnesque repudiare. An petentium intellegebat nam. An sed harum sapientem, cibo graeci aeterno pro eu, eam cu legere appareat postulant. Congue labores accusata an per, no facer menandri dissentias quo. Has ut harum latine hendrerit, aliquid dissentiunt consectetuer id his. Et pri omittam convenire. Ne usu vocent omnesque repudiare. An petentium intellegebat nam. An sed harum sapientem, cibo graeci aeterno pro eu, eam cu legere appareat postulant. Congue labores accusata an per, no facer menandri dissentias quo. Has ut harum latine hendrerit, aliquid dissentiunt consectetuer id his.<br /><br />Id eripuit accumsan persequeris mei, ut aeterno elaboraret liberavisse vis. Eum eros euripidis eloquentiam id. Vim quem dissentiet signiferumque et, per te solum mundi nullam. Ea dolorem noluisse his, paulo adolescens philosophia ex vim. Et pri omittam convenire. Ne usu vocent omnesque repudiare. An petentium intellegebat nam. An sed harum sapientem, cibo graeci aeterno pro eu, eam cu legere appareat postulant. Congue labores accusata an per, no facer menandri dissentias quo. Has ut harum latine hendrerit, aliquid dissentiunt consectetuer id his.<br /><br />In omnes dolore maluisset mei, pro vide movet sanctus id. Bonorum interesset signiferumque at eos, senserit evertitur eam te. Mea posse tantas te, abhorreant delicatissimi mel ex. Qui summo euripidis ut, ea dignissim intellegat nam, mei mutat liber constituto ne.<br /><br />Omnis moderatius at vel, habemus epicurei te mel. Solum antiopam id usu. Duo ex tota fabulas consectetuer. Nam exerci consetetur ad, vivendo appellantur ex mel, ne mel perpetua oportere. Ut cetero utamur alienum eam, cu eos viderer deleniti qualisque. Et pri omittam convenire. Ne usu vocent omnesque repudiare. An petentium intellegebat nam. An sed harum sapientem, cibo graeci aeterno pro eu, eam cu legere appareat postulant. Congue labores accusata an per, no facer menandri dissentias quo. Has ut harum latine hendrerit, aliquid dissentiunt consectetuer id his. Et pri omittam convenire. Ne usu vocent omnesque repudiare. An petentium intellegebat nam. An sed harum sapientem, cibo graeci aeterno pro eu, eam cu legere appareat postulant. Congue labores accusata an per, no facer menandri dissentias quo. Has ut harum latine hendrerit, aliquid dissentiunt consectetuer id his.</p>"
				);
				Mage::getModel('cms/page')->setData($cmsPageData)->save();
			}
		}
	}

	/**
	 * @param null $attrText
	 * @return array
	 */
	public function getBadgeClass($attr = null){

		$badge = array();
		if(!empty($attr)){
			$value = str_replace(' ', '', strtolower($attr));
			if (strpos($value, 'new') !== false){
				$value = 'new';
			}

			$badge = array('value' => $value, 'label' => $attr);
		}

		return $badge;
	}

	public function getBadgeSrc($overlay,$thumbImg){
                $badge = "";
		if ($thumbImg){
			if($overlay=='Best Seller'){
				$badge = Mage::getBaseUrl('media').'theme/' . Mage::getStoreConfig('badges/badges/bestsellerth');
			}elseif($overlay=='New Arrival'){
				$badge = Mage::getBaseUrl('media') .'theme/'. Mage::getStoreConfig('badges/badges/newth');
			}elseif($overlay=='On Sale'){
				$badge = Mage::getBaseUrl('media') .'theme/'. Mage::getStoreConfig('badges/badges/onsaleth');
			}elseif($overlay=='Coming Soon'){
				$badge = Mage::getBaseUrl('media') .'theme/'. Mage::getStoreConfig('badges/badges/comingsoonth');
			}
		}else{
			if($overlay=='Best Seller'){
				$badge = Mage::getBaseUrl('media').'theme/' . Mage::getStoreConfig('badges/badges/bestseller');
			}elseif($overlay=='New Arrival'){
				$badge = Mage::getBaseUrl('media') .'theme/'. Mage::getStoreConfig('badges/badges/new');
			}elseif($overlay=='On Sale'){
				$badge = Mage::getBaseUrl('media') .'theme/'. Mage::getStoreConfig('badges/badges/onsale');
			}elseif($overlay=='Coming Soon'){
				$badge = Mage::getBaseUrl('media') .'theme/'. Mage::getStoreConfig('badges/badges/comingsoon');
			}
		}
		return $badge;
	}

	public function checkForEmptyFields(){
		$products = Mage::getModel('catalog/product')->getCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('type_id', 'configurable')
			->setPageSize(500)
			->setCurPage(1);
		$data = array();
		foreach($products as $product){
			$simple_product = Mage::getModel('catalog/product')->load($product->getId());
			$category_id = $simple_product->getCategoryIds();
			$data[] = array(
				'Id' => $product->getId(),
				'Name' =>  $product->getName(),
				'Sku' =>  $product->getSku(),
				'Visiblity' =>  $product->getVisibility(),
				'Status' =>  $product->getStatus(),
				'Category_Id' => $category_id[0]
			);
		}
		return $data;
	}

	public function compareChildsNametoParent(){
		$configurable_products = Mage::getModel('catalog/product')->getCollection()
			->addAttributeToSelect('*')
			->addAttributeToFilter('type_id', 'configurable')
			->load();

		foreach($configurable_products as $product){
			$product = Mage::getModel('catalog/product')->load($product->getId());
			$simpleCollection = $this->_getCollectionOfSimpleProducts($product);
			foreach($simpleCollection as $simpleProduct){
				if ($simpleProduct->getName() !=  $product->getName()){
					return false;
				}else{
					return true;
				}
			}
		}
	}

	public function shouldDisplayMinimalPrice($product)
	{

		$productDetails = $this->_productDetails($product);

		$checkProductType = $this->isConfigurable($product);
		if ($checkProductType){
			$childProducts = $this->_getUsedProducts($product);
		}else{
			return array('rrp_price' => $product->getPrice(),
				'online_price' => $product->getFinalPrice(),
				'special_price' => $product->getSpecialPrice(),
				'product_details' => Mage::helper('core')->jsonEncode($productDetails),
			);
		}

		$childPriceLowest = '';
		$arrayPrice = [];
		$rrp = [];
		$sp = [];
		$price = array('rrp_price' => '',
				'online_price' => '',
				'product_details' => Mage::helper('core')->jsonEncode($productDetails),
			);

		if ( $childProducts ) {
			foreach ( $childProducts as $child ) {
				$_child = Mage::getModel('catalog/product')->load( $child->getId() );

				$isAvailable = $this->_isAvailable($_child);
				if($isAvailable){
					if ($_child->getFinalPrice() > 0 || ($_child->getFinalPrice() == 0 && (bool)$_child->getFreeProduct())) {
						$arrayPrice[] = $_child->getFinalPrice();
						$rrp[] = $_child->getPrice();
						$sp[] = $_child->getSpecialPrice();

						$productDetails['sizeOptions'][] = $this->_childSize($_child);
					}
				}
			}

			//Exclude Free Products when calculating lowest price for configurable products
			$priceWithNoneZero = array_filter($arrayPrice);
			if(count($priceWithNoneZero)>0){
				$indexLowestPrice = array_keys($priceWithNoneZero,min($priceWithNoneZero))[0];
			}else {
                            
                            if(count($arrayPrice)>0)
                            $indexLowestPrice = array_keys($arrayPrice,min($arrayPrice))[0];
			}

                        if(isset($indexLowestPrice)) :    
			//Assigning lowest price to overlay section
			$productDetails['sizeOptions'][$indexLowestPrice]['IsLowest'] = 1;
			$productDetails['sizeOptions']  = $this->sortdata($productDetails['sizeOptions'],'Title','Id');

			$price = array('rrp_price' => $rrp[$indexLowestPrice],
				'online_price' => $arrayPrice[$indexLowestPrice],
				'special_price' => $sp[$indexLowestPrice],
				'product_details' => Mage::helper('core')->jsonEncode($productDetails),
			);
                        
                        endif;

		} else {
			$price = array('rrp_price' => $product->getPrice(),
				'online_price' => $product->getFinalPrice(),
				'product_details' => Mage::helper('core')->jsonEncode($productDetails),
			);
		}

		return $price;
	}

	/**
	 * @param $dataArray
	 * @param $key
	 * @param string $id_key
	 * @return array
	 */
	public function sortdata($dataArray,$key,$id_key = 'entity_id'){

		$sizeOptions = array();
		foreach ($dataArray as $item){
			$sizeOptions[$item[$key]. '-'  . $item[$id_key]] = $item;
		}
		array_multisort(array_keys($sizeOptions), SORT_NATURAL, $sizeOptions);
		return $sizeOptions;

	}

	public function isConfigurable($prod){
		$configurable = false;
		if ($prod->getTypeId() == 'configurable'){
			$configurable = true;
		}
		return $configurable;
	}

	public function shouldDisplayFromText($product){
		$checkProductType = $this->isConfigurable($product);
		$collection = $this->_getCollectionOfSimpleProducts($product);
		if (($checkProductType) && (count($collection) > 1)){
			return " From ";
		}
		return /*" Price:"*/"";
	}

	private function _getCollectionOfSimpleProducts($prod){
		$configurable = Mage::getModel('catalog/product_type_configurable')->setProduct($prod);
		$simpleProductsCollection = $configurable->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
		return $simpleProductsCollection;
	}

	/**
	 *
	 * @param $product
	 */
	protected function _productDetails($product){

		return  array('Type'=>$product->getTypeId(),
			"Id"=>$product->getId(),
			"Name"=>$product->getName(),
			"Brand" => array(
				"Name" => $product->getAttributeText('overlay'),
				"Image" => $this->getSlugurl($product->getAttributeText('overlay'))
			),
			'ProductImage' => (string)Mage::helper('catalog/image')->init($product, 'small_image')->resize(75),
			"Badge" => array(
				"Name" => $product->getAttributeText('brand'),
				"Image" => Mage::helper('apdwidgets')->getBrandLogoUrl($product->getAttributeText('brand'))
			),
			"Price" => array(
				"OnlinePrice" => $product->getFinalPrice(),
				"RRP" => $product->getPrice(),
				"SpecialPrice" =>$product->getSpecialPrice()
			),
			'IsFreeProduct' => (bool)$product->getFreeProduct(),
			'sizeOptions' => array(),

		);

	}

	/**
	 * @param $child
	 * @return array
	 */
	protected function _childSize($_child = null){

		if($_child != null){

			return  array(
				'Id' =>$_child->getSize(),
				'Sku' => $_child->getSku(),
				'Title'  => $_child->getAttributeText('size'),
				'OnlinePrice'  => $_child->getFinalPrice(),
				'RRP' => $_child->getPrice(),
				'SpecialPrice' => $_child->getSpecialPrice(),
				'IsFreeProduct' => (bool)$_child->getFreeProduct(),
				'IsLowest' => 0
			);
		}
	}

	public function getPriceFromSearchVehicle($product){
		$rrp = null;
		$onlinePrice = null;
		$from = 'Price:';
		$price =[];

		//product details into array
		$productDetails = $this->_productDetails($product);

                
		$childProducts = $this->_getUsedProducts($product);
		if ( $childProducts ) {
			foreach ( $childProducts as $child ) {
				$_child = Mage::getModel('catalog/product')->load($child->getId());

				$searchedFrontVehicle = Mage::getModel('core/session')->getSizeF();
				$searchedRearVehicle = Mage::getSingleton('core/session')->getSize1F();
				$optionIdForFront = $this->_getAttributeIdOfSimpleProduct($_child,$searchedFrontVehicle);
				$optionIdForRear = $this->_getAttributeIdOfSimpleProduct($_child,$searchedRearVehicle);

				// load all child products and get its Price that matches the Size based from the searched vehicle (i.e. Front/Rear)
				$isAvailable = $this->_isAvailable($_child);
				if($optionIdForFront == $_child->getSize() && $isAvailable){
					if ($_child->getFinalPrice() > 0 || ($_child->getFinalPrice() == 0 && (bool)$_child->getFreeProduct())) {
						$price[] = $_child->getFinalPrice();
						$frontPrice[] = $_child->getFinalPrice();
						$tyreRrp[] = $_child->getPrice();
						$sp[] = $_child->getSpecialPrice();

						$productDetails['sizeOptions'][] = $this->_childSize($_child);
					}

				}elseif($optionIdForRear == $_child->getSize() && $isAvailable){
					if ($_child->getFinalPrice() > 0 || ($_child->getFinalPrice() == 0 && (bool)$_child->getFreeProduct())) {
						$price[] = $_child->getFinalPrice();
						$frontPrice[] = $_child->getFinalPrice();
						$tyreRrp[] = $_child->getPrice();
						$sp[] = $_child->getSpecialPrice();

						$productDetails['sizeOptions'][] = $this->_childSize($_child);
					}
				}

				//assign lowest price
				if((bool)count($price)){
					$productDetails['sizeOptions'][array_keys($price,min($price))[0]]['IsLowest'] = 1;
				}

			}

			//Exclude Free Products when calculating lowest price for configurable products
			$priceWithNoneZero = array_filter($price);
			if(count($priceWithNoneZero)>0){
				$indexLowestPrice = array_keys($priceWithNoneZero,min($priceWithNoneZero))[0];
			}else {

				if(count($price)>0)
					$indexLowestPrice = array_keys($price,min($price))[0];
			}

			$productDetails['sizeOptions']  = $this->sortdata($productDetails['sizeOptions'],'Title','Id');
			return 	array('rrp_price' => $tyreRrp[$indexLowestPrice],
				'online_price' => $price[$indexLowestPrice],
				'from' => $from,
				'special_price' => $sp[$indexLowestPrice],
				'product_details' => Mage::helper('core')->jsonEncode($productDetails),
			);
		}

	}
        
                

	private function _getAttributeIdOfSimpleProduct($simple,$size){

		$attr = $simple->getResource()->getAttribute('size');
		if ($attr->usesSource()) {
			$id = $attr->getSource()->getOptionId($size);
		}
		return $id;
	}

	private function _getUsedProducts($prod){
		$childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts( null, $prod );
		return $childProducts;
	}

	public function fetchCodes() {
		$codes = array();
		$json = Mage::helper('searchtyre')->getVehicleWheels(Mage::getSingleton('core/session')->getSeriesF());
		foreach($json->Items as $item) {
			foreach($item->WheelFitments as $v) {
				$codes[]=$v->FrontWheel->Code;

			}
		}

		array_unique($codes);
		return $codes;
	}

	public function matchedPrice($_product,$codes) {
		$final= 0 ;
		$childProducts = $this->_getUsedProducts($_product);
		if ( $childProducts ) {
			foreach ( $childProducts as $child ) {
				$_child = Mage::getModel('catalog/product')->load($child->getId());

				if(in_array($child->getSku(),$codes)) {
					$final = $_child->getFinalPrice();
					return $final;
				}

			}
			return;
		}

		return $final;
	}

	public function getPromoBlock($url) {
		if (strpos($url, 'tyres') !== false) {
			return 'promo-tyres-cdp';
		}elseif (strpos($url, 'batteries') !== false) {
			return 'promo-batteries-cdp';
		}elseif (strpos($url, 'catalogsearch') !== false) {
			return 'promo-tyres-results-cdp';
		}else{
			return;
		}
	}


	/**
	 * Display CMS Block content
	 *
	 * @param string $identifier
	 * @return string
	 */
	public function showPromoBlock($identifier = ''){

		$blockHtml = '';

		if($identifier){
			$blocks = Mage::getModel('dynamicblock/dynamicblock')->getAllBlocksPerPosition($identifier);
			$block_id = $blocks->getFirstItem()->getIdentifier();
			if($block_id){
				$blockHtml = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($block_id)->toHtml();
			}
		}

		return $blockHtml;
	}

	public function getTyreAttributesArray(){

		return $attributes_array = array('consumer_categorization', 'commercial_categorization', 'battery_categorization',
			'application', 'sports_performance_handling', 'durability', 'mileage', 'even_ware', 'handling', 'superior_braking_grip',
			'quiet_comfort', 'fuel_saver', 'performance', 'cycling', 'grip', 'slow_wear', 'off_road', 'value',
			'fuel_economy','grip_performance','handling_performance','off_road_performance','wet_performance','low_noise',
			'comfort_performance','sports_performance','braking_performance','even_wear','dry_performance','wet_dry_performance',
			'all_terrain','winter','original_equipment','run_on_flat','traction','cornering_performance','road_hazard_resistant');
		// no icons yet superior_grip, excellent_handling , outstanding_braking , superior_comfort , slow_wear

	}

	public function getBatteryAttributesArray(){

		return $attributes_array = array('durability_battery', 'performance_battery','value_battery','cycling_battery','application');
	}

	public function getCategoryName($categoryId){
		$categoryIds = $categoryId;
		if (count($categoryId)) {
			$catId = $categoryIds[0];
			$_category = Mage::getModel('catalog/category')->load($catId);
			$catName = strtolower($_category->getName());

			return $catName;
		}

	}

	public function isSpecificDateValid($from,$to){
		$today =  strtotime(Mage::getModel('core/date')->date('Y-m-d'));
		if (($today >= strtotime( $from) && $today <= strtotime($to) || $today >= strtotime( $from) && is_null($to))){
			return true;
		}
		return false;
	}
	/*
	 * This method determines what class should be use on grid view based on the product's price
	 * @param array of product Price
	 * @return string - class name
	 */

	public function gridViewClass($data){
		$show = null;
		if (($data['rrp_price'] > $data['online_price'] && $data['online_price'] != 0 )){
			$show = 'show-rrp-price';
		}elseif ($data['online_price'] != 0){
			$show = 'show-online-price';
		}elseif (($data['online_price'] >= $data['rrp_price']) || ($data['online_price'] == 0)) {
			$show = 'show-none-price';
		}
		return $show;
	}

	/*
     * This method determines if the product is under tyre category
     * @param object   $product
     * @return boolean $flag
     */
	public function checkCategoryOfProduct($product) {
		$categoryCollection = $product->getCategoryIds();
		$flag=0;
		foreach($categoryCollection as $category_id) {

			if($category_id==41 || $category_id==42)
				$flag = $category_id;
			break;
		}


		return $flag;
	}

	/*
	 * This method checks when to display ellipsis (...) based from the product name's length - See BFT-2128
	 * @param string
	 * @return string
	 */

	public function getFormattedProductName($productName){
		if (strlen($productName) > 25){
			return substr($productName,0,25) . '&#8230;';
		}
		return $productName;
	}
        
        public function checkIfSuvOr4wd($_product) {
            return (strpos($_product->getAttributeText('application'),'SUV')==false && strpos($_product->getAttributeText('application'),'4WD')==false )? true : false; 
        }
        
        public function getImportantMsg() {
        $module = Mage::app()->getRequest()->getModuleName();
        $layer = Mage::getSingleton('catalog/layer');
        $_category = $layer->getCurrentCategory();
        $currentCategoryId = $_category->getId();
        $categoryPost = $this->_getPost('category');
        
        if ($currentCategoryId == '' || $currentCategoryId == 2)
            $currentCategoryId = Mage::getSingleton('core/session')->getCategoryF();

        $msg = '';
        if(Mage::getSingleton('core/session')->getTSeriesNameF() == '' && Mage::getSingleton('core/session')->getSizeF() =='' && Mage::getSingleton('core/session')->getSizeWF()=='')
        if ($module == 'catalogsearch')
            $msg = 'Remember not all products will fit your vehicle. Please enter your vehicle above';
        elseif ((isset($categoryPost) && $categoryPost == '42') || (!isset($categoryPost) && $currentCategoryId == 42))
            $msg = 'Remember not all wheels will fit your vehicle. Please enter your vehicle or wheel brand & size above';
        elseif ((isset($categoryPost) && $categoryPost == '41') || $currentCategoryId == '41')
            $msg = 'Remember not all tyres will fit your vehicle. Please enter your vehicle or tyre size above';
        
        return $msg;
        
        }
        
        function _getPost($parameter){
            return Mage::app()->getRequest()->getPost($parameter);
	}

	protected function _isAvailable($product){

		$available = false;
		if($product->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED){
			$available = true;
		}

		return $available;
	}
        
        public function getPriceFromSearchVehicleWheel($product, $attribute) {
        $rrp = null;
        $onlinePrice = null;
        $from = 'Price:';
        $price = [];

        //product details into array
        $productDetails = $this->_productDetails($product);
        $series = Mage::getSingleton('core/session')->getSeriesF();        

        
        $sizes_array = Mage::Helper('apdinteract_searchresult')->sanitizeWheelSizes($series);
        
        $sizes_array = array_unique($sizes_array);
        $sizes = array();
        $values = array();
        $childProducts = $this->_getUsedProducts($product);
        if ($childProducts) {
            foreach ($childProducts as $child) {
                $_child = Mage::getModel('catalog/product')->load($child->getId());

                //echo $_child->getAttributeText('rim_diameter_configurable').'<br>';
                // load all child products and get its Price that matches the Size based from the searched vehicle (i.e. Front/Rear)
                $isAvailable = $this->_isAvailable($_child);
                if ($isAvailable && ($this->_isSizeFit($sizes_array, $_child->getAttributeText($attribute)) && $attribute=='rim_diameter_configurable') || ($_child->getAttributeText($attribute) == Mage::getSingleton('core/session')->getSizeF() && $attribute=='size')) {
                    if ($_child->getFinalPrice() > 0 || ($_child->getFinalPrice() == 0 && (bool) $_child->getFreeProduct())) {
                        $sizes[] = $_child->getAttributeText($attribute);
                        $values[$_child->getAttributeText($attribute)] = array('price'=>$_child->getFinalPrice(), 'rrp'=>$_child->getPrice(), 'sp'=>$_child->getSpecialPrice());
                        /*$price[] = $_child->getFinalPrice();
                        $frontPrice[] = $_child->getFinalPrice();
                        $tyreRrp[] = $_child->getPrice();
                        $sp[] = $_child->getSpecialPrice();*/

                        $productDetails['sizeOptions'][] = $this->_childSize($_child);
                    }
                }


                //assign lowest price
                if ((bool) count($price)) {
                    $productDetails['sizeOptions'][array_keys($price, min($price))[0]]['IsLowest'] = 1;
                }
            }

            //Exclude Free Products when calculating lowest price for configurable products
            $priceWithNoneZero = array_filter($price);
            if (count($priceWithNoneZero) > 0) {
                $indexLowestPrice = array_keys($priceWithNoneZero, min($priceWithNoneZero))[0];
            } else {

                if (count($price) > 0)
                    $indexLowestPrice = array_keys($price, min($price))[0];
            }
            
            sort($sizes);
           
            $productDetails['sizeOptions'] = $this->sortdata($productDetails['sizeOptions'], 'Title', 'Id');
            return array('rrp_price' => $values[$sizes[0]]['rrp'],
                'online_price' => $values[$sizes[0]]['price'],
                'from' => $from,
                'special_price' => $values[$sizes[0]]['sp'],
                'product_details' => Mage::helper('core')->jsonEncode($productDetails),
            );
        }
    }

    private function _isSizeFit($sizes, $option) {
        $result = false;        
        foreach ($sizes as $size):
            if (strpos($option, $size . ' ') !== false):
                $result = true;
                break;
            endif;

        endforeach;

        return $result;
    }

}