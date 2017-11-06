<?php

class ApdInteract_Breadcrumbs_Block_Breadcrumbs extends Mage_Page_Block_Html_Breadcrumbs {

    protected function _toHtml() {
        if (is_array($this->_crumbs)) {
            reset($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['first'] = true;
            end($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['last'] = true;
        }

        $menu_id = $this->ifexistinmegamenu($_SERVER["REQUEST_URI"]);
        if ($menu_id != 0) {
            $parenxt = $this->megamenu_parent($menu_id);
            $parray = array();
            $parray[] = $menu_id;
            while ($parenxt != 0) {
                $parray[] = $parenxt;
                $parenxt = $this->megamenu_parent($parenxt);
            }
            $main_menu = array_reverse($parray);
        }

        $this->assign('crumbs', $this->_crumbs);
        $this->assign('menu_id', $menu_id);
        if(isset($main_menu))
        $this->assign('main_menu', $main_menu);

        return parent::_toHtml();
    }

    public function ifexistinmegamenu($url) {
        // $url_le = strlen($url);
        $url = filter_var($url, FILTER_SANITIZE_SPECIAL_CHARS);
        $url = Mage::helper("jmmegamenu")->fixLink($url);
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = "SELECT * FROM jmmegamenu WHERE link='$url' OR link='/$url' OR link='$url/' order by menu_id DESC LIMIT 1";
        
//        print_r($query); // test line
        
        $results = $readConnection->fetchAll($query);
        $return = 0;
        foreach ($results as $val) {
            return $val['menu_id'];
        }
    }

    public function megamenu_details($id) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = "SELECT * FROM jmmegamenu WHERE menu_id='$id' LIMIT 1";
        $resultsx = $readConnection->fetchAll($query);
        foreach ($resultsx as $results) {
            return $results;
        }
    }

    public function megamenu_parent($id) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = "SELECT parent FROM jmmegamenu WHERE menu_id='$id' LIMIT 1";
        $parent = $readConnection->fetchOne($query);
        return $parent;
    }

}
