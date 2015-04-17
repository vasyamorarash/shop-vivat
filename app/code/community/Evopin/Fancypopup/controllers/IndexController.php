<?php

class Evopin_Fancypopup_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$collection = Mage::getModel('fancypopup/fancypopup')->getCollection();
		var_dump($collection);
	}
}