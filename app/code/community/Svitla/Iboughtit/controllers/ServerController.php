<?php

class Svitla_Iboughtit_ServerController extends Mage_Core_Controller_Front_Action
{
    /**
     * Default info, Hello!
     */
    public function IndexAction()
    {
        if ($this->checkApiKey()) {
            // Get module API
            $helper = Mage::helper('iboughtit');

            // Render answer
            $answer = $this->getLayout()->createBlock('iboughtit/server_general_connect');

            // Add Soap customer or load if is
            if (!$SOAP_Customer = $helper->getSoapCustomer('iboughtit')) {
                /*
                $SOAP_Customer = $helper->addSoapCustomer(
                    'iboughtit',
                    $helper->randomPassword()
                );
                */
            }

            // Set answer data
            $answer->setSoapLogin($SOAP_Customer->getUsername());
            $answer->setSoapApiKey($SOAP_Customer->getApiKey());

            // Send answer
            echo $answer->toHtml();

        } else {
            // Return 404
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $pageId = Mage::getStoreConfig('web/default/cms_no_route');
            if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
                $this->_forward('defaultNoRoute');
            }
        }
    }

    /**
     * Return product list
     */
    public function LoadProductAction()
    {
        if ($this->checkApiKey()) {
            // Set answer
            $answer = array(
                'data' => array(),
                'page' => Mage::app()->getRequest()->getParam('p', 1),
                'page-size' => Mage::app()->getRequest()->getParam('size', 1)

            );

            //fetch all visible products
            $product_collection = Mage::getModel('catalog/product')->getCollection();


            $product_collection->addAttributeToSelect('*');

            //filter by name or description
            if ($sTerm = Mage::app()->getRequest()->getParam('f_name')) {
                $product_collection->addFieldToFilter(array(
                    array('attribute' => 'name', 'like' => $sTerm)
                ));
            }


            //pagination (THIS DOESNT WORK!)
            $product_collection->setPageSize($answer['page-size'])->setCurPage($answer['page']);

            //TEST OUTPUT
            foreach ($product_collection as $product) {
                $answer['data'][] = $product->getData();
            }

            $answer['count-products'] = $product_collection->getSize();

            echo json_encode($answer);

        } else {
            // Return 404
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $pageId = Mage::getStoreConfig('web/default/cms_no_route');
            if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
                $this->_forward('defaultNoRoute');
            }
        }
    }

    /**
     * Redirect to product page by url
     */
    public function sharePageAction()
    {
        // Cookie API
        $cookie = Mage::getSingleton('core/cookie');

        // If referer is facebook
        if ($referer = $this->getRequest()->getServer('HTTP_REFERER')) {

            $referer = parse_url($referer);
            $referer = explode('.', $referer['host']);

            if (isset($referer['1']) && isset($referer['2'])) {
                $referer = $referer['1'] . '.' . $referer['2'];
            } else {
                $referer = 'undefined';
            }

            $product_id = false;

            // Check count products
            if ($order_id = Mage::app()->getRequest()->getParam('order_id')) {
                // If order is load
                if ($order = Mage::getModel('sales/order')->load($order_id)) {
                    // Get order items
                    $products = $order->getAllItems();
                    // Set product id if count == one
                    if (count($products) == 1) {
                        $product_id = $products['0']->getProductId();
                    }
                }
            }

            switch ($referer) {
                case('facebook.com'):
                    // It is new referrer?
                    if(!$cookie->get('ibirefererid')){
                        // Set custom visitors data
                        $order_id = Mage::app()->getRequest()->getParam('order_id');
                        $buyer_id = Mage::app()->getRequest()->getParam('buyer');
                        $home_url = Mage::helper('core/url')->getHomeUrl();
                        $api_key = base64_encode(Mage::getStoreConfig('svitla_iboughtit/general/apikey'));
                        $customerIP = Mage::helper('core/http')->getRemoteAddr(true);

                        // Set visitors data
                        $refererId = $this->milliseconds(true) . mt_rand(1, 1000);
                        $cookie->set('ibirefererid', $refererId, time() + 2592000, '/');



                        // Request to ibi server
                        $context = stream_context_create(array('http' =>
                            array(
                                'method' => 'POST',
                                'header' => 'Content-type: application/x-www-form-urlencoded',
                                'content' => http_build_query(
                                    array(
                                        'order_id' => $order_id,
                                        'buyer_id' => $buyer_id,
                                        'home_url' => $home_url,
                                        'api_key' => $api_key,
                                        'session_id' => $refererId,
                                        'ip' => $customerIP
                                    )
                                )
                            )
                        ));

                        $query = 'http://server.i-bought-it.com/success/addreferrals';


                        // Get store product pack
                        if (!$response = @file_get_contents($query, false, $context)) {
                            exit('Fatal error: problem with response from ibi.');
                        }
                    }

                    // Redirect to product page!
                    $this->redirectToProduct($product_id);

                    // Close case www.facebook.com
                    break;
                default: {
                $this->redirectToProduct();
                }
            }

        }

        // If order
        if ($order_id = Mage::app()->getRequest()->getParam('order_id')) {

            // Render answer
            $block = $this->getLayout()->createBlock('iboughtit/share');

            // Set products from order
            $block->setProducts($order_id);

            exit($block->toHtml());

        }


        // If product

        // Return 404
        $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
        $this->getResponse()->setHeader('Status', '404 File not found');
        $pageId = Mage::getStoreConfig('web/default/cms_no_route');
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultNoRoute');
        }
    }

    /**
     * Make coupon Action
     */
    public function makeCouponAction()
    {
        if($this->checkApiKey()){
            $order_id = Mage::app()->getRequest()->getParam('order_id');
            $campaign = Mage::app()->getRequest()->getParam('campaign_data');
            // Get order data
            $order = Mage::getModel('sales/order')->load($order_id);

            if($order_id && $campaign && $order){
                // Get API
                $helper = Mage::helper('iboughtit');



                // Import json
                $campaign = json_decode($campaign); // var_dump($campaign); exit();

                // Check status onetime discount
                if($campaign->discount_onetime_status){

                    // Get discount product
                    $products = unserialize($campaign->discount_onetime_products);


                    $productsCollection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToFilter('entity_id', array('in' => $products));

                    $skus = '';

                    $count = $productsCollection->count();
                    $i = 1;
                    foreach($productsCollection as $product) {
                        $skus .= $product->getSku().($i < $count ? ',' : '');
                        $i++;
                    }


                    // Make coupon
                    $helper->createCoupon($order->getCustomerId(), '10', $skus);
                    //var_dump($products);
                    //echo('Set discount_onetime');


                }

                echo 'true';

            }

        }else{
            // Return 404
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $pageId = Mage::getStoreConfig('web/default/cms_no_route');
            if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
                $this->_forward('defaultNoRoute');
            }
        }
    }

    /**
     * Check API Key
     * @return bool
     */
    private function checkApiKey()
    {
        if (base64_decode(Mage::app()->getRequest()->getParam('apikey')) == Mage::getStoreConfig('svitla_iboughtit/general/apikey')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param bool $product_id
     */
    private function redirectToProduct($product_id = false)
    {
        if ($product_id) {
            // Get product url
            if ($product = Mage::getModel('catalog/product')->load($product_id)) {
                // Redirect to product page
                header("Location: " . $product->getProductUrl($product));
            } else {
                $this->redirectToProduct();
            }
        } else {
            header("Location: " . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));
        }
    }

    /**
     * @return mixed
     */
    private function milliseconds()
    {
        $mt = explode(' ', microtime());
        return $mt[1] * 1000 + round($mt[0] * 1000);
    }


}