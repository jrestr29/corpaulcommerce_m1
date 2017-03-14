<?php

class Ditosas_CoordinadoraCourrier_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'ditosas_coordinadoracourrier';

    /**
     * Collect and get rates
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result|bool|null
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');
        $result->append($this->_getRate());

        return $result;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array(
            'ditosas_fedex' => $this->getConfigData('name'),
        );
    }

    protected function _getRate() {
        $address = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
        $checkCost = Mage::helper('ditosas_coordinadoracourrier')->checkDefinedShippingCost($address->getCountryId(), $address->getRegion(), $address->getCity());

        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod($this->_code);
        $rate->setMethodTitle($this->getConfigData('name'));

        if(!$checkCost){
            $rate->setPrice($this->getConfigData('price'));
        } else {
            $rate->setPrice($checkCost);
        }

        $rate->setCost(0);

        return $rate;
    }
}