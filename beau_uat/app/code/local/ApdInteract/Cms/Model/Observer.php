<?php

class ApdInteract_Cms_Model_Observer {

    public function onAdminCmsPageEditPreDispatch($observer) {
        $id = Mage::app()->getRequest()->getParam('page_id');

        $model = Mage::getModel('cms/page')->load($id);
        if ($model->getUnderVersionControl()):
            $revision_id = $model->getData('published_revision_id');
            $revision = Mage::getModel('enterprise_cms/page_revision')->load($revision_id);
            if ($revision->getData('page_id') != $id):


                $version = Mage::getModel('enterprise_cms/page_version');

                $revisionInitialData = $model->getData();
                $revisionInitialData['copied_from_original'] = true;

                $version->setLabel($model->getTitle())
                        ->setAccessLevel(Enterprise_Cms_Model_Page_Version::ACCESS_LEVEL_PUBLIC)
                        ->setPageId($model->getId())
                        ->setUserId(Mage::getSingleton('admin/session')->getUser()->getId())
                        ->setInitialRevisionData($revisionInitialData)
                        ->save();


                $revision = $version->getLastRevision();

                if ($revision instanceof Enterprise_Cms_Model_Page_Revision) {
                    $revision->publish();
                }


            endif;
        endif;
    }

}
