<?php

class Evopin_Fancypopup_Block_Adminhtml_Fancypopup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      
      $model = Mage::registry('fancypopup_data'); //importante nas abas pa saber onde grava
      
      
      $fieldset = $form->addFieldset('fancypopup_form', 
      array('legend'=>Mage::helper('fancypopup')->__('Fancy Popup')));
		
      $fieldset->addField('popup_name', 'text', array(
          'label'     => Mage::helper('fancypopup')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'popup_name',
          'maxlength' => '30'
      ));
      
      
      $fieldset->addField('image_link', 'image', array(
          'label'     => Mage::helper('fancypopup')->__('Popup Image'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'image_link',
	  ));

      $fieldset->addField('width_image', 'text', array(
          'label'     => Mage::helper('fancypopup')->__('Width Image'),
          'class'     => 'required-entry validate-digits',
          'required'  => true,          
          'name'      => 'width_image',          
	  ));
	  
      $fieldset->addField('height_image', 'text', array(
          'label'     => Mage::helper('fancypopup')->__('Height Image'),
          'class'     => 'required-entry validate-digits',
          'required'  => true,          
          'name'      => 'height_image',          
	  ));	  	                  

      $fieldset->addField('url_link', 'text', array(
          'label'     => Mage::helper('fancypopup')->__('Url'),
          'name'      => 'url_link',
	  ));
	  
      $fieldset->addField('is_active', 'select', array(
          'label'     => Mage::helper('fancypopup')->__('Status'),
          'options' => $this->helper('fancypopup/data')->getStatusOptions(),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'is_active',
	  ));  
	  
	        
      if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('fancypopup')->__('Store View'),
                'title'     => Mage::helper('fancypopup')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
      }
     
      if ( Mage::getSingleton('adminhtml/session')->getFancypopupData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFancypopupData());
          Mage::getSingleton('adminhtml/session')->setFancypopupData(null);
      } elseif ( Mage::registry('fancypopup_data') ) {
          $form->setValues(Mage::registry('fancypopup_data')->getData());
      }
      $form->setValues($model->getData()); //coloquei
      $this->setForm($form);
      
      return parent::_prepareForm();
  }

  
  
  
}