<?php 
class Fsite_NewsletterGroup_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function addNewsletterGroups($newsletter_group_names_array)
    {
        foreach ($newsletter_group_names_array as $group_name)
        {
            $this->addNewsletterGroup($group_name);
        }                
    }
    
    public function addNewsletterGroup($group_name)
    {
        // If group exists in Magento already, load it.
        $group = $this->loadGroupByName($group_name);
            
        if ($group->getId())
        {
            return $group;
        }
        
        $group = Mage::getModel( 'newslettergroup/group' );
        $group->setGroupName($group_name)
                ->setVisibleInFrontend(1)
                ->setParentGroupId(0);

        $group->save();
        
        return $group;
    }
    
    public function loadGroupByName($group_name)
    {
        $collection = $this->_getGroupCollection()
            ->addFieldToFilter( 'group_name', array( "eq" => $group_name ) )
            ->setPageSize(1)
            ->load();
                
        return $collection->getFirstItem();
    }
    
    /**
     * @assert () == true
     * @return NewsletterGroup Collection
     */
    public function getGroups()
    {
        $collection = $this->_getGroupCollection()
            ->addFieldToFilter( 'visible_in_frontend', array( "eq" => 1 ) )
            ->load();
                
        return $collection->getItems();
    }
    
    public function deleteGroupsNotInArray($newsletter_group_names_array)
    {
        $collection = $this->_getGroupCollection()
            ->addFieldToFilter( 'group_name', array( "nin" => $newsletter_group_names_array ) )
            ->load();
        
        foreach ($collection as $group) 
        {
            $group->delete();
        }
    }
    
    private function _getGroupCollection() {
        return Mage::getResourceModel( 'newslettergroup/group_collection' );
    }
    
    public function syncNewsletterGroups($newsletter_group_names_array)
    {
        $this->deleteGroupsNotInArray($newsletter_group_names_array);
        $this->addNewsletterGroups($newsletter_group_names_array);        
    }
    
    public function syncSilverpopNewsletterGroups($prefix = null)
    {        
        if (!$prefix) 
        {
            $prefix = $this->_getNewslettersPrefix();
        }
        
        $newsletter_group_names_array = Mage::helper('silverpopapi')->getListNamesStartingWith($prefix);
        if (is_array($newsletter_group_names_array))
        {
            $this->syncNewsletterGroups($newsletter_group_names_array);
        }        
    }
    
    private function _getNewslettersPrefix()
    {
        $list_prefix = Mage::getStoreConfig('silverpop_api/settings/list_prefix');
        if (empty($list_prefix)) {
            $list_prefix = 'Beaurepaires - Marketing -';
        }
        return $list_prefix;       
    }
    
    public function hidePrefixFromNewsletterName($name) 
    {
        return str_replace($this->_getNewslettersPrefix(), '', $name);
    }
    
    public function getHiddenSubscribeAllFormInputs()
    {
        $html = "";
        $groups = $this->getGroups();
        foreach ($groups as $group) 
        {
            $group_id = $group->getId();
            $html .= '<input value="' . $group_id . '" type="hidden" name="new_is_subscribed[]" />';        
        }
        return $html;
    }
}