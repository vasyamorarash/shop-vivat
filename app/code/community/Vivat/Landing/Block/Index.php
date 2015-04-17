<?php   
class Vivat_Landing_Block_Index extends Mage_Core_Block_Template{   

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @return mixed
     */
    public function getRandomProductId()
    {
        $categoryId = Mage::getStoreConfig('landing/general/category'); // get category id from admin

        $category = new Mage_Catalog_Model_Category();
        $category->load($categoryId);
        $collection = $category->getProductCollection();
        $collection->addAttributeToSelect('*');
        $arrayProductId = array();


        foreach ($collection as $_product) {
//            echo $_product->getProductUrl();
//            echo $_product->getName();
            $arrayProductId[] = $_product->getId();
        }
        $rand = rand(0,count($arrayProductId)-1);
        return $arrayProductId[$rand];
    }

    public function isActive()
    {
        return Mage::getStoreConfig('landing/general/status');
    }


    /**
     *
     */
    public function checkDate()
    {
        $dateAdmin = Mage::getStoreConfig('landing/general/date');
        $date = date('Y-m-d');
        $config = new Mage_Core_Model_Config();
        if($dateAdmin=='')
        {
            $config ->saveConfig('landing/general/date', $date, 'default',0);
        }
        if($dateAdmin!=$date)
        {
            $productId = $this->getRandomProductId();
            $config ->saveConfig('landing/general/product', $productId, 'default',0);
            $config ->saveConfig('landing/general/date', $date, 'default',0);
            $this->setDiscount($productId);

            die("<meta http-equiv='refresh' content='0; url=http://shop.vivat.info/landing'>");

        }
    }

    public function getProductId()
    {
        return Mage::getStoreConfig('landing/general/product');
    }

    public function getProductSKU()
    {
        return Mage::getStoreConfig('landing/proposal/sku');
    }

    public function getDiscount(){
        return Mage::getStoreConfig('landing/general/discount');
    }

    public function setDiscount($productId){
        $product = Mage::getModel('catalog/product')->load($productId);
        $product->setSpecialPrice($product->getPrice() * (1 - (floatval($this->getDiscount())/100) ) );

        $product->setSpecialFromDate(date('Y-m-d'));
        $product->setSpecialFromDateIsFormated(true);

        $product->setSpecialToDate(date('Y-m-d'));
        $product->setSpecialToDateIsFormated(true);
        $product->save();
    }





}