<?php

class ApdInteract_Vir_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    protected function _prepareCollection() {
        /**
         * Tell Magento which collection to use to display in the grid.
         */
        $collection = Mage::getResourceModel('apdinteract_vir/order_collection')->setOrder('parent_id', 'DESC');

        //get all the stores assigned to this role
        $adminId = $this->getAdminId();
        //$adminId = 8; //for testing
        if ($adminId > 1 || $this->getAdminRole() == 33) {

            $storelist = $this->getStoreList($this->getAdminUser()->getUserId());
            if ($storelist == '')
                $storelist = 0;
            $mappingTableName = Mage::getSingleton('core/resource')->getTableName('apdinteract_vir/orderstoremapping');
            $storeTableName = "iwd_storelocator_users";
            $vir_type = 0;
            $collection->getSelect()
                    ->join(array('map' => $mappingTableName), 'main_table.parent_id = map.vir_id', array('map.store_id as mapstoreid'))
                    ->join(array('s' => $storeTableName), 'map.store_id = s.store_id', array('s.store_id as sstoreid'))
                    ->group('parent_id')
                    ->where("map.vir_type = $vir_type AND s.store_id IN ($storelist)");

            $ids = implode(",", $collection->getAllIds());
            if ($ids == '')
                $ids = 0;
            $collection = Mage::getResourceModel('apdinteract_vir/order_collection');
            $collection->getSelect()->where("main_table.parent_id IN ($ids)");
        }
        if ($this->getAdminRole() == 41) {
            $collection = Mage::getResourceModel('apdinteract_vir/order_collection');
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function getStoreList($var) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $results = $readConnection->fetchAll("SELECT * FROM iwd_storelocator_users WHERE account_id='$var'");
        $aid = array();
        foreach ($results as $val) {
            $aid[] = $val['store_id'];
        }
        return implode(",", $aid);
    }

    protected function getAdminRole() {
        $role_data = Mage::getModel('admin/user')->load($this->getAdminUser()->getUserId())->getRole()->getRoleId();
        return $role_data;
    }

    protected function getAdminUser() {
        $admin_user_session = Mage::getSingleton('admin/session');
        $admin_user = $admin_user_session->getUser();
        return $admin_user;
    }

    protected function getAdminId() {
        $adminuserId = 0; //default for admin login
        if ($this->getAdminUser()->getUsername() != "admin" && $this->getAdminRole() != 1) {
            $adminuserId = $this->getAdminUser()->getUserId();
        }
        return $adminuserId;
    }

    public function getRowUrl($row) {
        /**
         * When a grid row is clicked, this is where the user should
         * be redirected to - in our example, the method editAction of
         * OrderController.php in VIR module.
         */
        return $this->getUrl(
                        'apdinteract_vir_admin/order/edit', array(
                    'id' => $row->getId()
                        )
        );
    }

    protected function _prepareColumns() {
        /**
         * Here, we'll define which columns to display in the grid.
         */
        $this->addColumn('parent_id', array(
            'header' => $this->_getHelper()->__('ID'),
            'type' => 'number',
            'index' => 'parent_id',
        ));

        $this->addColumn('invoiceno', array(
            'header' => $this->_getHelper()->__('Invoice Number'),
            'type' => 'number',
            'index' => 'invoiceno',
        ));

        $this->addColumn('created_at', array(
            'header' => $this->_getHelper()->__('Created'),
            'type' => 'datetime',
            'index' => 'created_at',
        ));

        $this->addColumn('custname', array(
            'header' => $this->_getHelper()->__('Name'),
            'type' => 'text',
            'index' => 'custname',
        ));

        $this->addColumn('vehiclemodel', array(
            'header' => $this->_getHelper()->__('Vehicle'),
            'type' => 'text',
            'index' => 'vehiclemodel',
        ));

        $this->addColumn('status', array(
            'header' => $this->_getHelper()->__('Status'),
            'type' => 'options',
            'index' => 'status',
            'options' => Mage::helper('apdinteract_vir')->getOptionArray(),
        ));

        // custname  vehiclemodel invoiceno
//        $this->addColumn('updated_at', array(
//            'header' => $this->_getHelper()->__('Updated'),
//            'type' => 'datetime',
//            'index' => 'updated_at',
//        ));

        $orderSingleton = Mage::getSingleton(
                        'apdinteract_vir/order'
        );

        /**
         * Finally, we'll add an action column with an edit link.
         */
        $this->addColumn('edit', array(
            'header' => $this->_getHelper()->__('Edit'),
            'width' => '50px',
            'type' => 'action',
            'actions' => array(
                array(
                    'caption' => $this->_getHelper()->__('Edit'),
                    'url' => array(
                        'base' => 'apdinteract_vir_admin'
                        . '/order/edit',
                    ),
                    'field' => 'id'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'parent_id',
        ));

        $this->addColumn('print', array(
            'header' => $this->_getHelper()->__('Print'),
            'width' => '50px',
            'type' => 'action',
            'actions' => array(
                array(
                    'caption' => $this->_getHelper()->__('Pdf'),
                    'url' => array('base' => 'apdinteract_vir_admin/order/print'),
                    'field' => 'id',
                    'target'    => '_blank'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'parent_id',
        ));

        // TODO: add "are you sure?"
//         $this->addColumn('delete', array(
//            'header' => $this->_getHelper()->__('Delete'),
//            'width' => '50px',
//            'type' => 'action',
//            'actions' => array(
//                array(
//                    'caption' => $this->_getHelper()->__('Delete'),
//                    'url' => array(
//                        'base' => 'apdinteract_vir_admin'
//                                  . '/order/delete',
//                    ),
//                    'field' => 'id'
//                ),
//            ),
//            'filter' => false,
//            'sortable' => false,
//            'index' => 'parent_id',
//        ));


        return parent::_prepareColumns();
    }

    protected function _getHelper() {
        return Mage::helper('apdinteract_vir');
    }

}
