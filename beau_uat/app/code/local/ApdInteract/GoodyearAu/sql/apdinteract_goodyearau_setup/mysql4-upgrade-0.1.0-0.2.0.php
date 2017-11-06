<?php
/**
 * Copy all CMS pages and CMS blocks from Beaurepaires default website to Goodyear AU website
 */

$installer = $this;

$installer->startSetup();

$currentStore = Mage::app()->getStore();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$sourStoreCode = 'default';
$destStoreCode = 'goodyear_au';


// Load the store and make sure it doesn't exist
$sourStore = Mage::getModel('core/store')->load($sourStoreCode, 'code');
$destStore = Mage::getModel('core/store')->load($destStoreCode, 'code');

if ($sourStore->getId() && $destStore->getId()) {
    // Copy all CMS pages

    /** @var $pageCollection Mage_Cms_Model_Resource_Page_Collection */
    $pageCollection = Mage::getResourceModel('cms/page_collection');

    $destPages = array();
    $destPageCollection = Mage::getResourceModel('cms/page_collection');
    $destPageCollection->addStoreFilter($destStore->getId(), false);
    foreach ($destPageCollection as $cmsPage) {
        $destPages[] = $cmsPage->getIdentifier();
    }

    $pageCollection
        ->addStoreFilter($sourStore->getId(), false)
        ->addFieldToFilter('is_active', 1);

    /** @var $cmsPage Mage_Cms_Model_Page */
    foreach ($pageCollection as $cmsPage) {
        if (in_array($cmsPage->getIdentifier(), $destPages)) {
            continue;
        }

        $newCmsPage = clone $cmsPage;
        $newCmsPage
            ->setId(null)
            ->setCreationTime(null)
            ->setUpdateTime(null)
            ->setStores(array($destStore->getId()))
        ;

        if (preg_match('/(^| )Beaurepaires( |$)/im', $newCmsPage->getTitle())) {
            $newCmsPage->setTitle(preg_replace('/(^| )Beaurepaires( |$)/im', '$1Goodyear$2', $newCmsPage->getTitle()));
        } else {
            $newCmsPage->setTitle($newCmsPage->getTitle());
        }

        $newCmsPage->save();
        //var_dump($cmsPage->debug());
    }


    // Copy all CMS blocks

    /** @var $blockCollection Mage_Cms_Model_Resource_Block_Collection */
    $blockCollection = Mage::getResourceModel('cms/block_collection');

    $destBlocks = array();
    $destBlockCollection = Mage::getResourceModel('cms/block_collection');
    $destBlockCollection->addStoreFilter($destStore->getId(), false);
    foreach ($destBlockCollection as $cmsBlock) {
        $destBlocks[] = $cmsBlock->getIdentifier();
    }

    $blockCollection
        ->addStoreFilter($sourStore->getId(), false)
        ->addFieldToFilter('is_active', 1);

    /** @var $cmsBlock Mage_Cms_Model_Block */
    foreach ($blockCollection as $cmsBlock) { 
        if (in_array($cmsBlock->getIdentifier(), $destBlocks)) {
            continue;
        }

        $newCmsBlock = clone $cmsBlock;
        $newCmsBlock
            ->setId(null)
            ->setCreationTime(null)
            ->setUpdateTime(null)
            ->setStores(array($destStore->getId()))
        ;

        if (preg_match('/(^| )Beaurepaires( |$)/im', $newCmsBlock->getTitle())) {
            $newCmsBlock->setTitle(preg_replace('/(^| )Beaurepaires( |$)/im', '$1Goodyear$2', $newCmsBlock->getTitle()));
        } else {
            $newCmsBlock->setTitle($newCmsBlock->getTitle());
        }

        $newCmsBlock->save();
    }
}

Mage::app()->setCurrentStore($currentStore);

$installer->endSetup();
