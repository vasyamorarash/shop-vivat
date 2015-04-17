<?php

class Evopin_Fancypopup_Block_Adminhtml_Fancypopup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('fancypopup_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('fancypopup')->__('Fancy Popup'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('fancypopup')->__('Fancy Popup'),
          'title'     => Mage::helper('fancypopup')->__('Fancy Popup'),
          'content'   => $this->getLayout()->createBlock('fancypopup/adminhtml_fancypopup_edit_tab_form')->toHtml(),
      ));
    
      $this->addTab('date_section', array(
          'label'     => Mage::helper('fancypopup')->__('Date Settings'),
          'title'     => Mage::helper('fancypopup')->__('Date Settings'),
          'content'   => $this->getLayout()->createBlock('fancypopup/adminhtml_fancypopup_edit_tab_formdate')
          ->toHtml(),
      ));
 
 
      
      $this->addTab('popup_section', array(
          'label'     => Mage::helper('fancypopup')->__('Popup Settings'),
          'title'     => Mage::helper('fancypopup')->__('Popup Settings'),
          'content'   => $this->getLayout()->createBlock('fancypopup/adminhtml_fancypopup_edit_tab_formpopup')->toHtml(),
      ));           
     
      return parent::_beforeToHtml();
  }


}