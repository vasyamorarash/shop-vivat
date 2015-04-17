<?php
/**
 * Product brand
 */

class Infortis_Infortis_Block_Catvideo extends Mage_Core_Block_Template
{

    private $category;

    public function __construct()
    {
        $this->category = Mage::registry('current_category');
    }


    public function getId()
    {
        return $this->category->getId();
    }
}
