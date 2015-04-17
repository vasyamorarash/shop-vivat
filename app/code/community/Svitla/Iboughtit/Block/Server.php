<?php
abstract class Svitla_Iboughtit_Block_Server extends Mage_Core_Block_Template{

    /**
     * SOAP Access
     * @var array
     */
    private $soap = array(
        'login'=> '',
        'api_key' => ''
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTemplate('svitla/iboughtit/json.phtml');
    }

    /**
     * @param $login
     */
    public function setSoapLogin($login)
    {
        $this->soap['login'] = $login;
    }

    /**
     * @param $key
     */
    public function setSoapApiKey($key)
    {
        $this->soap['api_key'] = $key;
    }

    /**
     * @param $login
     */
    public function getSoapLogin()
    {
        return $this->soap['login'];
    }

    /**
     * @param $key
     */
    public function getSoapApiKey()
    {
        return $this->soap['api_key'];
    }

    /**
     * @return json|string
     */
    abstract public function getJson();

}