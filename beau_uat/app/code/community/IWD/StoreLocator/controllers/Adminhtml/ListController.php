<?php
class IWD_StoreLocator_Adminhtml_ListController extends Mage_Adminhtml_Controller_Action{
	
	
	protected function _initAction(){
		// load layout, set active menu and breadcrumbs
		$this->loadLayout()
			->_setActiveMenu('storelocator/list')
			->_addBreadcrumb(Mage::helper('storelocator')->__('Store Locator'), Mage::helper('storelocator')->__('Store Locator'))
			->_addBreadcrumb(Mage::helper('storelocator')->__('Manage Store'), Mage::helper('storelocator')->__('Manage Store'));
		return $this;
	}
	
	
	public function indexAction(){
		
		$this->_title($this->__('Store Locator'))->_title($this->__('Manage Stores'));
		
		$this->_getSession()->addNotice(
				$this->__('<a href="https://docs.google.com/a/interiorwebdesign.com/document/d/12F4yvI2IHA7L-WfvYcuHTTDrLewxJLQrZc7lkL-iV74/edit" target="_blank">Store Locator Documentation</a>')
		);
		 
		$this->loadLayout();
		
		$this->_setActiveMenu('slocator/list');
		
		$this->_addBreadcrumb(Mage::helper('storelocator')->__('Manage Store Locations'),
				Mage::helper('storelocator')->__('Store Locator')
		);
		
		$this->renderLayout();
	}
	
	
	public function newAction(){
		$this->_forward('edit');
	}
	
	public function editAction(){
		
		
		$this->_title($this->__('Store Locator'))
			->_title($this->__('Store'))
			->_title($this->__('Manage Store'));
		
		// 1. Get ID and create model
		$id = $this->getRequest()->getParam('store_id');
		$model = Mage::getModel('storelocator/stores');
		
		// 2. Initial checking
		if ($id) {
			$model->load($id);
			if (! $model->getId()) {
				Mage::getSingleton('adminhtml/session')->addError(
						Mage::helper('storelocator')->__('This store no longer exists.'));
				$this->_redirect('*/*/');
				return;
			}
		}
		
		$this->_title($model->getId() ? $model->getTitle() : $this->__('New Store'));
		
		// 3. Set entered data if was error when we do save
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (! empty($data)) {
			$model->setData($data);
		}
		
		// 4. Register model to use later in blocks
		Mage::register('storelocator_store', $model);
		
		// 5. Build edit form
		$this->_initAction()
		->_addBreadcrumb(
				$id ? Mage::helper('storelocator')->__('Edit Store')
				: Mage::helper('storelocator')->__('New Store'),
				$id ? Mage::helper('storelocator')->__('Edit Store')
				: Mage::helper('storelocator')->__('New Store'));
		
		$this->renderLayout();

	}
	
	protected function _filterPostData($data){
		$data = $this->_filterDates($data, array('custom_theme_from', 'custom_theme_to'));
		return $data;
	}
	 
	
public function saveAction(){
        // check if data sent
    if(count($_POST['admin_users'])!=0)
	{
    $resource = Mage::getSingleton('core/resource');
    $writeConnection = $resource->getConnection('core_write');
 	$writeConnection->query("DELETE FROM iwd_storelocator_users WHERE store_id='".$this->getRequest()->getParam('entity_id')."'");	
	foreach($_POST['admin_users'] as $val)
	{
		$writeConnection->query("INSERT INTO iwd_storelocator_users SET store_id='".$this->getRequest()->getParam('entity_id')."',account_id='$val'");
	}	
	$_POST['admin_users'] = implode(",",$_POST['admin_users']);		
	}

		
		
		
		
		$data = $this->getRequest()->getPost();
		
		#$data['admin_users'] = implode(",",$_POST['admin_users']);
        if ($data) {
        	
            $data = $this->_filterPostData($data);
           
            //init model and set data
            $model = Mage::getModel('storelocator/stores');
            
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {            
                $model->load($id);
            }

          
            
			//ICON
			
            if(isset($data['icon']['delete']) && $data['icon']['delete'] == 1){
            	unset($data['icon']['delete']);
            	$data['icon'] = '';            
            }
            
            if(isset($data['icon']['value'])){
            	$data['icon'] = $data['icon']['value'];            	
            }
            
            
            //IMAGE
            if(isset($data['image']['delete']) && $data['image']['delete'] == 1){
            	unset($data['image']['delete']);
            	$data['image'] = '';
            }
            
            if(isset($data['image']['value'])){
            	$data['image'] = $data['image']['value'];
            }
            
            
            
            //update stores
            
            
          
             
            //icon
            if(isset($_FILES['icon']['name']) and (file_exists($_FILES['icon']['tmp_name']))){
            	try
            	{
            		$path = Mage::getBaseDir('media') . DS . 'iwd/storelocator/';
            		$uploader = new Varien_File_Uploader('icon');
            
            		$uploader->setAllowedExtensions(array('jpg','png','gif','jpeg'));
            		$uploader->setAllowRenameFiles(true);
            		$uploader->setFilesDispersion(false);            		
            		$destFile = $path . $_FILES['icon']['name'];
            		$filename = $uploader->getNewFileName($destFile);
            		$result  = $uploader->save($path, $filename);
            	
            
            		$data['icon'] = 'iwd/storelocator/' . $result['file'];
            	}
            	catch(Exception $e)
            	{
            		unset($data['icon']);
            		$this->_getSession()->addError($e->getMessage());
            		$this->_getSession()->setFormData($data);
            		$this->_redirect('*/*/edit', array('page_id' => $this->getRequest()->getParam('entity_id')));
            	}
            }
            
            
            
            
            //image
            if(isset($_FILES['image']['name']) and (file_exists($_FILES['image']['tmp_name']))){
            	try
            	{
            		$path = Mage::getBaseDir('media') . DS . 'iwd/storelocator/';
            		$uploader = new Varien_File_Uploader('image');
            
            		$uploader->setAllowedExtensions(array('jpg','png','gif','jpeg'));
            		$uploader->setAllowRenameFiles(true);
            		$uploader->setFilesDispersion(false);
            		$destFile = $path . $_FILES['image']['name'];
            		$filename = $uploader->getNewFileName($destFile);
            		$result  = $uploader->save($path, $filename);
            		 
            
            		$data['image'] = 'iwd/storelocator/' . $result['file'];
            	}
            	catch(Exception $e)
            	{
            		unset($data['image']);
            		$this->_getSession()->addError($e->getMessage());
            		$this->_getSession()->setFormData($data);
            		$this->_redirect('*/*/edit', array('page_id' => $this->getRequest()->getParam('entity_id')));
            	}
            }
            
            // try to save it
            try {
            	
            	/*$stores = $data['stores'];
            	unset($data['stores']);*/
                $yesno = array(
                        'comm_tyres', 'cons_tyres', 'brake_fitting', 'wheel_balancing_service', 
                        'wheel_alignment_service', 'batteries_available', 'nitrogen_available', 
                        'wheelchair_access', 'drop_off', 'has_mobility_fleet', 
                        'waiting_area', 'guest_wifi', 'guest_tablet', 'coffee_tea', 'off_street_parking',
						'email_copy_invoices','payment_interest_free_terms','payment_card','payment_eftpos',
						'road_hazard_warranty','flat_tyres_repair','windscreen_wipers');
                foreach ($yesno as $fieldname) {
                    if (isset($data[$fieldname])) {
                        $data[$fieldname] = 1;
                    }
                    else {
                        $data[$fieldname] = 0;
                    }
                }
                
                
            	$website = $data['website'];
            	if (!preg_match('/http/i', $website)){
            		$data['website'] = 'http://' . $website;
            	}
            	
            	$model->setData($data);
                // save the data
                $model->save();

                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('storelocator')->__('The store has been saved.'));
                
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                
                //update stores
               // $this->_updateStores($stores, $model->getId());
                
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('store_id' => $model->getId(), '_current'=>true));
                    return;
                }
                
                
               
               
                
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
            	
                $this->_getSession()->addError($e->getMessage());
                
            }catch (Exception $e) {
            	
                $this->_getSession()->addException($e, Mage::helper('storelocator')->__('An error occurred while saving the store information.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('page_id' => $this->getRequest()->getParam('entity_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
	
    
    protected function _updateStores($stores, $id){
    	$collection = Mage::getModel('storelocator/store')->getCollection()->addFieldToFilter('locatorstore', array('eq'=>$id));
    	foreach($collection as $item){
    		try{
    			$item->delete();
    		}catch(Exception $e){
    			Mage::logException($e);
    		}
    	} 
    	
    	foreach($stores as $store_id){
    		$model = Mage::getModel('storelocator/store');
    		
    		$model->setData('store_id',$store_id);
    		$model->setData('locatorstore',$id);
    		try{
    			$model->save();
    		}catch(Exception $e){
    			Mage::logException($e);
    		}
    	}
    }
    
    /**
     * Delete action
     */
    public function deleteAction(){
    	// check if we know what should be deleted
		
		$id = $this->getRequest()->getParam('store_id');
		$admin_user_session = Mage::getSingleton('admin/session');
		$adminuserId = $admin_user_session->getUser()->getUserId();
		$role_data = Mage::getModel('admin/user')->load($adminuserId)->getRole()->getRoleId();

		if($role_data==33)
		{
			Mage::getSingleton('adminhtml/session')->addError("Your account has no enough rights to delete.");
			$this->_redirect('*/*/edit', array('store_id' => $id));
			return;
		}
		
    	
    	if ($id) {
    		
    		try {
    			// init model and delete
    			$model = Mage::getModel('storelocator/stores');
    			$model->load($id);
    			
    			$model->delete();
    			// display success message
    			Mage::getSingleton('adminhtml/session')->addSuccess(
    			Mage::helper('storelocator')->__('The store has been deleted.'));
    			
    			// go to grid    			
    			$this->_redirect('*/*/');
    			return;
    
    		} catch (Exception $e) {
    			
    			// display error message
    			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
    			
    			// go back to edit form
    			$this->_redirect('*/*/edit', array('store_id' => $id));
    			return;
    		}
    	}
    	// display error message
    	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('storelocator')->__('Unable to find a store to delete.'));
    	// go to grid
    	$this->_redirect('*/*/');
    }
    
    
    
    /**
     *  Export customer grid to CSV format
     */    
    public function exportCsvAction(){
        
        $fileName   = 'customers.csv';
        
        $content    = $this->getLayout()->createBlock('storelocator/adminhtml_list_export')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }
    
}