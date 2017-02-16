<?php

class Ditosas_CityDropdown_Model_Resource_City extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('ditosas_citydropdown/city', 'city_id');
    }
}