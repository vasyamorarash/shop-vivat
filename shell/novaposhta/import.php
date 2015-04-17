<?php
require_once '../abstract.php';

/**
 * Shell script for import warehouses and cities
 *
 * Class Mage_Shell_Novaposhta_Import
 */
class Mage_Shell_Novaposhta_Import
    extends Mage_Shell_Abstract
{
    /**
     * Import warehouses and cities
     *
     * @return $this
     */
    public function run()
    {
        if($import = Mage::getModel('novaposhta/import')){
            $import->run();
        }else{
            exit("Module Novaposhta is turn off!\n");
        }



        return $this;
    }
}

$shell = new Mage_Shell_Novaposhta_Import();


$shell->run();
