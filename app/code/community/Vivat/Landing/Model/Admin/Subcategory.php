<?php
/**
 * Created by PhpStorm.
 * User: Vassya
 * Date: 05.03.2015
 * Time: 15:32
 */
class Vivat_Landing_Model_Admin_Subcategory
{
    public function toOptionArray($addEmpty = true)
    {
        $tree = Mage::getResourceModel('catalog/category_tree');

        $collection = Mage::getResourceModel('catalog/category_collection');

        $collection->addAttributeToSelect('name')
            //->addRootLevelFilter()
            ->load();

        $options = array();

        if ($addEmpty) {
            $options[] = array(
                'label' => Mage::helper('adminhtml')->__('-- Please Select a Category --'),
                'value' => ''
            );
        }
        foreach ($collection as $category) {
            $options[] = array(
                'label' => $category->getName(),
                'value' => $category->getId()
            );
        }

        return $options;
    }
}
