<?php   
class Svitla_Iboughtit_Block_Server_General_Connect extends Svitla_Iboughtit_Block_Server{

    /**
     * @return json|string
     */
    public function getJson(){

        // Get module version
        $ModVersions = (array)Mage::getConfig()->getModuleConfig("Svitla_Iboughtit")->version;


        // Get Magento version
        $SystemVersion = Mage::getVersion();

        return json_encode(array(
                'MyName' => 'Magento',
                'ShopName' => Mage::app()->getStore()->getName(),
                'SystemVersion' => $SystemVersion,
                'ModuleVersion' => $ModVersions['0'],
                'SoapLogin' => $this->getSoapLogin(),
                'SoapKey' => $this->getSoapApiKey()
        ));
    }

}