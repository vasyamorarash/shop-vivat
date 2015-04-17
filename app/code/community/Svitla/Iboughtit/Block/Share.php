<?php
class Svitla_Iboughtit_Block_Share extends Mage_Core_Block_Template{

    // Product names
    private $productsNames = '';

    // Product images
    private $productImages = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTemplate('svitla/iboughtit/sharepage.phtml');
    }

    public function getProductImages()
    {
        return $this->productImages;
    }

    public function getProductsNames()
    {
        return $this->productsNames;
    }

    /**
     * @param $order_id
     */
    public function setProducts($order_id)
    {
        if($order = Mage::getModel('sales/order')->load($order_id)){
            $products =  $order->getAllItems();
            $shift = count($products) - 1;

            for($i = 0; isset($products[$i]); $i++) {
                if($i >= $shift){
                    $this->productsNames .= $products[$i]->getName().'!';
                }else{
                    $this->productsNames .= $products[$i]->getName().', ';
                }

                // Load product data
                $product = Mage::getModel('catalog/product')->load($products[$i]->getProductId());

                // Set product images
                $this->productImages[$i] = (string)Mage::helper('catalog/image')->init($product, 'thumbnail')->resize(200);

            }

        }
    }


}