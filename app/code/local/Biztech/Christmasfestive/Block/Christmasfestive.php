<?php

class Biztech_Christmasfestive_Block_Christmasfestive extends Mage_Core_Block_Template {

    public function getJsonOptions() {

        $min = Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_minsize') ? Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_minsize'): 10;
        $max = Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_maxsize') ? Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_maxsize'): 50;
        
        
        $options = new stdClass();
        $options->flakes = round(Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_number'));
        $options->color = explode(',', Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_colors'));
        $options->text = explode(',' , Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_text'));
        $options->speed = Mage::getStoreConfig('christmasfestive/snowflakesconfig/snowflakes_speed');
        $options->size = (object) array(
                    'min' => $min,
                    'max' => $max
        );

        return json_encode($options);
    }

    public function getSeason() {
        $helper = Mage::helper('christmasfestive');
        return $helper->currentSeason();
    }

    public function getLogoSeason(){
        return Mage::getStoreConfig('christmasfestive/'. $this->getSeason() .'/uploadbg_file');
    }

    public function isActive(){
        $helper = Mage::helper('christmasfestive');
        return $helper->isActiveSeason();
    }



}
