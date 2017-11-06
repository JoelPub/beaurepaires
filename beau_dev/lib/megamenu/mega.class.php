<?php

/* ------------------------------------------------------------------------
  # $JA#PRODUCT_NAME$ - Version $JA#VERSION$ - Licence Owner $JA#OWNER$
  # ------------------------------------------------------------------------
  # Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
  # @license - Copyrighted Commercial Software
  # Author: J.O.O.M Solutions Co., Ltd
  # Websites: http://www.joomlart.com - http://www.joomlancers.com
  # This file may not be redistributed in whole or significant part.
  ------------------------------------------------------------------------- */

class JAMenuMega {

    var $children = null;
    var $items = null;
    var $open = null;
    var $general_config = array();

    protected $_activeLink = array();
    protected $_parentId = 0;

    public function __construct($attributes = array()) {

        $this->params = new stdclass();
        $this->params->megamenu = 1;
        $this->params->startlevel = 0;
        $this->params->endlevel = 5;
        $this->general_config = $this->getGeneralConfig();

        $this->activeLink();
    }

    protected function _getParent($id){

        if($id != 0){
            $item =  Mage::getModel('jmmegamenu/jmmegamenu')->load($id);
            if($item->getParent() < 1){
                $this->_parentId = $id;
            }else{
                $this->_getParent($item->getParent());
            }
        }

        return $this->_parentId;
    }

    public function activeLink(){

        $currentUrl = Mage::helper('core/url')->getCurrentUrl();
        $currentUri =   strtok(Mage::helper("jmmegamenu")->parse_url($currentUrl), '?');

        $collections = Mage::getModel('jmmegamenu/jmmegamenu')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter("link", array('eq' => $currentUri))
            ->getFirstItem();

        $this->_getParent($collections->getData('parent'));
        $collections->setData('parent',$this->_parentId);
        $this->_activeLink = $collections->getData();

    }

    public function getGeneralConfig() {
        $config = array();
        $config['shownumproduct'] = Mage::getStoreConfig("wavethemes_jmmegamenu/wavethemes_jmmegamenu/shownumproduct");
        return $config;
    }

    public function genMenu() {
        $pid = 0;
        if (!isset($this->children[$pid]))
            return; //generate nothing

        $this->beginMenu();

        $this->genMenuItems($pid, 0);

        $this->endMenu();
    }

    public function getList($collections) {
        $active = Mage::helper("jmmegamenu")->getActivemenu($collections);

        if ($active) {
            $this->open = $active->tree;
        }


        $children = array();

        foreach ($collections as $item) {
            $item->megaparam = new stdclass();
            $pt = $item->parent;

            $list = isset($children[$pt]) ? $children[$pt] : array();

            $item->megaparam->cols = $item->mega_cols ? $item->mega_cols : 1;
            $item->megaparam->group = $item->mega_group;
            $item->megaparam->class = $item->mega_class;
            $item->megaparam->width = $item->mega_width;
            $item->megaparam->subcontent = $item->mega_subcontent;
            $item->megaparam->colw = $item->mega_colw;
            if (preg_match_all('/([^\s]+)=([^\s]+)/', $item->mega_colxw, $colwmatches)) {
                for ($i = 0; $i < count($colwmatches[0]); $i++) {
                    $item->megaparam->$colwmatches[1][$i] = $colwmatches[2][$i];
                }
            }

            $blocks = $this->loadBlocks($item);
            if (is_array($blocks) && count($blocks) > 0) {

                $item->content = "";
                $total = count($blocks);

                $cols = min($item->megaparam->cols, $total);

                for ($col = 0; $col < $cols; $col++) {
                    $pos = ($col == 0 ) ? 'first' : (($col == $cols - 1) ? 'last' : '');
                    if ($cols > 1)
                        $item->content .= $this->beginSubMenuModules($item->menu_id, 1, $pos, $col, true);
                    $i = $col;
                    while ($i < $total) {
                        $block = $blocks[$i];
                        $i += $cols;
                        $item->content .= $block->toHtml();
                    }
                    if ($cols > 1)
                        $item->content .= $this->endSubMenuModules($item->menu_id, 1, true);
                }

                $item->cols = $cols;
                $item->content = trim($item->content);
                $this->items[$item->menu_id] = $item;
            }else if ($blocks) {
                $item->content = $blocks;
            }

            if ($item->megaparam->cols) {
                $item->cols = $item->megaparam->cols;
                //$item->col = array();
                for ($i = 0; $i < $item->cols; $i++) {
                    if (isset($item->megaparam->{"col$i"}) && $item->megaparam->{"col$i"})
                        $item->col[$i] = $item->megaparam->{"col$i"};
                }
            }
            $item->_idx = count($list);
            array_push($list, $item);
            $children[$pt] = $list;

            $this->items[$item->menu_id] = $item;
        }
        if (isset($children))
            $this->children = $children;

        return true;
    }

    /*
      $pid: parent id
      $level: menu level
      $pos: position of parent
     */

    function genMenuItems($pid, $level) {

        if (isset($this->children[$pid]) && $this->children[$pid]) {
            $cols = ($pid && $this->items[$pid]->cols) ? $this->items[$pid]->cols : 1;

            $total = count($this->children[$pid]);

            $tmp = isset($this->items[$pid]) ? $this->items[$pid] : new stdclass();



            // calculate number of menu items per column
            if ($cols > 1) {

                $fixitems = 0;
                if ($fixitems < $cols) {
                    $leftitem = $total - $fixitems;
                    $col = array();
                    $items = ceil($leftitem / ($cols - $fixitems));

                    for ($m = 0; $m < $cols && $leftitem > 0; $m++) {

                        if (!isset($tmp->col[$m]) || !$tmp->col[$m]) {

                            if ($leftitem > $items) {

                                $col[$m] = $items;
                                $leftitem -= $items;
                            } else {
                                $col[$m] = $leftitem;
                                $leftitem = 0;
                            }
                        }
                    }
                    $tmp->col = $col;
                    $cols = count($tmp->col);
                    $tmp->cols = $cols;
                }
            } else {
                $tmp->col = array($total);
            }

            //recalculate the colw for this column if the first child is group
            for ($col = 0, $j = 0; $col < $cols && $j < $total; $col++) {
                $i = 0;
                $colw = 0;
                while ($i < $tmp->col[$col] && $j < $total) {
                    $row = $this->children[$pid][$j];
                    if ($row->megaparam->group && $row->megaparam->width > $colw) {
                        $colw = $row->megaparam->width;
                    }
                    $j++;
                    $i++;
                }
                if ($colw)
                    $this->items[$pid]->megaparam->{'colw' . ($col + 1)} = $colw;
            }
            $this->beginMenuItems($pid, $level);

            for ($col = 0, $j = 0; $col < $cols && $j < $total; $col++) {

                $pos = ($col == 0 ) ? 'first' : (($col == $cols - 1) ? 'last' : '');
                //recalculate the colw for this column if the first child is group
                if ($this->children[$pid][$j]->megaparam->group && $this->children[$pid][$j]->megaparam->width)
                    $this->items[$pid]->megaparam->{'colw' . ($col + 1)} = $this->children[$pid][$j]->megaparam->width;

                $this->beginSubMenuItems($pid, $level, $pos, $col);
                $i = 0;
                while ($i < $tmp->col[$col] && $j < $total) {
                    //foreach ($this->children[$pid] as $row) {
                    $row = $this->children[$pid][$j];
                    $pos = ($i == 0 ) ? 'first' : (($i == count($this->children[$pid]) - 1) ? 'last' : '');

                    $this->beginMenuItem($row, $level, $pos);
                    $this->genMenuItem($row, $level, $pos);

                    // show menu with menu expanded - submenus visible

                    if (isset($row->megaparams->group) && $row->megaparams->group) {

                        $this->genMenuItems($row->menu_id, $level); //not increase level
                    } else if ($level < $this->params->endlevel) {

                        $this->genMenuItems($row->menu_id, $level + 1);
                    }

                    $this->endMenuItem($row, $level, $pos);
                    $j++;
                    $i++;
                }
                $this->endSubMenuItems($pid, $level);
            }
            $this->endMenuItems($pid, $level);
        }
    }

    public function loadBlocks($item) {
        $blocks = array();
        switch ($item->megaparam->subcontent) {
            case 3:
                $ids = $item->static_block;
                $ids = preg_split('/,/', $ids);
                foreach ($ids as $id) {
                    if ($id && $block = Mage::getModel('cms/block')->load($id)) {
                        $block = Mage::app()->getLayout()->createBlock('cms/block')->setBlockId($block->identifier);
                        $blocks[] = $block;
                    }
                }
                return $blocks;

            case 2:
                $block = new stdclass();
                $helper = Mage::helper('cms');
                $processor = $helper->getBlockTemplateProcessor();
                $block = $processor->filter($item->contentxml);
                return $block;
            default:
                return null;
        }
    }

    public function getActive() {
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();

        foreach ($this->getList() as $list) {

            if ($list->link == $currentUrl) {
                break;
                return $list;
            }
        }
    }

    function beginSubMenuModules($item, $level = 0, $pos, $i, $return = false) {
        $data = '';

        if ($level) {

            if ($item->megaparam->get('group')) {

            } else {
                $colw = $item->megaparam->{'colw' . ($i + 1)};
                if (!$colw)
                    $colw = $this->items[$pid]->megaparam->colw ? $this->items[$pid]->megaparam->colw : 200;
                $style = $colw ? " style=\"width: {$colw}px;\"" : "";
                #$data .= "<div class=\"megacol column".($i+1).($pos?" $pos":"")."\"$style>";
            }
        }
        if ($return)
            return $data;
        else
            echo $data;
    }

    function endSubMenuModules($item, $level = 0, $return = false) {
        $data = '';
        if ($level) {
            if ($item->megaparam->get('group')) {

            } else
                $data .= "";
        }
        if ($return)
            return $data;
        else
            echo $data;
    }

    function beginMenuItems($pid = 0, $level = 0, $return = false) {
        if ($level) {

            if (isset($this->items[$pid]) && $this->items[$pid]->megaparam->group) {

                $cols = $pid && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;
                $cols_cls = ($cols > 1) ? " cols$cols" : '';
                $data = "<div class=\"group-content$cols_cls\">";
            } else {
                $style = 1;
                $data = call_user_func_array(array($this, "beginMenuItems$style"), array($pid, $level, true));
            }
            if ($return)
                return $data;
            else
                echo $data;
        }
    }

    function endMenuItems($pid = 0, $level = 0, $return = false) {
        if ($level) {

            if (isset($this->items[$pid]) && $this->items[$pid]->megaparam->group) {

                $data = "</div>";
            } else {
                $style = 1;
                $data = call_user_func_array(array($this, "endMenuItems$style"), array($pid, $level, true));
            }
            if ($return)
                return $data;
            else
                echo $data;
        }
    }

    function beginSubMenuItems($pid = 0, $level = 0, $pos, $i, $return = false) {
        $level = (int) $level;
        $data = '';

        if (isset($this->items[$pid]) && $level) {
            $cols = $pid && isset($this->items[$pid]->megaparam->cols) && $this->items[$pid]->megaparam->cols ? $this->items[$pid]->megaparam->cols : 1;
            if ($this->items[$pid]->megaparam->group && $cols < 2) {

            } else {
                $colw = 0;
                if (isset($this->items[$pid]->megaparam->{'colw' . ($i + 1)}))
                    $colw = $this->items[$pid]->megaparam->{'colw' . ($i + 1)};
                if (!$colw)
                    $colw = $this->items[$pid]->megaparam->colw ? $this->items[$pid]->megaparam->colw : 200;
                $style = $colw ? " style=\"width: {$colw}px;\"" : "";
                #$data .= "<div class=\"megacol column".($i+1).($pos?" $pos":"")."\"$style>";
            }
        }
        if ($level > 0) {
            if (@$this->children[$pid])
                $data .= "<ul class=\"dropdown level$level\">";
        } else {
            if (@$this->children[$pid])
                $data .= "<ul class=\"level$level\">";
            $data .= $this->displayStoreLocation();
        }

        if ($return)
            return $data;
        else
            echo $data;
    }

    function endSubMenuItems($pid = 0, $level = 0, $return = false) {
        $data = '';
        if (@$this->children[$pid])
            $data .= $this->displayCustomerLinks();
        $data .= "</ul>";
        if (isset($this->items[$pid]) && $level) {
            $cols = $pid && isset($this->items[$pid]->megaparam->cols) && $this->items[$pid]->megaparam->cols ? $this->items[$pid]->megaparam->cols : 1;
            if ($this->items[$pid]->megaparam->group && $cols < 2) {

            } else
                $data .= "";
        }
        if ($return)
            return $data;
        else
            echo $data;
    }

    /**
     * Add store location
     * @return string
     */
    function displayStoreLocation(){


        $link = "<li class=\"mega m-search show-for-small-only\"><a class=\"mega\"><i class=\"fa fa-fw fa-search\"></i> <span class=\"menu-title\">Search</span></a></li>";
        $link .= "<li class=\"mega m-store-loc show-for-small-only\"><a class=\"mega\" href=\"/store-locator\"><i class=\"fa fa-fw fa-map-marker\"></i> <span class=\"menu-title\">Store Locator</span></a></li>";

        return $link;
    }

    //Add link for customer login/logout
    function displayCustomerLinks(){
        $storeId = Mage::app()->getStore()->getId();
        if($storeId == 1){

            $data = array();
            if (Mage::helper('customer')->isLoggedIn() ){

                $logoutUrl = Mage::helper('customer')->getLogoutUrl();
                $customerDashboard = Mage::getUrl('customer/account');

                $link = "<li class=\"mega user-account show-for-small-only\">";
                $link .= "<a class=\"mega\" href=\"{$customerDashboard}\"><i class=\"fa fa-fw fa-address-card-o\"></i> <span class=\"menu-title\">My Account Dashboard</span></a>";
                $link .= "</li>";

                $link .= "<li class=\"mega user-account show-for-small-only\">";
                $link .= "<a class=\"mega\" href=\"{$logoutUrl}\"><i class=\"fa fa-fw fa-sign-out\"></i> <span class=\"menu-title\">Log Out</span></a>";
                $link .= "</li>";

            }else{

                $loginUrl = Mage::helper('customer')->getLoginUrl();
                $registerUrl = Mage::getUrl('customer/account/create');

                // $link = "<li class=\"mega user-account show-for-small-only\">";
                // $link .= "<a class=\"mega\" href=\"{$registerUrl}\"><i class=\"fa fa-fw fa-user-plus\"></i> <span class=\"menu-title\">Register</span></a>";
                // $link .= "</li>";

                $link = "<li class=\"mega user-account m-login show-for-small-only\">";
                $link .= "<a class=\"mega\"><i class=\"fa fa-fw fa fa-user-o\"></i> <span class=\"menu-title\">Log In / Register</span></a>";
                $link .= "</li>";

            }

            return $link;
        }

    }

    /* Sub nav style - 1 - basic (default) */

    function beginMenuItems1($pid = 0, $level = 0, $return = false) {
        $cols = $pid && isset($this->items[$pid]->megaparam->cols) && $this->items[$pid]->megaparam->cols ? $this->items[$pid]->megaparam->cols : 1;


        if (isset($this->items[$pid])) {
            $width = $this->items[$pid]->megaparam->width;
            $colw = 0;
            if (!$width) {
                for ($col = 0; $col < $cols; $col++) {


                    $colw = @$this->items[$pid]->megaparam->{'colw' . ($col + 1)};

                    if (!$colw)
                        $colw = $this->items[$pid]->megaparam->colw ? $this->items[$pid]->megaparam->colw : 200;
                    $width += $colw;
                }
            }
            $style = $width ? " style=\"width: {$width}px;\"" : "";
            $right = isset($this->items[$pid]->megaparam->right) ? 'right' : '';
        }

        #$data = "<div class=\"childcontent cols$cols $right\">\n";
        #$data .= "<div class=\"childcontent-inner-wrap\" id=\"childcontent$pid\">\n"; 	//Add wrapper
        #$data .= "<div class=\"childcontent-inner clearfix\"$style>"; //Move width into inner
        $data = '';
        if ($return)
            return $data;
        else
            echo $data;
    }

    function endMenuItems1($pid = 0, $level = 0, $return = false) {
        $data = '';
        #$data = "</div>\n"; //Close of childcontent-inner
        #$data .= "</div></div>"; //Close wrapper and childcontent
        if ($return)
            return $data;
        else
            echo $data;
    }

    function beginMenuItem($mitem = null, $level = 0, $pos = '') {
        $active = $this->genClass($mitem, $level, $pos);
        if ($active)
            $active = " class=\"$active\"";
        echo "<li $active>";
        if ($mitem->megaparam->group)
            echo "<div class=\"group\">";

        /* $path = Mage::getBaseDir('media') .DS. $postData['image']['value'];
          $path= preg_replace("/\//", "\\", $path); */
        //add item icon
    }

    function endMenuItem($mitem = null, $level = 0, $pos = '') {
        if ($mitem->megaparam->group)
            echo "</div>";
        echo "</li>";
    }

    function beginMenu() {
        $effect = Mage::helper("jmmegamenu")->get("animation");
        // To turn sticky menu on:
        // <div class=\"contain-to-grid sticky top-bar-wrapper\">\n<nav class=\"top-bar\" data-options=\"sticky_on: large\" role=\"navigation\" data-topbar=\"\">\n


        // Dear BE poeple, this sucks, don't do it!  From FE
        $headerBlock = Mage::app()->getLayout()->getBlockSingleton('page/html_header');
        $logoUrl = $headerBlock->getLogoSrcSmall();
        $alt = $headerBlock->getLogoAlt();

        //   echo "<div class=\"top-bar-wrapper\">\n<nav class=\"top-bar\" role=\"navigation\" data-topbar=\"\">\n
        // <ul class=\"title-area\">
        // 		<!-- Remove the class \"menu-icon\" to get rid of menu icon. Take out \"Menu\" to just have icon alone -->
        //         <li class=\"toggle-topbar\"><a href=\"#\"><span><i class=\"fa fa-lg fa-navicon\"></i></span></a></li>
        //         <li class=\"name show-for-small-only\">
        //           <a href=\"/\"><img src=\"{$logoUrl}\" alt=\"$alt\"></a>
        //         </li>
        //          <li class=\"mobile-store-locator show-for-small-only\">
        //            <a href=\"/store-locator\" title=\"Store Locator\"><i class=\"fa fa-map-marker fa-lg fa-fw text-rigt\" aria-hidden=\"true\"><span class=\"show-for-sr\">Store Locator</span></i>Store Locator</a>
        //         </li>
        //       </ul>
        //                   <div class=\"location find-location mobile-nearest-store\">
        //                       <i class=\"fa fa-location-arrow\"></i> <span class=\"store\" data-default_store_id=\" ". Mage::getSingleton("core/session")->getdefaultStoreId() . "  \" data-store_id=\" ". Mage::getModel('core/cookie')->get('store_id') ." \"></span>
        //                       <a class=\"link\" href=\"/store-locator/\">Find the Nearest Store</a>
        //                   </div>
        //       <section class=\"top-bar-section\">
        // ";
        echo "<div class=\"top-bar-wrapper\">\n<nav class=\"top-bar\" role=\"navigation\" data-topbar=\"\">\n
          <section class=\"top-bar-section\">\n
            <div class=\"location find-location mobile-nearest-store\">
                <i class=\"fa fa-location-arrow\"></i> <span class=\"store\" data-default_store_id=\" ". Mage::getSingleton("core/session")->getdefaultStoreId() . "  \" data-store_id=\" ". Mage::getModel('core/cookie')->get('store_id') ." \"></span>
                <a class=\"link\" href=\"/store-locator/\">Find the Nearest Store</a>
            </div>
    ";
    }

    function endMenu() {
        echo "\n</section>\n</nav>\n</div>";
        //Create menu
    }

    function genClass($mitem, $level, $pos) {

        $activeLink = "";
        if(count($this->_activeLink)){
            if((isset($this->_activeLink['link']) && $this->_activeLink['link'] == $mitem->link) && $this->_activeLink['parent'] == 0){
                $activeLink = " active";
            }

            if($this->_activeLink['parent'] == $mitem->menu_id){
                $activeLink = " active";
            }
        }

        $cls = "mega {$activeLink}" . ($pos ? " $pos" : "");
        if (@$this->children[$mitem->menu_id] || (isset($mitem->content) && $mitem->content)) {
            if (isset($mitem->megaparams->group))
                $cls .= " group";
            else if ($level < $this->params->endlevel)
                $cls .= " has-dropdown";
        }

        if (isset($this->open))
            $active = in_array($mitem->menu_id, $this->open);
        else
            $active = false;
        if (!preg_match('/group/', $cls))
            $cls .= ($active ? " " : "");
        if ($mitem->megaparam->class)
            $cls .= " " . $mitem->megaparam->class;
        return $cls;
    }

    function genMenuItem($item, $level = 0, $pos = '', $ret = 0) {
        $data = '';
        $tmp = $item;
        $tmpname = ($this->params->megamenu && !$tmp->showtitle) ? '' : $tmp->title;
        // Print a link if it exists
        $active = $this->genClass($tmp, $level, $pos);
        if ($active)
            $active = " class=\"$active\"";
        $id = 'id="menu' . $tmp->menu_id . '"';

        $itembg = '';
        //Add icon to item
        $icon = '';

        if ($item->getData('menutype') == 0) {
            if ($item->getData('shownumproduct') == 1 || ($item->getData('shownumproduct') == 0 && $this->general_config['shownumproduct'] == 1)) {
                $storeId = Mage::app()->getStore()->getId();
                $category = Mage::getModel('catalog/category')->load($item->getData('catid'));
                $category->setIsAnchor(1);
                $collection = Mage::getResourceModel('catalog/product_collection');
                $collection->addCategoryFilter($category)
                    ->addAttributeToSelect('id')
                    ->setStoreId($storeId)
                    ->addStoreFilter($storeId)
                    ->addAttributeToFilter("status", Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    ->addAttributeToFilter("visibility", Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

                $tmpname .= '<span class="shownumproduct">(' . $collection->getSize() . ')</span>';
            }
        }


        if ($tmp->image != "") {
            $image = preg_replace("/jmmegamenu\//", "", $tmp->image);
            $txt = '<span class="menu-title" style="background-image: url(' . Mage::getBaseUrl('media') . "jmmegamenu/" . $image . ');">' . $tmpname . '</span>';
        } else {
            $txt = '<span class="menu-title">' . $tmpname . '</span>';
        }

        //Add page title to item
        if (isset($tmp->megaparams->desc) && $tmp->megaparams->desc) {
            $txt .= '<span class="menu-desc">' . JText::_($tmp->megaparams->desc) . '</span>';
        }
        //Add description to item
        if ($tmp->description != "") {
            $txt.= "<span class='mega-item-des' >";
            $txt.= ($tmp->description);
            $txt.= "</span>";
        }
        //replace base_url by real base url
        if ($tmp->menutype == 2 && (strpos($tmp->link, "base_url") >= 0)) {
            $tmp->link = str_replace('base_url', Mage::getBaseUrl(), $tmp->link);
        }

        $title = "title=\"$tmpname\"";
        $title = strip_tags($title);
        $tmp->link = ($tmp->menutype == 2) ? $tmp->link : Mage::getBaseUrl() . $tmp->url . (($tmp->menualias != " " && $tmp->menualias != null) ? "?alias=" . $tmp->menualias : "");

        if ($tmpname) {

            if ($tmp->link != null) {


                //BCC-178 11.4.2016
                if (strpos($tmp->link, '/onsale') !== false) {
                    $url = substr(Mage::getUrl(), 0, -1); //remove '/' from base url
                    $tmp->link = $url . $tmp->link;
                }else{
                    $tmp->link = Mage::helper("jmmegamenu")->parse_url($tmp->link);
                    $tmp->link = Mage::getUrl() . $tmp->link;
                }


                switch ($tmp->browserNav) {
                    default:
                    case 0:
                        // _top
                        $data = '<a href="' . $tmp->link . '" ' . $active . ' ' . $id . ' ' . $title . '>' . $txt . '</a>';
                        break;
                    case 1:
                        // _blank
                        $data = '<a href="' . $tmp->link . '" target="_blank" ' . $active . ' ' . $id . ' ' . $title . '>' . $txt . '</a>';
                        break;
                    case 2:
                        // window.open
                        $attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $this->getParam('window_open');

                        // hrm...this is a bit dickey

                        $data = '<a href="' . $tmp->link . '" onclick="window.open(this.href,\'targetWindow\',\'' . $attribs . '\');return false;" ' . $active . ' ' . $id . ' ' . $title . '>' . $txt . '</a>';
                        break;
                }
            } else {
                $data = '<a ' . $active . ' ' . $id . ' ' . $title . '>' . $txt . '</a>';
            }
        }

        //for megamenu
        if ($this->params->megamenu) {

            //For group
            if (($tmp->megaparam->group) && $data) {
                $data = "<div class=\"group-title\">$data</div>";
            }
            // die($item->menu_id."---".$item->content);
            if ($item->content) {

                if ($item->megaparam->group) {
                    $data .= "<div class=\"group-content\">{$item->content}</div>";
                } else {
                    $data .= $this->beginMenuItems($item->id, $level + 1, true);
                    $data .= $item->content;
                    $data .= $this->endMenuItems($item->id, $level + 1, true);
                }
            }
        }

        if ($ret)
            return $data;
        else
            echo $data;
    }

}
