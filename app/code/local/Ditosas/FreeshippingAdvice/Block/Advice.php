<?php
class Ditosas_FreeshippingAdvice_Block_Advice extends Mage_Core_Block_Template {

    protected $_freeValue = "65000";

    public function isFreeShipping() {
        if ((int)$this->_freeValue <= $this->_getItemsTotal()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAdviceClass() {
        if ((int)$this->_freeValue <= $this->_getItemsTotal()) {
            return 'free';
        } else {
            return 'remaining';
        }
    }

    public function getRemainingValue() {
        return ($this->_freeValue - $this->_getItemsTotal());
    }

    protected function _getItemsTotal() {
        $cartTotal = 0;
        $cart = Mage::getModel('checkout/cart')->getQuote();

        foreach ($cart->getAllItems() as $item) {
            $cartTotal += $item->getProduct()->getFinalPrice()*$item->getProduct()->getQty();
        }

        return $cartTotal;
    }
}
