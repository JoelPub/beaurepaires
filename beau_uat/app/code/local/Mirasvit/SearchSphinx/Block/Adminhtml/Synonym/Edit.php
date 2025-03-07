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



class Mirasvit_SearchSphinx_Block_Adminhtml_Synonym_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'synonym_id';
        $this->_blockGroup = 'searchsphinx';
        $this->_mode = 'edit';
        $this->_controller = 'adminhtml_synonym';

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('searchsphinx')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";

        return $this;
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_model')->getId() > 0) {
            return Mage::helper('searchsphinx')->__('Edit Synonyms');
        } else {
            return Mage::helper('searchsphinx')->__('Add Synonyms');
        }
    }
}
