<?php
class Ditosas_CityDropdown_Model_Resource_City_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('ditosas_citydropdown/city');
    }
}