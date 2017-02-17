<?php

class Ditosas_FedexCourrier_Model_Resource_Costos extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('ditosas_fedexcourrier/costos', 'costo_id');
    }
}