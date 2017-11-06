<?php

class ApdInteract_Catalog_Block_Breadcrumbs extends Mage_Catalog_Block_Breadcrumbs
{


    protected function _prepareLayout()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb('home', array(
                'label'=>Mage::helper('catalog')->__('Home'),
                'title'=>Mage::helper('catalog')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ));
            
            
            $current_category   = Mage::registry('current_category');
			$current_product    = Mage::registry('current_product');
			if(!$current_category && $current_product){
				$categories = $current_product->getCategoryCollection()->addAttributeToSelect('name')->setPageSize(1);
				foreach($categories as $category) {
					Mage::unregister('current_category');
					Mage::register('current_category', $category);
				}
			}

            $title = array();
            $path  = Mage::helper('catalog')->getBreadcrumbPath();

            foreach ($path as $name => $breadcrumb) {
                $breadcrumbsBlock->addCrumb($name, $breadcrumb);
                $title[] = $breadcrumb['label'];
            }

            if ($headBlock = $this->getLayout()->getBlock('head')) {
                $headBlock->setTitle(join($this->getTitleSeparator(), array_reverse($title)));
            }
        }
        return parent::_prepareLayout();
    }
}
