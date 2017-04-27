<?php

class Ditosas_CoordinadoraCourrier_Helper_Data extends Mage_Core_Helper_Abstract{

    public function checkDefinedShippingCost($country_id, $region, $city){
        if(!$region == "" && !is_null($region)) {
            $region = Mage::getModel('directory/region')->getCollection()
                ->addFieldToFilter('country_id', array('eq' => $country_id))
                ->addFieldToFilter('default_name', array('eq' => $region))
                ->getFirstItem();

            $region_id = $region->getId();
            $city = Mage::getModel('ditosas_citydropdown/city')->getCollection()
                ->addFieldToFilter('region_id', array('eq' => $region_id))
                ->addFieldToFilter('name', array('eq' => $city))
                ->getFirstItem();
        } else {
            $city = Mage::getModel('ditosas_citydropdown/city')->getCollection()
                ->addFieldToFilter('name', array('eq' => $city))
                ->getFirstItem();
        }

        if(!is_null($region) && ($region !="")) {
            $model = Mage::getModel('ditosas_coordinadoracourrier/costos')->getCollection()
                ->addFieldToFilter('country_id', array('eq' => $country_id))
                ->addFieldToFilter('region_id', array('eq' => $region_id))
                ->addFieldToFilter('city_id', array('eq' => $city->getId()))
                ->getFirstItem();
        } else {
            $model = Mage::getModel('ditosas_coordinadoracourrier/costos')->getCollection()
                ->addFieldToFilter('country_id', array('eq' => $country_id))
                ->addFieldToFilter('city_id', array('eq' => $city->getId()))
                ->getFirstItem();
        }

        if($model)
            return $model->getCost();
        else
            return false;
    }
}