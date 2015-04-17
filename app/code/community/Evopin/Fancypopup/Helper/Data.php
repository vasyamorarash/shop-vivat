<?php

class Evopin_Fancypopup_Helper_Data extends Mage_Core_Helper_Abstract
{

	public function getStatusOptions() {
		return array(
			'0' => Mage::helper('fancypopup')->__('Disabled'), 
			'1' => Mage::helper('fancypopup')->__('Enabled')
		);
	}
	
	public function getIsEnabled() {
		return (bool) Mage::getStoreConfigFlag('fancypopup/general/active');
	}


}