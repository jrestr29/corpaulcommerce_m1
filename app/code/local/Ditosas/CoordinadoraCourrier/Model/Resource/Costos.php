<?php

class Ditosas_CoordinadoraCourrier_Model_Resource_Costos extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('ditosas_coordinadoracourrier/costos', 'costo_id');
    }
}