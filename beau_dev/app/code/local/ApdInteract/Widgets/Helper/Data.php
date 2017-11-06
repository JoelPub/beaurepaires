<?php

class ApdInteract_Widgets_Helper_Data extends Mage_Core_Helper_Abstract {

    private $_barHtml;
    private $_starHtml;
    private $_productRating;
    protected $_count;

    public function getStarHtmlFromRating($percent) {

        if (!isset($this->_starHtml[$percent])) {
            $this->_starHtml[$percent] = $this->_getHtmlFromRating($percent, 5, 'fa fw fa-star');
        }
        return $this->_starHtml[$percent];
    }

    public function getBarHtmlFromRating($percent) {
        if (!isset($this->_barHtml[$percent])) {
            $this->_barHtml[$percent] = $this->_getHtmlFromRating($percent, 3, 'fa fa-check-circle');
        }
        return $this->_barHtml[$percent];
    }

    private function _getHtmlFromRating($percent, $maxscore, $class) {
        $rating = $percent / (100 / $maxscore);

        if ($rating > $maxscore)
            $rating = $maxscore;
        if ($rating < 0)
            $rating = 0;

        $stars = ceil($rating);
        $gaps = floor($maxscore - $rating);


        $html = '';
        for ($i = 0; $i < $stars; $i++) {
            $html .= '<i class="' . $class . ' full"></i>';
        }
        for ($i = 0; $i < $gaps; $i++) {
            $html .= '<i class="' . $class . ' empty"></i>';
        }

        return $html;
    }

    public function getRatingSummary($product_id) {

        if (!isset($this->_productRating[$product_id])) {

            $websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
            $websiteNz = Mage::getModel('core/website')->load('goodyear_nz', 'code');

            $stores = array($websiteAu->getId(), $websiteNz->getId(), 1);

            $summaryDataCollection = Mage::getModel('review/review_summary')
                    ->getCollection()
                    ->AddFieldToFilter("entity_pk_value", $product_id)
                    ->AddFieldToFilter("store_id", $stores);
            ;

            $rating_summary = 0;
            $reviews_count = 0;
            $counter = 0;
            foreach ($summaryDataCollection as $summary) {
                if ($summary->getData('rating_summary') > 0) {
                    $rating_summary+=$summary->getData('rating_summary');
                    $reviews_count+=$summary->getData('reviews_count');
                    $counter++;
                }
            }

            /* if (!isset($summaryData['rating_summary'])) {
              $summaryData['rating_summary'] = false;
              } */

            $rating = @($rating_summary / $counter);
            $count = @($reviews_count / $counter);

            $this->_productRating[$product_id] = floor($rating);
            $this->_count = floor($count);

              return $this->_productRating[$product_id];
        }
    }

    public function getProductRatingSummaryHtml($product_id, $url) {
        $getRatingSummary = $this->getRatingSummary($product_id);
        $rating = "";
        if (!empty($getRatingSummary)) {
            $rating = $this->getStarHtmlFromRating($getRatingSummary);
        }

        $count = Mage::helper('apdinteract_review')->getReviewCount($product_id);
        $html = <<<EOF
			<div class="star-rating">
			{$rating}
			<span class="review-links"><a href="{$url}#customer-reviews" class="customer-reviews">{$count} Review(s)</a> | <a class="add-review" href="{$url}#review-form">Add your review</a></span>
			</div>
EOF;

        return $html;
    }

    public function getProductFeatureRatingHtml($percent) {
        $rating = $this->getBarHtmlFromRating($percent);
        if (empty($rating))
            return;


        $html = <<<EOF
                <div class="feature-rating">
                {$rating}
                </div>
EOF;
        return $html;
    }

    public function getBrandLogo($brand,$brandurl= false) {

        if(!$brandurl)
        $brandurl = "/brand-" . Mage::helper('apdinteract_catalog')->getSlugurl($brand);

        if (!empty($brand)) {
            return '<a href="' . $brandurl . '"><img class="dealer-logo" alt="' . $brand . '" src="' . Mage::getDesign()->getSkinUrl('images/brands/' . strtolower($brand) . '-logo.png') . '" /></a>';
        }
        return '';
    }

    /**
     * brand logo url
     * @param $brand
     * @return string
     */
    public function getBrandLogoUrl($brand) {

        if (!empty($brand)) {
            return Mage::getDesign()->getSkinUrl('images/brands/' . strtolower($brand) . '-logo.png');
        }
        return '';
    }

    public function getCategorization($product) {

        // Do some special logic here to determine the actual category option
        $consumer = $product->getAttributeText('consumer_categorization');
        $commercial = $product->getAttributeText('commercial_categorization');

        $battery = $product->getAttributeText('battery_categorization');

        return $consumer . " " . $commercial . " " . $battery;
    }

    public function getAltforIcon($icon, $type) {
        if ($type == 'tw')
            $alt = Mage::getStoreConfig('tooltip/tyrewheel/apdinteract_' . $icon . '_tw');
        else
            $alt = Mage::getStoreConfig('tooltip/batteries/apdinteract_' . $icon . '_b');
        return $alt;
    }

    public function getProductFeatureHtml($fields, $num, $type) {
        $html = "";
        $fields->product->attribute_code[$num];
        $icon = $fields->product->bar_ratingclass[$num];
        $alt = $this->getAltforIcon($fields->product->attribute_code[$num], $type);

        $html .= <<<EOF
                        <li>
          <i class="sprite icons-large {$icon}" aria-hidden="true" data-tooltip aria-haspopup="true" class="has-tip" title="{$alt}"><span class="show-for-sr">{$fields->product->bar_ratingtext[$num]}</span></i>
          <p data-equalizer-watch>{$fields->product->bar_ratingtext[$num]}</p>
          {$fields->product->bar_rating[$num]} 
        </li>
EOF;

        return $html;
    }

    public function getProductListViewHtml($fields) {
        list($onlinePriceDollar, $onlinePriceCents) = explode(".", $fields->product->finalPriceHtml);

        $featureHtml = "";
        for ($i = 0; $i < count($fields->product->bar_rating); ++$i) {
            $featureHtml .= $this->getProductFeatureHtml($fields, $i, 'tw');
        }


        $html = <<<EOF
        <!-- Start desktop product list iteration -->
        <div class="row {$fields->product->no_badge}">
        <div class="small-4 medium-3 columns product-media-section">
           {$fields->product->badge_big}
           <a href="{$fields->product->url}" title="{$fields->product->title}" class="product-image">
             <img src="{$fields->product->img->src}" class="expand" alt="{$fields->product->img->label}" />
           </a>
        </div>
        <div class="small-8 medium-6 columns product-info-section">
              {$fields->product->badge}
              {$fields->product->brandlogo} 
               <h3 class="product-title">
                   <a href="{$fields->product->url}" title="{$fields->product->name_stripped}">{$fields->product->name}</a>
               </h3>
              <span class="product-type">
                  {$fields->product->subcategory}
              </span>
               {$fields->product->ratingSummary}
              <ul class="no-bullet product-features small-block-grid-3 large-block-grid-3" data-equalizer >
                {$featureHtml}
              </ul>
              <p class="product-description">
                {$fields->product->short_description}
              </p>
            </div>
            <div class="small-8 medium-3 columns pricing-section">
            <!-- Hide RRP Price for phase 1 7/23/15 display none-->
              <div class="rrp" style="display:{$fields->product->show_rrp}">
                  <span class="rrp-label">{$fields->product->rrpText}</span>
                  <span class="rrp-amount">{$fields->product->priceHtml}</span>
              </div>
              <div class="online-price" style="display:{$fields->product->show_onp}">
                  <span class="online-price-label">{$fields->product->onlineText}</span>
                  <span class="online-price">{$onlinePriceDollar}<sup>.{$onlinePriceCents}</sup><sub>/ea</sub></span>
              </div>
              {$fields->product->addButtons}

              {$fields->product->addStoreButton}
              <!-- compare link url removed for now-->

              {$fields->product->addToCompareLink}
              {$fields->product->viewCompareLink}

             </div>
        </div>
EOF;
        return $html;
    }

    public function getProductListViewHtmlWheels($fields) {

        $html = <<<EOF
        <!-- Start desktop product list iteration -->
        <div class="row {$fields->product->no_badge}">
        <div class="small-4 medium-3 columns product-media-section">
           {$fields->product->badge_big}
           <a href="{$fields->product->url}" title="{$fields->product->title}" class="product-image">
             <img src="{$fields->product->img->src}" class="expand" alt="{$fields->product->img->label}" />
           </a>
        </div>
        <div class="small-8 medium-6 columns product-info-section">
        	{$fields->product->badge}
                {$fields->product->brandlogo}
               <h3 class="product-title">
                   <a href="{$fields->product->url}" title="{$fields->product->name_stripped}">{$fields->product->name}</a>
               </h3>
               {$fields->product->ratingSummary}
	       {$fields->product->finish}
               <p class="product-description">
                {$fields->product->short_description}
               </p>
            </div>
            <div class="small-8 medium-3 columns pricing-section">
            <!-- Hide RRP Price for phase 1 7/24/15 display none-->
              <div class="rrp" style="display:{$fields->product->show_rrp}">
                  <span class="rrp-label">{$fields->product->rrpText}</span>
                  <span class="rrp-amount">{$fields->product->priceHtml}</span>
              </div>
              <div class="online-price" style="display:{$fields->product->show_onp}">
                  <span class="online-price-label">{$fields->product->onlineText}</span>
                  <span class="online-price">{$fields->product->finalPriceHtml}<sub>/ea</sub></span>
              </div>
              {$fields->product->addButtons}
              {$fields->product->addToCompareLink}
              {$fields->product->viewCompareLink}
             </div>
        </div>
EOF;
        return $html;
    }

    public function getProductListViewHtmlBattery($fields) {
        $featureHtml = "";
        for ($i = 0; $i < count($fields->product->bar_rating); ++$i) {
            $featureHtml .= $this->getProductFeatureHtml($fields, $i, 'b');
        }

        $html = <<<EOF
        <!-- Start desktop product list iteration -->
        <div class="row {$fields->product->no_badge}">
        <div class="small-4 medium-3 columns product-media-section">
           {$fields->product->badge_big}
           <a href="{$fields->product->url}" title="{$fields->product->title}" class="product-image">
             <img src="{$fields->product->img->src}" class="expand" alt="{$fields->product->img->label}" />
           </a>
        </div>
        <div class="small-8 medium-6 columns product-info-section">
        		 {$fields->product->badge}
                 {$fields->product->brandlogo}
               <h3 class="product-title">
                   <a href="{$fields->product->url}" title="{$fields->product->name_stripped}">{$fields->product->name}</a>
               </h3>
              <span class="product-type">
                  {$fields->product->subcategory}
              </span>
               {$fields->product->ratingSummary}
              <ul class="no-bullet product-features small-block-grid-3 large-block-grid-3" data-equalizer >
                 {$featureHtml}
              </ul>
              <p class="product-description">
                {$fields->product->short_description}
              </p>
            </div>
            <div class="small-8 medium-3 columns pricing-section">
            <!-- Hide RRP Price for phase 1 7/24/15 display none-->
              <div class="rrp" style="display:{$fields->product->show_rrp}">
                  <span class="rrp-label">{$fields->product->rrpText}</span>
                  <span class="rrp-amount">{$fields->product->priceHtml}</span>
              </div>
              <div class="online-price" style="display:{$fields->product->show_onp}">
                  <span class="online-price-label">{$fields->product->onlineText}</span>
                  <span class="online-price">{$fields->product->finalPriceHtml}<sub>/ea</sub></span>
              </div>
              {$fields->product->addButtons}
              {$fields->product->addStoreButton}
              <!-- compare link url removed for now-->
              {$fields->product->addToCompareLink}
              {$fields->product->viewCompareLink}

             </div>
        </div>
EOF;
        return $html;
    }

    public function getMobileProductListViewHtml($fields) {
        $html = <<<EOF
        <!-- THIS FUNCTION IS NO LONGER BEING CALLED DURING MOBILE VIEW 7/8/2015 -->
        <!-- Start mobile product list iteration -->
              <div class="row">
                <div class="small-12 columns">
                  <div class="row">
                    <div class="small-6 columns">
                      {$fields->product->badge_big}
                      <a href="{$fields->product->url}" title="{$fields->product->title}" class="product-image">
                        <img src="{$fields->product->img->src}" class="expand" alt="{$fields->product->img->label}" />
                      </a>
                    </div>
                    <div class="small-6 columns">
                    	{$fields->product->badge}
                        {$fields->product->brandlogo}
                        <h1 class="product-title">
                          <a href="{$fields->product->url}" title="{$fields->product->name_stripped}">{$fields->product->name}</a>
                        </h1>
                        <span class="product-type">
                            {$fields->product->subcategory}
                        </span>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                           {$fields->product->ratingSummary}
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="small-12 columns">
                      <ul class="no-bullet product-features small-block-grid-3 large-block-grid-3">
                        <li>
                            <i class="fa fa-2x fa-tachometer"></i>
                            <p>{$fields->product->bar_ratingtext[0]}</p>
                            <div class="feature-rating">
                              {$fields->product->bar_rating[0]}
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-2x fa-tachometer"></i>
                            <p>{$fields->product->bar_ratingtext[1]}</p>
                            <div class="feature-rating">
                              {$fields->product->bar_rating[1]}
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-2x fa-tachometer"></i>
                            <p>{$fields->product->bar_ratingtext[0]}</p>
                            <div class="feature-rating">
                              {$fields->product->bar_rating[2]}
                            </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="row">
                    <div class="small-12 columns">
                        <h4 class="product-price">{$fields->product->priceHtml}</h4>
                        <ul class="button-group">
                            <li>{$fields->product->addButtonsMobile}</li>
                            <li>{$fields->product->addStoreButtonMobile}</li>
                        </ul>
                        <!-- compare link url removed for now-->
                    </div>
                  </div>
                </div>
              </div>
EOF;
        return $html;
    }

    public function getBestSellers() {
                
        
        $store_id = Mage::app()->getStore()->getStoreId();
        
        $cacheId = 'home_popular_products_'.$store_id;
        
      if(false !== ($data = Mage::app()->getCache()->load($cacheId))):
            $results = unserialize($data);
        else:
            $query = "SELECT SUM(qty_ordered) AS ordered_qty, sku, name AS order_items_name, IF(parent_id IS NOT NULL AND visibility != 4, parent_id, product_id) AS final_product_id FROM (SELECT order_items.qty_ordered, order_items.sku, order_items.name, order_items.product_id, cpr.parent_id, cat_index.store_id, cat_index.visibility, cat_index.category_id FROM `sales_flat_order_item` AS `order_items` INNER JOIN `sales_flat_order` AS `order` ON `order`.entity_id = order_items.order_id AND `order`.state != 'canceled' LEFT JOIN catalog_product_relation AS cpr ON cpr.child_id = order_items.product_id LEFT JOIN catalog_category_product_index AS cat_index ON cat_index.product_id = order_items.product_id WHERE parent_item_id IS NULL AND cat_index.store_id = '$store_id' AND order_items.created_at>'2015-08-04 00:00:00') AS T1 GROUP BY final_product_id ORDER by ordered_qty DESC limit 0,12";

            $resource = Mage::getSingleton('core/resource');

            $readConnection = $resource->getConnection('core_read');

            $results = array();
            $collection = $readConnection->fetchAll($query);
            
            foreach($collection as $row) {
                $results[] = array("final_product_id" => $row['final_product_id']);
            }

            Mage::app()->getCache()->save(serialize($results), $cacheId);
            
            $readConnection->closeConnection();
            
        endif;
        
       
        
        return $results;
    }

    /**
     * From default bestseller aggregated table
     * @param int $limit
     * @return mixed
     */
    public function getBestSellerProducts($limit=12){

            $storeId = (int) Mage::app()->getStore()->getId();

            // Date
            $date = new Zend_Date();
            $toDate = $date->setDay(1)->getDate()->get('Y-MM-dd');
            $fromDate = $date->subMonth(2)->getDate()->get('Y-MM-dd');

            $collection = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addStoreFilter()
                ->addPriceData()
                ->addTaxPercents()
                ->addUrlRewrite()
                ->setPageSize($limit);

            $collection->getSelect()
                ->joinLeft(
                    array('aggregation' => $collection->getResource()->getTable('sales/bestsellers_aggregated_monthly')),
                    "e.entity_id = aggregation.product_id AND aggregation.store_id={$storeId} AND aggregation.period BETWEEN '{$fromDate}' AND '{$toDate}'",
                    array('SUM(aggregation.qty_ordered) AS sold_quantity')
                )
                ->group('e.entity_id')
                ->order(array('sold_quantity DESC', 'e.created_at'));

            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

            return $collection;
    }

    public function getFullProductUrl($product) {
        $categoryIds = $product->getCategoryIds();
        $lastid = $categoryIds[0];
        $purl = $product->getProductUrl();
        $cat_name = "";
        if ($lastid) {
            $_category = Mage::getModel('catalog/category')->load($lastid);
            $cat_name = strtolower($_category->getName()) . '/';
        }
        $purl_ar = explode("/", $purl);
        $pur_l = count($purl_ar);
        if ($pur_l > 9) {
            $key = $purl_ar[$pur_l - 2];
        } else {
            $key = $purl_ar[$pur_l - 1];
        }

        if (is_numeric($key))
            $key = $purl_ar[$pur_l - 4];

        $productUrl = "/" . $cat_name . $key;

        return $productUrl;
    }

    public function getSimplesOfConfigurable($product_id) {
        $configurableProduct = Mage::getModel('catalog/product')->load($product_id);
        $simpleCollection = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $configurableProduct);

        $simples = '';
        foreach ($simpleCollection as $simpleProduct) {
            $simples[] = $simpleProduct->getSku();
        }


        return $simples;
    }

    public function getOrderData($order) {

        $items = $order->getAllVisibleItems();
        $item_count = count($items);
        $data = $data_wiser = '';
        $i = 0;
        foreach ($items as $itemId => $item):

            $i++;

            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $cat_ids = $product->getCategoryIds();
            $cat_name = Mage::getModel('catalog/category')->load($cat_ids[count($cat_ids) - 1])->getName();
            $sku = $item->getSku();
            $name = $item->getName();
            $qty = (int) $item->getQtyOrdered();
            $subtotal = $item->getRowTotalInclTax();
            $price = sprintf('%01.2f', (float) $item->getPrice());
            $data .= "
	        
	        {
			   'sku': '" . $sku . "',
			   'name': '" . $name . "',
			   'category': '" . $cat_name . "',
			   'price': '" . $price . "',
			   'quantity': " . $qty . "
			 }";

            $data_wiser .= "{page: 'cart page',sku: '" . $item->getSku() . "', qty: '" . $qty . "', revenue: '" . $subtotal . "'}";
            if ($item_count > $i) {
                $data.=",";
                $data_wiser .=",";
            }

        endforeach;

        return array(
            "ecom" => $data,
            "wiser" => $data_wiser
        );
    }

    public function getWiser($_product) {

        if ($_product->getTypeId() == "configurable") {
            $product_id = $_product->getId();
            $simples = $this->getSimplesOfConfigurable($product_id);
            $count = count($simples) - 1;
        } else {
            $simples[] = $_product->getSku();
            $count = 1;
        }
        $data_wiser = "";

        for ($i = 0; $i <= $count; $i++) {
            $sku = $simples[$i];
            if (trim($sku) != '') {
                $data_wiser .= "wiseAnalytics.track('view',{page: 'product page',sku: '" . $sku . "'});\n";
            }
        }

        return $data_wiser;
    }

    public function displayAnalytics($tag) {

        $api = Mage::getStoreConfig('wiser/wisersetting/wiser_analytics');
        $store = Mage::getStoreConfig('wiser/wisersetting/wiser_store_id');

        $html = "";



        if ($tag == 'checkout') { // check if on success page
            $order_id = Mage::getSingleton('checkout/type_onepage')->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($order_id);
            $address = $order->getBillingAddress();
            $data = Mage::helper('apdwidgets')->getOrderData($order);
            $city = Mage::helper('apdinteract_searchresult')->getCityLocation($order->getIncrementId());
            $state = Mage::helper('apdinteract_searchresult')->getStateLocation($order->getIncrementId());
            $ecom = $data['ecom'];
            $order_id = $order->getIncrementId();
            $total = str_replace(",", "", number_format(round($order->getGrandTotal(), 2), 2));
            $tax = str_replace(",", "", number_format(round($order->getTaxAmount(), 2), 2));
            $shipping = str_replace(",", "", number_format(round($order->getShippingAmount(), 2), 2));
            $wiser = $data['wiser'];
            $html = "
                <script>
                	
                	var products = [{$ecom}];
                    dataLayer = [{
                        'event': 'ecomEvent',
                        'transactionId': '{$order_id}',
                        'transactionAffiliation': 'Beaurepaires',
                        'transactionTotal': {$total},
                        'transactionTax': {$tax},
                        'ransactionShipping': '{$shipping}',
                        'transactionCity': '{$city}',
                        'transactionState': '{$state}',
                        'transactionCountry': 'Australia',
                        'transactionProducts': products
                    }];
                	
                </script>";
            if ($api != '' && $store != '') {
                $html.="
	                <script src=\"https://s3.amazonaws.com/wpanalytics/web/w.js\"></script>
	                <script>                    

	                    var myProductList = [{$wiser}];
	                    wiseAnalytics.init('{$api}', {$store});
	                    wiseAnalytics.sales(myProductList);

	                </script>
	                ";
            }
        } else if ($tag == 'product') { // check if on product view page
            $_product = Mage::registry('current_product');
            $data_wiser = Mage::helper('apdwidgets')->getWiser($_product);

            if ($api != '' && $store != '') {
                $html = "
	                <script src=\"https://s3.amazonaws.com/wpanalytics/web/w.js\"></script>
	                <script>
	                    wiseAnalytics.init('{$api}', {$store});
	        			{$data_wiser}
	                </script>
	                 ";
            }
        }


        echo $html;
    }

    public function getAnalyticsAsJson($order_id) {

        $order = Mage::getModel('sales/order')->load($order_id);
        $address = $order->getBillingAddress();
        $ecom = Mage::helper('apdwidgets')->getOrderDataJson($order);
        $city = Mage::helper('apdinteract_searchresult')->getCityLocation($order->getIncrementId());

        $state = Mage::helper('apdinteract_searchresult')->getStateLocation($order->getIncrementId());
        $order_id = $order->getIncrementId();
        $total = str_replace(",", "", number_format(round($order->getGrandTotal(), 2), 2));
        $tax = str_replace(",", "", number_format(round($order->getTaxAmount(), 2), 2));
        $shipping = str_replace(",", "", number_format(round($order->getShippingAmount(), 2), 2));
        $store = Mage::app()->getStore();
        $storeName = $store->getName();
        $country = $this->_getDefaultCountry();
        $request = $order->getRequestType();



        $array = array(
            'transactionProducts' => $ecom,
            'event' => 'ecomEvent',
            'transactionId' => $order_id,
            'transactionAffiliation' => $request,
            'transactionTotal' => $total,
            'transactionTax' => $tax,
            'transactionShipping' => $shipping,
            'transactionCity' => $city,
            'transactionState' => $state,
            'transactionCountry' => $country
        );

        return $array;
    }

    public function getOrderDataJson($order) {


        $items = $order->getAllVisibleItems();
        $item_count = count($items);
        $i = 0;
        $data = '';
        $array = array();
        foreach ($items as $itemId => $item):


            $i++;


            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $cat_ids = $product->getCategoryIds();
            $cat_name = Mage::getModel('catalog/category')->load($cat_ids[count($cat_ids) - 1])->getName();
            $sku = $item->getSku();
            $name = $item->getName();
            $qty = (int) $item->getQtyOrdered();
            $subtotal = $item->getRowTotalInclTax();
            $price = sprintf('%01.2f', (float) $item->getPrice());
            $array[] = array(
                'sku' => $sku,
                'name' => $name,
                'category' => $cat_name,
                'price' => $price,
                'quantity' => $qty
            );

        endforeach;

        return $array;
    }

    private function _getDefaultCountry() {
        $countryCode = Mage::getStoreConfig('general/country/default');
        $country = Mage::getModel('directory/country')->loadByCode($countryCode);
        return $country->getName();
    }

    public function getCountryHtmlSelect($defValue = null, $name = 'country_id', $id = 'country', $title = 'Country', $class = 'validate-select', $extraParams = 'required') {
        Varien_Profiler::start('TEST: ' . __METHOD__);

        $directoryBlockData = Mage::app()->getLayout()->createBlock('directory/data');

        if (is_null($defValue)) {
            $defValue = $directoryBlockData->getCountryId();
        }
        $cacheKey = 'DIRECTORY_COUNTRY_SELECT_STORE_' . Mage::app()->getStore()->getCode();
        if (Mage::app()->useCache('config') && $cache = Mage::app()->loadCache($cacheKey)) {
            $options = unserialize($cache);
        } else {
            $options = $directoryBlockData->getCountryCollection()->toOptionArray();
            if (Mage::app()->useCache('config')) {
                Mage::app()->saveCache(serialize($options), $cacheKey, array('config'));
            }
        }
        $html = $directoryBlockData->getLayout()->createBlock('core/html_select')
                ->setName($name)
                ->setId($id)
                ->setTitle(Mage::helper('directory')->__($title))
                ->setClass($class)
                ->setValue($defValue)
                ->setOptions($options)
                ->setExtraParams($extraParams)
                ->getHtml();

        Varien_Profiler::stop('TEST: ' . __METHOD__);
        return $html;
    }

}
