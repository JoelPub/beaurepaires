<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Sphinx Search Ultimate
 * @version   2.3.4
 * @build     1372
 * @copyright Copyright (C) 2016 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_SearchIndex_Block_Adminhtml_Index_Edit_Index_External_Url extends Varien_Data_Form_Element_Fieldset
{
    public function toHtml()
    {
        $model = $this->getModel();

        parent::__construct(array('legend' => Mage::helper('searchindex')->__('URL Settings')));

        $this->addField('url_template', 'text', array(
            'name' => 'properties[url_template]',
            'label' => Mage::helper('searchindex')->__('Url Template'),
            'required' => true,
            'value' => $model->getProperty('url_template'),
            'note' => Mage::helper('searchindex/help')->field('url_template'),
            'after_element_html' => '<span style="margin-left:25px">[GLOBAL]</span>',
        ));

        return parent::toHtml();
    }
}
