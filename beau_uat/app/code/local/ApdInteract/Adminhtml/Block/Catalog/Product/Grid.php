<?php

/**
 * Class ApdInteract_Adminhtml_Block_Catalog_Product_Grid
 */
class ApdInteract_Adminhtml_Block_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{

    /**
     * Override _prepareCollection
     */
    protected function _prepareCollection()
    {

        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('category_ids')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }
        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );

            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'catalog_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );


        }
        else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }

        $filter = $this->getParam($this->getVarNameFilter(), null);
        $data = $this->helper('adminhtml')->prepareFilterString($filter);
        if(isset($data['category_id'])){
            $collection->joinField('category_id',
                'catalog/category_product',
                'category_id',
                'product_id=entity_id',
                null,
                'left')
                ->addAttributeToFilter('category_id', array('in' => $data['category_id']));
        }else{
            $collection->joinField('category_id',
                'catalog/category_product',
                'category_id',
                'product_id=entity_id',
                null,
                'left');
        }

        $collection->joinAttribute(
            'brand',
            'catalog_product/brand',
            'entity_id',
            null,
            'left',
            $store->getId()
        );

        $collection->joinAttribute(
            'overlay',
            'catalog_product/overlay',
            'entity_id',
            null,
            'left',
            $store->getId()
        );

        $collection->getSelect()
            ->group('e.entity_id');


        $this->setCollection($collection);

        Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();

        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

    /**
     * Override _prepareColumns
     */
    protected function _prepareColumns()
    {

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('catalog')->__('Websites'),
                    'width' => '100px',
                    //'sortable'  => false,
                    'index'     => 'websites',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
                ));
        }

        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('catalog')->__('SKU'),
                'width' => '80px',
                'index' => 'sku',
            ));

        $this->addColumn('name',
            array(
                'header'=> Mage::helper('catalog')->__('Name'),
                'index' => 'name',
            ));

        $store = $this->_getStore();
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('catalog')->__('Price'),
                'type'  => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'price',
            ));

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Qty'),
                    'width' => '50px',
                    'type'  => 'number',
                    'index' => 'qty',
                ));
        }

        $this->addColumn('type',
            array(
                'header'=> Mage::helper('catalog')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
            ));

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name',
                array(
                    'header'=> Mage::helper('catalog')->__('Name in %s', $store->getName()),
                    'index' => 'custom_name',
                ));
        }

        $this->addColumn('category_id',
            array(
                'header'=> Mage::helper('catalog')->__('Category'),
                'width' => '70px',
                'index' => 'category_id',
                'type'  => 'options',
                'options' => $this->_getCategoriesOptionArray(),
            ));

        $this->addColumn('brand',
            array(
                'header'=> Mage::helper('catalog')->__('Brand'),
                'width' => '70px',
                'index' => 'brand',
                'type'  => 'options',
                'options' => $this->_getAttributeOptionArray('brand'),
            ));

        $this->addColumn('overlay',
            array(
                'header'=> Mage::helper('catalog')->__('Catalogue'),
                'width' => '70px',
                'index' => 'overlay',
                'type'  => 'options',
                'options' => $this->_getAttributeOptionArray('overlay'),
            ));

        $this->addColumn('status',
            array(
                'header'=> Mage::helper('catalog')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
            ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'url'     => array(
                            'base'=>'*/*/edit',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
            ));

        if (Mage::helper('catalog')->isModuleEnabled('Mage_Rss')) {
            $this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));
        }

        parent::_prepareColumns();

        $this->removeColumn('entity_id');
        $this->removeColumn('set_name');
        $this->removeColumn('visibility');
    }

    /**
     * Retrieve all brand options
     * @param $attributeCode
     * @return array
     */
    protected function _getAttributeOptionArray($attributeCode){

        $optionArray = array();
        $attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $attributeCode);
        $options = Mage::getModel('eav/entity_attribute_source_table')
            ->setAttribute($attribute)
            ->getAllOptions(false);

        foreach ($options as $option) {
            $optionArray[$option['value']] = $option['label'];
        }

        return $optionArray;
    }

    /**
     * Categories
     * @return array
     */
    protected function _getCategoriesOptionArray(){

        $exclude_category = array(1,2);
        $collection = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('name');
        $options = array();
        foreach ($collection as $item){
            if($item->getId() != '' && !in_array($item->getId(),$exclude_category)){
                $options[$item->getId()] = $item->getName();
            }
        }

        return $options;
    }

}
