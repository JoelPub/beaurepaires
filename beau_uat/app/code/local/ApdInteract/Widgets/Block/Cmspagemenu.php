<?php

class ApdInteract_Widgets_Block_Cmspagemenu extends Enterprise_Cms_Block_Hierarchy_Menu {

    /**
     * Retrieve list begin tag
     * APD: Hardcoded to desired start tag for cms menu
     *
     * @return string
     */
    protected function _getListTagBegin($parentNodeId)
    {
        return '<ul class="no-bullet side-nav cms-menu-node-' . $parentNodeId. '">';
    }

    /**
     * Retrieve List end tag
     * APD: Hardcoded to desired end tag for cms menu
     *
     * @return string
     */
    protected function _getListTagEnd()
    {
        return '</ul>';        
    }

    /**
     * Retrieve List Item begin tag
     * APD: added "current" class to current li node
     * 
     * @param Enterprise_Cms_Model_Hierarchy_Node $node
     * @param bool $hasChilds Whether item contains nested list or not
     * @return string
     */
    protected function _getItemTagBegin($node, $hasChilds = false)
    {            
            $class = array();
            
            $template = '<' . self::TAG_LI;
            if ($hasChilds) {
                $class[] = 'parent';
            }
            if ($this->_isCurrentNode($node)) {
                $class[] = 'current';
            }
            
            if (count($class) > 0) {
                $template .= ' class="' . implode(' ', $class) . '"';
            }
            
            foreach ($this->_allowedListAttributes as $attribute) {
                $value = $this->getData('item_' . $attribute);
                if (!empty($value)) {
                    $template .= ' '.$attribute.'="'.$this->escapeHtml($value).'"';
                }
            }
            if ($this->getData('item_props')) {
                $template .= ' ' . $this->getData('item_props');
            }
            $template .= '>';

            $this->setData($templateKey, $template);


        return strtr($template, $this->_getNodeReplacePairs($node));
    }

    
    /**
     * Retrieve Node label with link
     * APD: refactored logic to _isCurrentNode function
     * 
     * @param Enterprise_Cms_Model_Hierarchy_Node $node
     * @return string
     */
    protected function _getNodeLabel($node)
    {
        if ($this->_isCurrentNode($node)) {
            return $this->_getSpan($node);
        }
        return $this->_getLink($node);
    }
    
    /**
     * APD: Is this current (selected) node?
     * 
     * @param Enterprise_Cms_Model_Hierarchy_Node $node
     * @return bool
     */
    protected function _isCurrentNode($node) {
        return ($this->_node && $this->_node->getId() == $node->getId());
    }
    
    /**
     * Recursive draw menu
     *
     * @param array $tree
     * @param int $parentNodeId
     * @return string
     */
    public function drawMenu(array $tree, $parentNodeId = 0)
    {
        if (!isset($tree[$parentNodeId])) {
            return '';
        }

//        $addStyles = ($parentNodeId == 0);
        $html = $this->_getListTagBegin($parentNodeId);

        foreach ($tree[$parentNodeId] as $nodeId => $node) {
            /* @var $node Enterprise_Cms_Model_Hierarchy_Node */
            $nested = $this->drawMenu($tree, $nodeId);
            $hasChilds = ($nested != '');
            $html .= $this->_getItemTagBegin($node, $hasChilds) . $this->_getNodeLabel($node);
            $html .= $nested;
            $html .= $this->_getItemTagEnd();

            $this->_totalMenuNodes++;
        }

        $html .= $this->_getListTagEnd();

        return $html;
    }
}

    


