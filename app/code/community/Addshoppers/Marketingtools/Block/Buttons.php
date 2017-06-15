<?php

/**
 * Buttons block to display the buttons
 *
 * @copyright Copyright © 2016  AddShoppers.com
 * 
 */
class Addshoppers_Marketingtools_Block_Buttons extends Addshoppers_Marketingtools_Block_Abstract
{    
    /**
     * If enabled, return the button code.
     *
     * @return string HTML Code
     * @copyright Copyright © 2016 AddShoppers.com
     * 
     */
    public function _toHtml() {
        if ($this->config->getEnabled()){
            return $this->_getButtonsCode();
        }
    }
    
    private function _getButtonsCode()
    {
        if ($this->config->getSocialEnabled()) {
            return $this->config->getButtonsCode();
        }
    }
}
