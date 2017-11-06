<?php
class ApdInteract_Exportpanel_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('content');
        $this->setTitle(Mage::helper('exportpanel')->__('Export Panel'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('customer_section', array(
            'label'     => Mage::helper('exportpanel')->__('Customer And Vehicles'),
            'title'     => Mage::helper('exportpanel')->__('Customer And Vehicles'),
            'content'   => $this->getLayout()->createBlock('exportpanel/adminhtml_exportpanel')->setTemplate('exportpanel/customer.phtml')->toHtml(),
        ));

        $this->addTab('sales_section', array(
            'label'     => Mage::helper('exportpanel')->__('Sales Order'),
            'title'     => Mage::helper('exportpanel')->__('Sales Order'),
            'content'   => $this->getLayout()->createBlock('exportpanel/adminhtml_exportpanel')->setTemplate('exportpanel/salesorder.phtml')->toHtml(),
        ));

        $this->addTab('products_section', array(
            'label'     => Mage::helper('exportpanel')->__('Products'),
            'title'     => Mage::helper('exportpanel')->__('Products'),
            'content'   => $this->getLayout()->createBlock('exportpanel/adminhtml_exportpanel')->setTemplate('exportpanel/products.phtml')->toHtml(),
        ));

        $this->addTab('products_inventory_section', array(
            'label'     => Mage::helper('exportpanel')->__('Product Inventory'),
            'title'     => Mage::helper('exportpanel')->__('Product Inventory'),
            'content'   => $this->getLayout()->createBlock('exportpanel/adminhtml_exportpanel')->setTemplate('exportpanel/product_inventory.phtml')->toHtml(),
        ));

        $this->addTab('products_review_section', array(
            'label'     => Mage::helper('exportpanel')->__('Product Review'),
            'title'     => Mage::helper('exportpanel')->__('Product Review'),
            'content'   => $this->getLayout()->createBlock('exportpanel/adminhtml_exportpanel')->setTemplate('exportpanel/product_review.phtml')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}