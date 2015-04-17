<?php
class Svitla_Iboughtit_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $soapPermissions = array(
        '__root__',
        'catalog',
        'catalog/product',
        'catalog/product/downloadable_link',
        'catalog/product/downloadable_link/list'
    );

    public function getSoapCustomer($username)
    {
        return Mage::getModel('api/user')->loadByUsername($username);
    }

    /**
     * Create SOAP customer
     * @return array
     */
    public function addSoapCustomer($login, $pass, $data = array())
    {
        // Is customer?
        if($this->getSoapCustomer($login)){
            return false;
        }

        // Check $data
        $data['email'] = isset($data['email']) ? $data['email'] : 'no-email@no.no';
        $data['lastname'] = isset($data['lastname']) ? $data['lastname'] : 'No name';
        $data['firstname'] = isset($data['firstname']) ? $data['firstname'] : 'No name';

        try {

            // Add SOAP role
            $role = Mage::getModel('api/roles')
                ->setName('iboughtit')
                ->setPid(false)
                ->setRoleType('G')
                ->save();

            // Set SOAP rules
            Mage::getModel("api/rules")
                ->setRoleId($role->getId())
                ->setResources($this->soapPermissions)
                ->saveRel();

            // Add SOAP customer
            $user = Mage::getModel('api/user');

            $user->setData(array(
                'username' => $login,
                'lastname' => $data['lastname'],
                'firstname' => $data['firstname'],
                'email' => $data['email'],
                'api_key' => $pass,
                'api_key_confirmation' => $pass,
                'is_active' => 1,
                'user_roles' => '',
                'assigned_user_role' => '',
                'role_name' => '',
                'roles' => array($role->getId())
            ));

            $user->save()->load($user->getId());

            $user->setRoleIds(array($role->getId()))
                ->setRoleUserId($user->getUserId())
                ->saveRelations();


            // Return SOAP customer data
            return $user;

        } catch (Mage_Core_Exception $e) {
            Mage::log($e->getMessage());
        } catch (Exception $e) {
            Mage::log(Mage::helper('iboughtit')->__('An error occurred while saving iboughtit role.'));
        }

        return false;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Catalog_Model_Category $category
     * @param bool $mustBeIncludedInNavigation
     * @return string
     */
    public function getProductFullUrl (Mage_Catalog_Model_Product $product ,
                                       Mage_Catalog_Model_Category $category = null ,
                                       $mustBeIncludedInNavigation = true ){

        // Try to find url matching provided category
        if( $category != null){
            // Category is no match then we'll try to find some other category later
            if( !in_array($product->getId() , $category->getProductCollection()->getAllIds() )
                ||  !self::isCategoryAcceptable($category , $mustBeIncludedInNavigation )){
                $category = null;
            }
        }
        if ($category == null) {
            if( is_null($product->getCategoryIds() )){
                return $product->getProductUrl();
            }
            $catCount = 0;
            $productCategories = $product->getCategoryIds();
            // Go through all product's categories
            while( $catCount < count($productCategories) && $category == null ) {
                $tmpCategory = Mage::getModel('catalog/category')->load($productCategories[$catCount]);
                // See if category fits (active, url key, included in menu)
                if ( !self::isCategoryAcceptable($tmpCategory , $mustBeIncludedInNavigation ) ) {
                    $catCount++;
                }else{
                    $category = Mage::getModel('catalog/category')->load($productCategories[$catCount]);
                }
            }
        }
        $url = (!is_null( $product->getUrlPath($category))) ?  Mage::getBaseUrl() . $product->getUrlPath($category) : $product->getProductUrl();
        return $url;
    }

    /**
     * Checks if a category matches criteria: active && url_key not null && included in menu if it has to
     */
    protected static function isCategoryAcceptable(Mage_Catalog_Model_Category $category = null, $mustBeIncludedInNavigation = true){
        if( !$category->getIsActive() || is_null( $category->getUrlKey() )
            || ( $mustBeIncludedInNavigation && !$category->getIncludeInMenu()) ){
            return false;
        }
        return true;
    }

    public function createCoupon($customer_id, $discount, $skus = false, $condition = '==')
    {
        $customer = Mage::getModel('customer/customer')->load($customer_id);

        $customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
        $websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();

        $customer_name = $customer->getName();
        $couponCode = Mage::helper('core')->getRandomString(9);

        $model = Mage::getModel('salesrule/rule');
        $model->setName('Discount for ' . $customer_name);
        $model->setDescription('Discount for ' . $customer_name);
        $model->setFromDate(date('Y-m-d'));
        $model->setToDate(date('Y-m-d', strtotime('+2 days')));
        $model->setCouponType(2);
        $model->setCouponCode($couponCode);
        $model->setUsesPerCoupon(1);
        $model->setUsesPerCustomer(1);
        $model->setCustomerGroupIds($customerGroupIds);
        $model->setIsActive(1);

        // Products list
        if($skus){
            $model->setConditionsSerialized($this->makeConditions($skus, $condition));
        }else{
            $model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
        }

        $model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
        $model->setStopRulesProcessing(0);
        $model->setIsAdvanced(1);
        $model->setProductIds('');
        $model->setSortOrder(1);
        $model->setSimpleAction('by_fixed');
        $model->setDiscountAmount($discount);
        $model->setDiscountStep(0);
        $model->setSimpleFreeShipping(0);
        $model->setTimesUsed(0);
        $model->setIsRss(0);
        $model->setWebsiteIds($websitesId);

        try {
            $model->save();
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }

        // Send coupon code to customer email
        try {
            // Get store data
            $store = Mage::app()->getStore();

            //load the custom template to the email
            $emailTemplate = Mage::getModel('core/email_template')
                ->loadDefault('coupon_new');

            // it depends on the template variables
            $emailTemplateVariables = array();
            $emailTemplateVariables['email_message'] = Mage::getStoreConfig('svitla_iboughtit/email/email_message');
            $emailTemplateVariables['customer_name'] = $customer_name;
            $emailTemplateVariables['coupon_code'] = $couponCode;


            $emailTemplate->setSenderName($store->getName());
            $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_sales/email'));
            $emailTemplate->setType('html');
            $emailTemplate->setTemplateSubject($this->__('Your discount coupon'));
            $emailTemplate->send($customer->getEmail(), $customer->getFirstname() . $customer->getLastname(), $emailTemplateVariables);
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

    function makeConditions($skus, $condition = '=='){
         return serialize(
             [
                 "type"=> "salesrule/rule_condition_combine",
                 "attribute"=>  NULL,
                 "operator"=>  NULL,
                 "value"=> "1",
                 "is_value_processed"=>  NULL,
                 "aggregator"=> "all",
                 ["conditions"]=> [
                     '0' => [
                         "type"=>  "salesrule/rule_condition_product_found",
                         "attribute"=> NULL,
                         "operator"=> NULL,
                         "value"=> "1",
                         "is_value_processed"=> NULL,
                         "aggregator"=> "all",
                         ["conditions"]=> [
                             '0'=> [
                                 "type"=> "salesrule/rule_condition_product",
                                 "attribute"=> "sku",
                                 "operator"=> $condition,
                                 "value"=> $skus,
                                 "is_value_processed"=> false
                             ]
                         ]
                     ]
                 ]
             ]
         );
    }

    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, count($alphabet)-1);
            $pass[$i] = $alphabet[$n];
        }
        return $pass;
    }
}
	 