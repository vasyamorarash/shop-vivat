<?php

class Evopin_Fancypopup_Block_Adminhtml_Fancypopup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {   	
    $this->_controller = 'adminhtml_fancypopup';
    $this->_blockGroup = 'fancypopup';
    $this->_headerText = Mage::helper('fancypopup')
    ->__('Fancy Popup');
    $this->_addButtonLabel = Mage::helper('fancypopup')->__('Add Fancy Popup');
    parent::__construct();
  }
}