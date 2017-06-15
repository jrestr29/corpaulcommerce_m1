<?php
class Ditosas_FedexCourrier_Model_Resource_Costos_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('ditosas_fedexcourrier/costos');
    }
}