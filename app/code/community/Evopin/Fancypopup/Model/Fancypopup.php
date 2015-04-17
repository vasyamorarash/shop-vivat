<?php

class Evopin_Fancypopup_Model_Fancypopup extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('fancypopup/fancypopup');
    }   
}