<?php
 class ApdInteract_Vir_Block_Adminhtml_Report_Consumer_Form extends Mage_Adminhtml_Block_Widget_Form{

     /**
      * @return Mage_Adminhtml_Block_Widget_Form
      */
     protected function _prepareForm(){
         $actionUrl = $this->getUrl('*/*/*');
         $form = new Varien_Data_Form(
             array('id' => 'filter_form', 'action' => $actionUrl, 'method' => 'get')
         );

         $htmlIdPrefix = 'vir_report_';
         $form->setHtmlIdPrefix($htmlIdPrefix);
         $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('reports')->__('Filter')));

         $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

         $fieldset->addField('from', 'date', array(
             'name'      => 'from',
             'format'    => $dateFormatIso,
             'image'     => $this->getSkinUrl('images/grid-cal.gif'),
             'label'     => Mage::helper('reports')->__('From Date'),
             'title'     => Mage::helper('reports')->__('From'),
             'required'  => false
         ));

         $fieldset->addField('to', 'date', array(
             'name'      => 'to',
             'format'    => $dateFormatIso,
             'image'     => $this->getSkinUrl('images/grid-cal.gif'),
             'label'     => Mage::helper('reports')->__('To Date'),
             'title'     => Mage::helper('reports')->__('To'),
             'required'  => false
         ));

         $fieldset->addField('status', 'select', array(
             'name'      => 'status',
             'options'   =>  $this->_statusOption(),
             'label'     => Mage::helper('reports')->__('Status'),
         ));

         $form->setUseContainer(true);
         $this->setForm($form);

         return parent::_prepareForm();
     }

     /**
      * @return Mage_Adminhtml_Block_Widget_Form
      *
      */
     protected function _initFormValues()
     {
         $data = $this->getFilterData()->getData();

         Mage::getSingleton('admin/session')->setReportFilter($data);
         foreach ($data as $key => $value) {
             if (is_array($value) && isset($value[0])) {
                 $data[$key] = explode(',', $value[0]);
             }
         }
         $this->getForm()->addValues($data);
         return parent::_initFormValues();
     }

     /**
      * @return array
      *
      */
     protected function _statusOption(){

         $status_option = array_merge(array('empty' => 'Please select option'),Mage::helper('apdinteract_vir')->getOptionArray());
         return $status_option;
     }

 }