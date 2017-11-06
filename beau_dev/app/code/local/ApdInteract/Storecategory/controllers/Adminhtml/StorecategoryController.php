<?php
class ApdInteract_Storecategory_Adminhtml_StorecategoryController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu("storecategory/storecategory")->_addBreadcrumb(Mage::helper("adminhtml")->__("Storecategory  Manager"), Mage::helper("adminhtml")->__("Storecategory Manager"));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__("Storecategory"));
        $this->_title($this->__("Manager Storecategory"));

        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction() {
        $this->_title($this->__("Storecategory"));
        $this->_title($this->__("Storecategory"));
        $this->_title($this->__("Edit Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("storecategory/storecategory")->load($id);
        if ($model->getId()) {
            Mage::register("storecategory_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("storecategory/storecategory");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Storecategory Manager"), Mage::helper("adminhtml")->__("Storecategory Manager"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Storecategory Description"), Mage::helper("adminhtml")->__("Storecategory Description"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("storecategory/adminhtml_storecategory_edit"))->_addLeft($this->getLayout()->createBlock("storecategory/adminhtml_storecategory_edit_tabs"));
            $this->renderLayout();
        } else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("storecategory")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction() {

        $this->_title($this->__("Storecategory"));
        $this->_title($this->__("Storecategory"));
        $this->_title($this->__("New Item"));

        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("storecategory/storecategory")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("storecategory_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("storecategory/storecategory");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Storecategory Manager"), Mage::helper("adminhtml")->__("Storecategory Manager"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Storecategory Description"), Mage::helper("adminhtml")->__("Storecategory Description"));


        $this->_addContent($this->getLayout()->createBlock("storecategory/adminhtml_storecategory_edit"))->_addLeft($this->getLayout()->createBlock("storecategory/adminhtml_storecategory_edit_tabs"));

        $this->renderLayout();
    }

    public function saveAction() {

        $post_data = $this->getRequest()->getPost();


        if ($post_data) {

            try {


                //save image
                try {

                    
                    if (isset($post_data['image']['delete']) &&  (bool) $post_data['image']['delete'] == 1) {

                        $post_data['image'] = '';
                    } else {

                        unset($post_data['image']);

                        if (isset($_FILES)) {

                            if ($_FILES['image']['name']) {

                                if ($this->getRequest()->getParam("id")) {
                                    $model = Mage::getModel("storecategory/storecategory")->load($this->getRequest()->getParam("id"));
                                    if ($model->getData('image')) {
                                        $io = new Varien_Io_File();
                                        $io->rm(Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('image'))));
                                    }
                                }
                                $path = Mage::getBaseDir('media') . DS . 'storecategory' . DS . 'storecategory' . DS;
                                $uploader = new Varien_File_Uploader('image');
                                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                                $uploader->setAllowRenameFiles(false);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path . $_FILES['image']['name'];
                                $filename = $uploader->getNewFileName($destFile);
                                $uploader->save($path, $filename);

                                $post_data['image'] = 'storecategory/storecategory/' . $filename;
                            }
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
//save image
                //save image
                try {


                    if (isset($post_data['icon']['delete']) && (bool) $post_data['icon']['delete'] == 1) {


                        $post_data['icon'] = '';
                    } else {

                        unset($post_data['icon']);

                        if (isset($_FILES)) {

                            if ($_FILES['icon']['name']) {

                                if ($this->getRequest()->getParam("id")) {
                                    $model = Mage::getModel("storecategory/storecategory")->load($this->getRequest()->getParam("id"));
                                    if ($model->getData('icon')) {
                                        $io = new Varien_Io_File();
                                        $io->rm(Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('icon'))));
                                    }
                                }
                                $path = Mage::getBaseDir('media') . DS . 'storecategory' . DS . 'storecategory' . DS;
                                $uploader = new Varien_File_Uploader('icon');
                                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                                $uploader->setAllowRenameFiles(false);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path . $_FILES['icon']['name'];
                                $filename = $uploader->getNewFileName($destFile);
                                $uploader->save($path, $filename);

                                $post_data['icon'] = 'storecategory/storecategory/' . $filename;
                            }
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
//save image


                $model = Mage::getModel("storecategory/storecategory")
                        ->addData($post_data)
                        ->setId($this->getRequest()->getParam("id"))
                        ->save();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Storecategory was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setStorecategoryData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setStorecategoryData($this->getRequest()->getPost());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                return;
            }
        }
        $this->_redirect("*/*/");
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = Mage::getModel("storecategory/storecategory");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

    public function massRemoveAction() {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("storecategory/storecategory");
                $model->setId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction() {
        $fileName = 'storecategory.csv';
        $grid = $this->getLayout()->createBlock('storecategory/adminhtml_storecategory_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction() {
        $fileName = 'storecategory.xml';
        $grid = $this->getLayout()->createBlock('storecategory/adminhtml_storecategory_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

}
