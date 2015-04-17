<?php
class Svitla_Iboughtit_Block_Checkout_Success extends Svitla_Iboughtit_Block_Checkout{

    private $order = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Get last order id
        $this->order = Mage::getModel('sales/order')->load(
            Mage::getSingleton('checkout/session')->getLastOrderId()
        );
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->order->getId();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return Mage::getStoreConfig('svitla_iboughtit/success/title');
    }

    /**
     * @return mixed
     */
    public function getMarketingText()
    {
        return Mage::getStoreConfig('svitla_iboughtit/success/marketing_message');
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->order->getCustomerEmail();
    }

    /**
     * @return string
     */
    protected function getServerUrl()
    {
        return 'http://server.i-bought-it.com/?'.
            http_build_query(
                [
                    'order_id'=>$this->order->getId(),
                    'customer_email'=>$this->getCustomerEmail(),
                    'btn_txt'=>Mage::getStoreConfig('svitla_iboughtit/success/button_text'),
                    'ibirefererid'=>Mage::getModel('core/cookie')->get('ibirefererid')
                ]
            );
    }

}