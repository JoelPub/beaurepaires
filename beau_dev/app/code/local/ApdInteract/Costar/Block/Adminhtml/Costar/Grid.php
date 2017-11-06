<?php

class ApdInteract_Costar_Block_Adminhtml_Costar_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('apd_log_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**    
    * Will filter according to type param set on the url
    *   
    */
    protected function _prepareCollection() {
        
        $collection = Mage::getModel('apdinteract_costar/log')->getCollection();
         if ($type = Mage::getSingleton('core/session')->getApdFilterType())
                 $collection = $collection->addFieldToFilter('type',array('like' => '%'.$type.'%'));
                 
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        
       
        $this->addColumn('id', array(
            'header' => Mage::helper('costar/costar')->__('ID'),
            'align' => 'center',
            'width' => '10px',
            'index' => 'id',
        ));

        $this->addColumn('type', array(
            'header' => Mage::helper('costar/costar')->__('Type'),
            'align' => 'left',
            'align' => 'center',
            'width' => '50px',
            'sortable' => true,
            'index' => 'type',
        ));


        $this->addColumn('start_date', array(
            'header' => Mage::helper('costar/costar')->__('Start'),
            'width' => '150px',
            'align' => 'center',
            'filter' => false,
            'sortable' => true,
            'index' => 'start_date'
        ));


        $this->addColumn('end_date', array(
            'header' => Mage::helper('costar/costar')->__('End'),
            'align' => 'center',
            'width' => '150px',
            'index' => 'content',
            'filter' => false,
            'sortable' => true,
            'index' => 'end_date'
        ));

        $this->addColumn('duration', array(
            'header' => Mage::helper('costar/costar')->__('Duration'),
            'width' => '50px',
            'index' => 'content',
             'filter' => false,
            'sortable' => true,
            'index' => 'duration'
        ));

        $this->addColumn('updated', array(
            'header' => Mage::helper('costar/costar')->__('Updated'),
            'width' => '50px',
            'index' => 'content',
            'align' => 'right',
             'filter' => false,
            'sortable' => true,
            'index' => 'updated'
        ));

        $this->addColumn('skipped', array(
            'header' => Mage::helper('costar/costar')->__('Skipped'),
            'width' => '50px',
            'index' => 'content',
             'filter' => false,
            'align' => 'right',
            'sortable' => true,
            'index' => 'skipped'
        ));

        $this->addColumn('error', array(
            'header' => Mage::helper('costar/costar')->__('Error'),
            'width' => '50px',
            'align' => 'right',
             'filter' => false,
            'sortable' => true,
            'index' => 'error',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('costar/costar')->__('Status'),
            'width' => '150px',
            'index' => 'content',
            'sortable' => true,
            'index' => 'status'
        ));

        $this->addColumn('action_log', array(
            'header' => $this->helper('costar/costar')->__('Log File'),
            'width' => 15,
            'sortable' => false,
            'align' => 'center',
            'filter' => false,
            'index' => 'log_file'            
        ));

        $this->addColumn('action_file', array(
            'header' => $this->helper('costar/costar')->__('File'),
            'width' => 15,
            'sortable' => false,
            'align' => 'center',
            'filter' => false,
            'type'      => 'action',
            'getter'     => 'getId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('costar/costar')->__('Download'),
                    'url'     => array(
                        'base'=>'*/*/download',
                        'params'=>array('store'=>$this->getRequest()->getParam('id'))
                    ),
                    'field'   => 'id'
                )
            )
        ));

        return parent::_prepareColumns();
    }

}
