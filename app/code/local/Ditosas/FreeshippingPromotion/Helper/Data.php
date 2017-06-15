<?php

class Ditosas_FreeshippingPromotion_Helper_Data extends Mage_Core_Helper_Abstract{

    public function checkAppliesPromotion($orderTotal, $isLocal)
    {
        if(!$isLocal){ //Si no es area metropolitana
            if($orderTotal <= "250000"){
                return false;
            } else {
                return true;
            }
        } else { // Si es area metropolitana
            if($orderTotal <= "65000"){
                return false;
            } else {
                return true;
            }
        }
    }

}