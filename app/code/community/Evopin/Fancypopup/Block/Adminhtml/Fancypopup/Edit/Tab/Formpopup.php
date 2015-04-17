<?php

class Evopin_Fancypopup_Block_Adminhtml_Fancypopup_Edit_Tab_Formpopup extends Mage_Adminhtml_Block_Widget_Form 

{
  protected function _prepareForm()
  {  
	  $form = new Varien_Data_Form();	
	  
	  $model = Mage::registry('fancypopup_data');
	  
	  $fieldset = $form->addFieldset('fancypopup_popup', array('legend'=>Mage::helper('fancypopup')->__('Popup Settings')));
	  
      // TODO: css and js styles to choose here
      
      $fieldset->addField('delay_start', 'text', array(
          'label'     => Mage::helper('fancypopup')->__('Delay Start'),
          'name'      => 'delay_start',
          'class'     => 'validate-zero-or-greater',          
	  ));
	  
      $fieldset->addField('delay_close', 'text', array(
          'label'     => Mage::helper('fancypopup')->__('Delay Close'),
          'name'      => 'delay_close',
          'class'     => 'validate-zero-or-greater',          
	  ));	  
	  	       
//       $fieldset->addField('priority', 'text', array(
//           'label'     => Mage::helper('fancypopup')->__('Priority'),
//           'name'      => 'priority',
//           'class'     => 'validate-zero-or-greater',         
// 	  ));
	  
//       $fieldset->addField('opacity', 'text', array(
//           'label'     => Mage::helper('fancypopup')->__('Opacity'),
//           'name'      => 'opacity',
//           'class'     => 'validate-zero-or-greater',         
// 	  ));	
	  
	  $form->setValues($model->getData());
	  
	  $this->setForm($form);
      return parent::_prepareForm();
  }

  
}  
