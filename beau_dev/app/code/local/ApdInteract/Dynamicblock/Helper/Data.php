<?php

class ApdInteract_Dynamicblock_Helper_Data extends Mage_Core_Helper_Abstract {



    /**
     * Check if static block is existing
     * @return boolean
     */
    public function isBlockExisting($key) {
        $model = Mage::getModel('cms/block');
        $collection = $model->getCollection()
                ->addFieldToFilter('identifier', array('eq' => $key));

        return ($collection->count()>0)?  true : false;
    }

    /**
     * Get all Homepage CTA Carousel
     * @return array
     */
    public function getHomepageCarouselBlock() {
        $cms_block = array();
        $cms_blockAr = array('homepage_cta_left', 'homepage_cta_middle', 'homepage_cta_right');

        foreach ($cms_blockAr as $block_id):
            $blockInfo = Mage::getModel('dynamicblock/dynamicblock')->getAllBlocksPerPosition($block_id); // check if active
            if ($blockInfo->count() > 0)           
                $cms_block[] = $block_id;
        endforeach;
        
        return $cms_block;
    }

}
