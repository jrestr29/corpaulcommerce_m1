<?php

class Ditosas_CoordinadoraCourrier_Helper_Data extends Mage_Core_Helper_Abstract{

    public function checkDefinedShippingCost($country_id, $region_id, $city){
        $model = Mage::getModel('ditosas_coordinadoracourrier/costos')->getCollection()
            ->addFieldToFilter('country_id', array('eq' => $country_id))
            ->addFieldToFilter('region_id', array('eq' => $region_id))
            ->addFieldToFilter('city_id', array('eq' => $city))
            ->load()
            ->getData();


        if(sizeof($model)>0)
            return $model[0]['cost'];
        else
            return false;
    }
}