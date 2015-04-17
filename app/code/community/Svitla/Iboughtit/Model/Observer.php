<?php

class Svitla_Iboughtit_Model_Observer
{

    public function productDeleteBefore(Varien_Event_Observer $observer)
    {
        //Mage::dispatchEvent('admin_session_user_login_success', array('user'=>$user));
        //$user = $observer->getEvent()->getUser();
        //$user->doSomething();
    }

    public function orderSuccess()
    {

    }

}
