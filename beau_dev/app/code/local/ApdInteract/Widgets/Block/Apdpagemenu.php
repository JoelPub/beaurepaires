<?php

class ApdInteract_Widgets_Block_Apdpagemenu extends Mage_Core_Block_Template {

    public $maxLevels = 0; // Siblings only, change to 1 to include siblings and children
    private $_menuGroupId;
    private $_menuHtml;
    private $_level;
    
    private function _getMenuItemHtml($item) { 
        
        // Make all the links relative paths, remove index.php, all that sort of rubbish
        $dirtyUrl = $item->getLink();
        $itemUrl = Mage::helper("jmmegamenu")->fixLink($dirtyUrl);
                
        if ($dirtyUrl != $itemUrl) {
            $item->setLink($itemUrl)->save();            
        }
        
        $currentUrl = $this->_getCurrentUrlKey();
        if ($itemUrl == $currentUrl) {
            $html = '<li class="current"><strong>' . $item->getTitle() . '</strong></li>';
        } else {
            $html = '<li><a href="' . Mage::getUrl($itemUrl) . '">' . $item->getTitle() . '</a></li>';
        }
        
        // $html .= '<pre>' . print_r($item, true) . '</pre>';

        return $html;
    }

    private function _getCurrentUrlKey() {
        return Mage::getSingleton('cms/page')->getIdentifier();
    }

    public function getPagemenu() {

        if (!Mage::helper('jmmegamenu')->get('show')) {
            return '<!-- Jmmegamenu module disabled in Admin section -->';
        }

        $url = $this->_getCurrentUrlKey();

        $result = $this->_getFilteredMenuCollection('link', $url)->getLastItem();
        $id = $result->getId();

        if ($id == null) {
            return '<!-- This page url key: ' . $url . ' is not defined in the Jmmegamenu, so no page menu is displayed -->';
        }

        // Get current item children
        // $items = array($id=>$result);
        // Get current item and siblings
        $items = $this->_getSiblings($result);

        $this->_level = 0;
        $this->_menuHtml = '<ul class="no-bullet side-nav level-' . $this->_level . '">';
        $this->_getAllMenuItemsChildItems($items);
        $this->_menuHtml .= '</ul>';

        return $this->_menuHtml;
    }

    private function _getSiblings($item) {
        return $this->_getMenuItemChildren($item->getParent());
    }

    private function _getAllMenuItemsChildItems($items_array) {
        foreach ($items_array as $id => $menuitem) {

            $this->_menuHtml .= $this->_getMenuItemHtml($menuitem);

            $children = $this->_getMenuItemChildren($id);
            if (count($children) > 0 && $this->_level < $this->maxLevels) {
                $this->_level++;
                $this->_menuHtml .= '<ul class="no-bullet side-nav level-' . $this->_level . '">';
                $this->_getAllMenuItemsChildItems($children);
                $this->_menuHtml .= '</ul>';
                $this->_level--;
            }
        }
    }

    private function _getMenuItemChildren($menu_item_id) {
        $children = $this->_getFilteredMenuCollection('parent', $menu_item_id);
        return $children->getItems();
    }

    private function _getFilteredMenuCollection($field, $eq_value) {

        $collection = Mage::getModel('jmmegamenu/jmmegamenu')
                ->getCollection()
                ->setOrder("parent", "ASC")
                ->setOrder("ordering", "ASC")
                ->addFilter("status", 1, "eq")
                ->addFilter("menugroup", $this->_getMenuGroupId());        


        if ($field == 'link') {
            // Add OR condition:
            $collection->addFieldToFilter('link', array(
                        array('eq' => $eq_value),
                        array('eq' => $eq_value . '/'),                    
                        array('eq' => '/' . $eq_value),
                        array('eq' => '/' . $eq_value . '/')                    
                )
            );
        }
        else {
            $collection ->addFilter($field, $eq_value, "eq");
        }
        
        // $sql = (string)$collection->getSelect();
        
        return $collection;
    }

    private function _getMenuGroupId() {
        if (!isset($this->_menuGroupId)) {

            $storeid = Mage::app()->getStore()->getStoreId();
            $resource = Mage::getSingleton('core/resource');
            $read = $resource->getConnection('core_read');

            $menutable = $resource->getTableName('jmmegamenu_store_menugroup');
            $query = 'SELECT menugroupid '
                    . ' FROM ' . $menutable
                    . ' WHERE store_id = ' . (int) $storeid
                    . ' ORDER BY id';
            $rows = $read->fetchRow($query);

            if (!$rows["menugroupid"]) {
                $rows["menugroupid"] = 0;
            }

            $this->_menuGroupId = $rows["menugroupid"];
        }

        return $this->_menuGroupId;
    }

}
