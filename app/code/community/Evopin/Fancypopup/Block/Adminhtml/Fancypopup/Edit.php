<?php

class Evopin_Fancypopup_Block_Adminhtml_Fancypopup_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'fancypopup';
        $this->_controller = 'adminhtml_fancypopup';
        
        $this->_updateButton('save', 'label', Mage::helper('fancypopup')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('fancypopup')->__('Delete'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('fancypopup_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'fancypopup_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'fancypopup_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('fancypopup_data') && Mage::registry('fancypopup_data')->getId() ) {
            return Mage::helper('fancypopup')->__("Edit '%s'.", $this->htmlEscape(Mage::registry('fancypopup_data')->getPopupName()));
        } else {
            return Mage::helper('fancypopup')->__('New Fancy Popup');
        }
    }
}