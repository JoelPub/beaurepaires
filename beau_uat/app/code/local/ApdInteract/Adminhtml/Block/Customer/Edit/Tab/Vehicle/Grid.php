<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Adminhtml Vehicle Information  grid block
 */
class ApdInteract_Adminhtml_Block_Customer_Edit_Tab_Vehicle_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('vehicleGrid');
        $this->setDefaultSort('vehicle_id');
        $this->setDefaultDir('asc');

        $this->setUseAjax(true);

        $this->setEmptyText(Mage::helper('customer')->__('No Vehicles Found'));

    }

    /**
     * Filter Grid results by going to the  vehicleAction controller
     * @return string url
     */

    public function getGridUrl()
    {
        return $this->getUrl('*/*/vehicle', array('_current'=>true));
    }

    protected function _prepareCollection()
    {
        $customer = Mage::registry('current_customer');
        $collection = Mage::getModel('apdinteract_vehicle/vehicle')->getCollection()
            ->addFieldToFilter('customer_id',$customer->getId());

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /*
     * Vehicle Information Columns
     */

    protected function _prepareColumns()
    {
        $this->addColumn('vehicle_id', array(
            'header'    =>  Mage::helper('customer')->__('ID'),
            'align'     =>  'left',
            'index'     =>  'vehicle_id',
            'width'     =>  10
        ));

        $this->addColumn('make_tyres', array(
            'header'    =>  Mage::helper('customer')->__('Make'),
            'align'     =>  'center',
            'index'     =>  'make',
            'width'     =>  150
        ));
        $this->addColumn('manufacture_year', array(
            'header'    =>  Mage::helper('customer')->__('Year'),
            'align'     =>  'center',
            'index'     =>  'manufacture_year',
            'width'     =>  150
        ));

        $this->addColumn('model', array(
            'header'    =>  Mage::helper('customer')->__('Model'),
            'align'     =>  'center',
            'index'     =>  'model',
        ));

        $this->addColumn('series', array(
            'header'    =>  Mage::helper('customer')->__('Series'),
            'align'     =>  'center',
            'index'     =>  'series',
        ));

        $this->addColumn('registration', array(
            'header'    =>  Mage::helper('customer')->__('Registration'),
            'align'     =>  'center',
            'index'     =>  'registration'
        ));

        $this->addColumn('url', array(
            'header'    =>  Mage::helper('customer')->__('Url'),
            'align'     =>  'center',
            'index'     =>  'url'
        ));

        $this->addColumn('action', array(
            'header'    => Mage::helper('customer')->__('Action'),
            'index'     => 'vehicle_id',
            'renderer'  => 'apdinteract_adminhtml/customer_grid_renderer_multiaction',
            'filter'    => false,
            'sortable'  => false,
            'actions'   => array(
                array(
                    'caption'           => Mage::helper('customer')->__('Configure'),
                    'process'   => 'update_vehicle',
                    'url'       => '?update_vehicle='
                ),
                array(
                    'caption'   => Mage::helper('customer')->__('Delete'),
                    'process'   => 'delete_vehicle',
                    'url'       => '?delete_vehicle=',
                    'onclick'   => 'return confirm(\'Are you sure you want to delete this vehicle?\')'
                )
            )
        ));

        return parent::_prepareColumns();
    }

}
