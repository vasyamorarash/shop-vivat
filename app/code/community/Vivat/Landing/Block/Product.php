<?php
/**
 * Created by PhpStorm.
 * User: Vassya
 * Date: 14.04.2015
 * Time: 14:50
 */

class Vivat_Landing_Block_Product extends Mage_Core_Block_Template
{
    public function getProductSKU()
    {
        return Mage::getStoreConfig('landing/proposal/sku');
    }
}
