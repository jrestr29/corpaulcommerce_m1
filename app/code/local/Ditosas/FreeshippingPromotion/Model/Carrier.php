<?php

class Ditosas_FreeshippingPromotion_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'ditosas_freeshippingpromotion';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');

        $rate = $this->_getRate($request->getBaseSubtotalInclTax());

        if(!$rate){
            return $this;
        }

        $result->append($rate);

        return $result;
    }

    public function getAllowedMethods()
    {
        return array(
            'ditosas_freeshipping' => $this->getConfigData('name'),
        );
    }

    protected function _getRate($subtotal) {
        $address = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
        $checkCost = Mage::helper('ditosas_coordinadoracourrier')->checkDefinedShippingCost($address->getCountryId(), $address->getRegion(), $address->getCity());

        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($this->_code);
        $rate->setMethodTitle($this->getConfigData('name'));
        $rate->setCost(0);

        if(Mage::helper('ditosas_freeshippingpromotion')->checkAppliesPromotion($subtotal,$checkCost)){
            $rate->setPrice(0);
        } else {
            return false;
        }

        return $rate;
    }
}