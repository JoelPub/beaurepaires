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



/**
 * Stop-words import block.
 *
 * @category Mirasvit
 */
class Mirasvit_SearchSphinx_Block_Adminhtml_Stopword_Import extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'stopword_id';
        $this->_blockGroup = 'searchsphinx';
        $this->_mode = 'import';
        $this->_controller = 'adminhtml_stopword';

        $this->_addButton('save', array(
            'label' => Mage::helper('searchsphinx')->__('Import Dictionary'),
            'onclick' => 'editForm.submit();',
            'class' => 'save',
        ), 1);

        return $this;
    }

    public function getHeaderText()
    {
        return Mage::helper('searchsphinx')->__('Import Dictionary');
    }
}
