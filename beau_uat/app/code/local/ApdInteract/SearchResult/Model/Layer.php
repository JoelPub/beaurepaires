<?php

class ApdInteract_SearchResult_Model_Layer extends Mage_Catalog_Model_Layer {

    public function getStateKey() {
        if ($this->_stateKey === null) {
            $this->_stateKey = 'STORE_' . Mage::app()->getStore()->getId() . '_SEARCHRESULTN_' . '_CUSTGROUP_' . Mage::getSingleton('customer/session')->getCustomerGroupId();
        }

        return $this->_stateKey;
    }

    public function getStateTags(array $additionalTags = array()) {
        $additionalTags = array_merge($additionalTags, array(
            'searchresultn'
        ));
        return $additionalTags;
    }

    public function getProductCollection() {
        if (isset($this->_productCollections['searchresultn'])) {
            $collection = $this->_productCollections['searchresultn'];
        } else {
            $collection = $this->_getCollection();
            $this->prepareProductCollection($collection);
            $this->_productCollections['searchresultn'] = $collection;
        }

        return $collection;
    }

    public function prepareProductCollection($collection) {

        $collection
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
        ;

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $this;
    }

    protected function _getCollection() {
        $post = Mage::app()->getRequest()->getParams();

        //$collection = Mage::getModel('catalog/product')->getCollection();

        $q = $_POST['type'];
        $s = $_POST['section'];
        $c = $_POST['category'];

        $series = '';
        $series_name = '';
        $make = '';
        $year = '';
        $model = '';
        $makemodel = '';

        if (isset($_POST['type'])) {


            Mage::getSingleton('core/session')->setTypeF($q);
            Mage::getSingleton('core/session')->setSectionF($s);
            Mage::getSingleton('core/session')->setCategoryF($c);
        }


        /*
          $collection = Mage::getResourceModel('catalog/product_collection')
          ->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')
          ->addAttributeToFilter('category_id', array('in' => $c))
          ->addAttributeToSelect('*');
          $collection->load();
         */


        $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('category_id', array('in' => Mage::getSingleton('core/session')->getCategoryF()));
        
        $q = Mage::getSingleton('core/session')->getTypeF();
        $s = Mage::getSingleton('core/session')->getSectionF();
        $c = Mage::getSingleton('core/session')->getCategoryF();
        $sType = "";
        if ($q == 'tyres' && !isset($post['reset-btn'])) {
            if ($s == 'vehicles') {
                if (!isset($_POST['saved_vehicles'])) {

                    if (isset($_POST['series-tyres']))
                        $series = $_POST['series-tyres'];
                    if (isset($_POST['tyre-series-name']))
                        $series_name = $_POST['tyre-series-name'];
                    if (isset($_POST['make-tyres']))
                        $make = $_POST['make-tyres'];
                    if (isset($_POST['year-tyres']))
                        $year = $_POST['year-tyres'];
                    if (isset($_POST['model-tyres']))
                        $model = $_POST['model-tyres'];
                    if (isset($_POST['make-model']))
                        $makemodel = $_POST['make-model'];

                    $sType = "vehicle_type";
                } else {
                   
                    $vehicles_data = explode(':', $_POST['saved_vehicles']);
                    $series = $vehicles_data[3];
                    $series_name = $vehicles_data[4];
                    $make = $vehicles_data[0];
                    $year = $vehicles_data[1];
                    $model = $vehicles_data[2];
                    $makemodel = $vehicles_data[4];

                    $sType = "saved_vehicles";
                }

                Mage::getSingleton('core/session')->setSelectorType($sType);

                $size = Mage::helper('searchtyre')->GetPatern($series);

                $size_ar = explode(',', $size);
                $allsizes = "";
                $sizes = Mage::Helper('apdinteract_searchresult')->extractWheelSizes($series);
                if(count($sizes)):
                    $allsizes = "Front: ".implode(", ",$sizes['fe'])."<br>Rear: ".implode(", ",$sizes['re']);
                endif;
                
                $patterns = Mage::helper('searchtyre')->GetPaternRaw($series);

                #echo '<pre>'; print_r($_POST); echo '</pre>';
                $products = array();
                $products_codes = array();
                $product_name_likes = array();
                $filters = array();
                for ($i = 0; $i <= $patterns->Total; $i++):
                    if (isset($patterns->Items[$i]->Name)) :
                        $products[$i] = $patterns->Items[$i]->Name;
                        $fet = $patterns->Items[$i]->TyreFitments[0]->FrontTyre->Code;
                        $ret = $patterns->Items[$i]->TyreFitments[0]->RearTyre->Code;
                        $fet1 = $patterns->Items[$i]->TyreFitments[0]->FrontTyre->Size->Description;
                        $ret1 = $patterns->Items[$i]->TyreFitments[0]->RearTyre->Size->Description;

                        //echo $patterns->Items[$i]->Name . '=' . $fet1 . '=' . $ret1 . '<br>';
                        $codearray = array();
                        $codearray['front'] = $fet;
                        $codearray['rear'] = $ret;
                        $products_codes[Mage::helper('apdinteract_catalog')->setToVariable($products[$i])] = $codearray;
                        $product_name_search = preg_replace('/\s+/', '%', trim($products[$i]));

                        $start = substr($product_name_search, 0, 1);
                        $end = substr($product_name_search, -2);
                        $middle = substr($product_name_search, 1, -2);

                        $product_name_search = $start . preg_replace('/[A-Za-z]/', '$0%', $middle) . $end;

                        $product_name_likes[] = array('attribute' => 'name', 'like' => $product_name_search);


                    endif;
                endfor;

                $filters[] = array('attribute' => 'searchfilter', 'like' => '%' . $fet1 . '%');
                $filters[] = array('attribute' => 'searchfilter', 'like' => '%' . $ret1 . '%');

                //echo '<pre>'; print_r($product_name_likes); echo '</pre>';
                //var_dump($products);


                if (isset($_POST['type'])) {
                    Mage::getSingleton('core/session')->setSizeWF($allsizes);
                    Mage::getSingleton('core/session')->setProductCodeTyres($products_codes);
                    Mage::getSingleton('core/session')->setSizeF($size_ar[0]);
                    Mage::getSingleton('core/session')->setSize1F($size_ar[1]);
                    Mage::getSingleton('core/session')->setSeriesF($series);
                    Mage::getSingleton('core/session')->setTMakeF($make);
                    Mage::getSingleton('core/session')->setTModelF($model);
                    Mage::getSingleton('core/session')->setTYearF($year);
                    Mage::getSingleton('core/session')->setTSeriesNameF($series_name);
                    Mage::getSingleton('core/session')->setTMakeModelF($makemodel);
                }

                $size = Mage::getSingleton('core/session')->getSizeF();
                $series = Mage::getSingleton('core/session')->getSeriesF();


                /* $collection->addAttributeToFilter('searchfilter', array(
                  'like' => "%$size%"
                  )); */
                if (count($product_name_likes) != 0) {
                    $collection->addAttributeToFilter($product_name_likes);
                } else {
                    // When there's no matching result, do not display ALL products - See BCC-327
                    $collection->addAttributeToFilter('name', array('like' => $products));
                }

                $collection->addAttributeToFilter($filters);

                Mage::getSingleton('core/session')->setTitleF("<strong>Tyre Size: </strong>" . $size . ' Front and Rear');
                Mage::getSingleton('core/session')->setQueryF($size);
            } elseif ($s == 'brand') {
                $brand = $_POST['brand'];
                if (isset($_POST['type'])) {
                    Mage::getSingleton('core/session')->setBrandF($brand);


                    Mage::getSingleton('core/session')->setSizeF('');
                    Mage::getSingleton('core/session')->setSize1F('');
                    Mage::getSingleton('core/session')->setSeriesF('');
                    Mage::getSingleton('core/session')->setTMakeF('');
                    Mage::getSingleton('core/session')->setTModelF('');
                    Mage::getSingleton('core/session')->setTYearF('');
                    Mage::getSingleton('core/session')->setTSeriesNameF('');
                    Mage::getSingleton('core/session')->setTMakeModelF('');
                }

                $brand = Mage::getSingleton('core/session')->getBrandF();


                $productModel = Mage::getModel('catalog/product');
                $attr = $productModel->getResource()->getAttribute("brand");
                if ($attr->usesSource()) {
                    $brand_id = $attr->getSource()->getOptionId($brand);
                }

                Mage::getSingleton('core/session')->setTitleF("<strong>Tyre Brand: </strong>" . $brand);
                Mage::getSingleton('core/session')->setQueryF($brand);
                $collection->addAttributeToFilter('brand', $brand_id);
            } elseif ($s == 'size') {
                $width = $_POST['width-tyres'];
                $profile = $_POST['profile-tyres'];
                $diameter = $_POST['diameter-tyres'];
                //$size     = Mage::helper('searchtyre')->GetTyrePattern($width, $profile, $diameter);
                $size = $width . '/' . $profile . 'R' . $diameter;

                if (isset($_POST['type'])) {
                    Mage::getSingleton('core/session')->setSeriesF('');
                    Mage::getSingleton('core/session')->setTMakeF('');
                    Mage::getSingleton('core/session')->setTModelF('');
                    Mage::getSingleton('core/session')->setTYearF('');
                    Mage::getSingleton('core/session')->setTSeriesNameF('');
                    Mage::getSingleton('core/session')->setWidthF($width);
                    Mage::getSingleton('core/session')->setProfileF($profile);
                    Mage::getSingleton('core/session')->setDiameterF($diameter);
                    Mage::getSingleton('core/session')->setSizeF($size);
                    Mage::getSingleton('core/session')->setTMakeModelF('');
                }

                $width = Mage::getSingleton('core/session')->getWidthF();
                $profile = Mage::getSingleton('core/session')->getProfileF();
                $diameter = Mage::getSingleton('core/session')->getDiameterF();
                //$size     = Mage::getSingleton('core/session')->getSizeF();

                Mage::getSingleton('core/session')->setTitleF("<strong>Tyre Size: </strong>" . $size . ' Front and Rear');
                Mage::getSingleton('core/session')->setQueryF($size);

                $collection->addAttributeToFilter('searchfilter', array(
                    'like' => "%$size%"
                ));
            }
        } else {     //wheels search      
            if ($s == 'vehicles' && !isset($post['reset-btn'])) {
                $wheels = $_POST['series-tyres'];

                if (!isset($_POST['saved_vehicles'])) {

                    if (isset($_POST['series-tyres']))
                        $series = $_POST['series-tyres'];
                    if (isset($_POST['tyre-series-name']))
                        $series_name = $_POST['tyre-series-name'];
                    if (isset($_POST['make-tyres']))
                        $make = $_POST['make-tyres'];
                    if (isset($_POST['year-tyres']))
                        $year = $_POST['year-tyres'];
                    if (isset($_POST['model-tyres']))
                        $model = $_POST['model-tyres'];
                    if (isset($_POST['make-model']))
                        $makemodel = $_POST['make-model'];

                    $sType = "vehicle_type";
                } else {
                    $vehicles_data = explode(':', $_POST['saved_vehicles']);
                    $series = $vehicles_data[3];
                    $series_name = $vehicles_data[4];
                    $make = $vehicles_data[0];
                    $year = $vehicles_data[1];
                    $model = $vehicles_data[2];
                    $makemodel = $vehicles_data[4];

                    $sType = "saved_vehicles";
                }
                
                
                
                 Mage::getSingleton('core/session')->setSelectorType($sType);
                
                $wheels_data = Mage::helper('searchtyre')->getVehicleWheels($wheels);



                //$size = 'Front: ' . $wheels_data->Items[0]->WheelFitments[0]->FrontWheel->Size->Description . ', ' . 'Rear: ' . $wheels_data->Items[0]->WheelFitments[0]->RearWheel->Size->Description;
                $sizes = Mage::Helper('apdinteract_searchresult')->extractWheelSizes($series);
                if(count($sizes)):
                    $size = "Front: ".implode(", ",$sizes['fe'])."<br>Rear: ".implode(", ",$sizes['re']);
                endif;


                if (isset($_POST['type']) || isset($_POST['saved_vehicles'])) {

                    Mage::getSingleton('core/session')->setSizeWF($size);
                    Mage::getSingleton('core/session')->setSeriesF($series);
                    Mage::getSingleton('core/session')->setTMakeF($make);
                    Mage::getSingleton('core/session')->setTModelF($model);
                    Mage::getSingleton('core/session')->setTYearF($year);
                    Mage::getSingleton('core/session')->setTSeriesNameF($series_name);
                    Mage::getSingleton('core/session')->setWMakeNameF($make_name);
                    Mage::getSingleton('core/session')->setTMakeModelF($makemodel);

                    Mage::getSingleton('core/session')->setWTitleF("<strong>Wheel Size: </strong>" . $size . ' Front and Rear');
                    Mage::getSingleton('core/session')->setQueryF($size);
                }


                $wheels = Mage::getSingleton('core/session')->getWheelF();
                $series = Mage::getSingleton('core/session')->getSeriesF();
                //$size   = Mage::helper('searchtyre')->GetPatern($wheels);
                //$collection->addAttributeToFilter('rim_width',$size);


                $wheels_result = Mage::helper('searchtyre')->getVehicleWheels($series);
                $products = array();
                $products_codesx = array();
                $product_name_likes = array();
                for ($i = 0; $i <= $wheels_result->Total; $i++) {
                    $products[$i] = $wheels_result->Items[$i]->Brand->Name . ' ' . $wheels_result->Items[$i]->Name;

                    $fet = $wheels_result->Items[$i]->WheelFitments[0]->FrontWheel->Code;
                    $ret = $wheels_result->Items[$i]->WheelFitments[0]->RearWheel->Code;
                    $codearray = array();
                    $codearray['front'] = $fet;
                    $codearray['rear'] = $ret;
                    $products_codesx[Mage::helper('apdinteract_catalog')->setToVariable($products[$i])] = $codearray;
                    $product_name_search = preg_replace('/\s+/', '%', trim($products[$i]));

                    $start = substr($product_name_search, 0, 1);
                    $end = substr($product_name_search, -2);
                    $middle = substr($product_name_search, 1, -2);

                    $product_name_search = $start . preg_replace('/[A-Za-z]/', '$0%', $middle) . $end;
                    
                    if(trim($wheels_result->Items[$i]->Name) !='')
                    $product_name_likes[] = array('attribute' => 'name', 'like' => '%'.$wheels_result->Items[$i]->Name.'%');
                }
                Mage::getSingleton('core/session')->setProductCodeWheels($products_codesx);
                

                if (count($product_name_likes) != 0) {
                    $collection->addAttributeToFilter($product_name_likes);
                } else {
                    // When there's no matching result, do not display ALL products - See BCC-327
                    $collection->addAttributeToFilter('name', array('like' => $products));
                }
            } /*else {
                if (isset($_POST['type']) && !isset($post['reset-btn'])) {

                    $brand = $_POST['brand-wheels'];


                    Mage::getSingleton('core/session')->setWBrandF($brand);

                    Mage::getSingleton('core/session')->setSizeF('');
                    Mage::getSingleton('core/session')->setWSeriesF('');
                    Mage::getSingleton('core/session')->setTMakeF('');
                    Mage::getSingleton('core/session')->setTModelF('');
                    Mage::getSingleton('core/session')->setTYearF('');
                    Mage::getSingleton('core/session')->setTSeriesNameF('');
                    Mage::getSingleton('core/session')->setTMakeModelF('');


                    Mage::getSingleton('core/session')->setWTitleF("<strong>Wheel Size: </strong>" . $size . ' Front and Rear');
                    Mage::getSingleton('core/session')->setQueryF($size);
                }




                $brand = Mage::getSingleton('core/session')->getWBrandF();


                $productModel = Mage::getModel('catalog/product');
                $attr = $productModel->getResource()->getAttribute("brand");
                if ($attr->usesSource()) {
                    $brand_id = $attr->getSource()->getOptionId($brand);
                }

                Mage::getSingleton('core/session')->setTitleF("<strong>Tyre Brand: </strong>" . $brand);
                Mage::getSingleton('core/session')->setQueryF($brand);
                $collection->addAttributeToFilter('brand', $brand_id);
            }*/
        }

        #var_dump($products);
        //echo $collection->getSelect()->__toString();


        return $collection;
    }

}
