<?php

class Biztech_Christmasfestive_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function currentSeason(){
        $seasons = array(
            0 => 'winter',
            1 => 'spring',
            2 => 'summer',
            3 => 'autumn'
        );
        return $seasons[floor(date('n') / 3) % 4];
    }
    public function isActiveSeason(){
        if(Mage::getStoreConfig('christmasfestive/generalconfig/active')) {
            if (Mage::getStoreConfig('christmasfestive/' . $this->currentSeason() . '/active')) {
                return true;
            }
        }
        return false;
    }
    public function getPathLogo(){
        return 'christmasfestive/season/' . Mage::getStoreConfig('christmasfestive/'. $this->currentSeason() .'/uploadbg_file');
    }

}