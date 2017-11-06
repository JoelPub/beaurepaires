<?php
class IWD_StoreLocator_Block_Adminhtml_List_Edit_Tab_Costar extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface{
	
		
	
    protected function _prepareForm(){
    	
        /* @var $model IWD_StoreLocator_Model_Stores */
        $model = Mage::registry('storelocator_store');

        
        $isElementDisabled = false;

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('storelocator')->__('Costar Integration')));

        
        $fieldset->addField('p_costar_live_id', 'text', array(
            'name' => 'p_costar_live_id',
            'label' => Mage::helper('storelocator')->__('Live ID'),
            'title' => Mage::helper('storelocator')->__('Costar Live ID'),
            'required' => false,
            'disabled' => $isElementDisabled
        ));
        
        $fieldset->addField('costar_store_code', 'text', array(
            'name' => 'costar_store_code',
            'label' => Mage::helper('storelocator')->__('Branch Code'),
            'title' => Mage::helper('storelocator')->__('Costar Branch Code'),
            'required' => false,
            'disabled' => $isElementDisabled
        ));
        
        $fieldset->addField('p_branch_password', 'text', array(
            'name' => 'p_branch_password',
            'label' => Mage::helper('storelocator')->__('Branch Password'),
            'title' => Mage::helper('storelocator')->__('Costar Branch Password'),
            'required' => false,
            'disabled' => $isElementDisabled
        ));
        
       $fieldset->addField('exclude_from_cart', 'select', array(
            'name' => 'exclude_from_cart',
            'label' => Mage::helper('storelocator')->__('Exclude from Shopping Cart'),
            'title' => Mage::helper('storelocator')->__('Exclude from Shopping Cart'),
            'required' => false,
            'disabled' => $isElementDisabled,
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
                      
                
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('storelocator')->__('Costar Integration');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('storelocator')->__('Costar Integration');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }        

}
