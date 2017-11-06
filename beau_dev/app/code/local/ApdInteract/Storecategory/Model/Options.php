<?php
class ApdInteract_Storecategory_Model_Options
{    

    public function toOptionArray()
    {
        $categories = Mage::getModel("storecategory/storecategory")->getCollection();

        $options = array();
        $options[] = array('value'=>'','label'=>'--Please Select--');
        foreach($categories as $category) {
            $options[] = array('value'=>$category->getId(),'label'=>$category->getCode());
        }
        return $options;
    }
}