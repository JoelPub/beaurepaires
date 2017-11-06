<?php
class ApdInteract_Costar_Block_Adminhtml_Costar_Renderer_Loglink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    /**    
    * Will render the specific column on admin grid to a link
    *   
    */
    public function render(Varien_Object $row)
        {
        $value =  $row->getData($this->getColumn()->getIndex());
        return '<a href="'.$value.'">Download</span>';

        }
}