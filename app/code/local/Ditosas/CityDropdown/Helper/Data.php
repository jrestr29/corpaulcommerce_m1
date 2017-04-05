<?php

class Ditosas_CityDropdown_Helper_Data extends Mage_Core_Helper_Abstract{

    public function getCities($country_id, $region_id) {
        $results = Mage::getModel('ditosas_citydropdown/city')->getCollection()
            ->addFieldToFilter('region_id', array('eq' => $region_id))
            ->addFieldToFilter('country:id', array('eq' => $country_id))
            ->load()
            ->getData();

        $cities = array();

        if(sizeof($results) > 0){
            foreach($results as $city){
                $cityId  = $city['city_id'];
                $cityName = $city['name'];
                $cities[$cityId]  = $cityName;
            }
        }
        return $cities;
    }

    public function getCitiesAsDropdown($selectedCity = '',$country_id, $region_id)
    {
        $cities =  $this->getCities($country_id, $region_id);
        $options =  '';
        if(sizeof($cities) > 0){

            foreach($cities as $key => $city){
                $isSelected = $selectedCity == $city ? ' selected="selected"' : null;
                $options .= '<option value="' . $city . '"' . $isSelected . '>' . ucfirst(strtolower($city)) . '</option>';
            }
        }
        return $options;
    }

    public function getDepartamentosDropdown()
    {
        $results = Mage::getModel('directory/region')->getResourceCollection()
            ->addCountryFilter('CO')
            ->load()
            ->getData();

        $options =  '';

        foreach($results as $key => $dpto){
            $options .= '<option value="' . $dpto['default_name'] . '">' . ucfirst(strtolower($dpto['default_name'])) . '</option>';
        }

        return $options;
    }

    public function getCitiesJson()
    {
        $results = Mage::getModel('ditosas_citydropdown/city')->getCollection()
            ->addFieldToFilter('country_id', array('eq' => 'CO'))
            ->load()
            ->getData();

        $array = array();

        foreach($results as $key => $city){
            $dpto = Mage::getModel('directory/region')->load($city['region_id']);
            $array[] = ['dpto' => $dpto->getDefaultName(), 'city' => ucfirst(strtolower($city['name']))];
        }

        return json_encode($array);
    }

}