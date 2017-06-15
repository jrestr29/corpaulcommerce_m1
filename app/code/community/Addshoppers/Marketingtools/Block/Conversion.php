<?php

/**
 * Addshoppers_Marketingtools
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Code
 * @package     Addshoppers_Marketingtools
 * @copyright   Copyright © 2016 AddShoppers.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Conversion track code block
 *
 * @package Addshoppers_Marketingtools
 * @copyright Copyright © 2016 AddShoppers.com
 * 
 */
class Addshoppers_Marketingtools_Block_Conversion extends Addshoppers_Marketingtools_Block_Abstract
{
    /**
     * Stores the order ID for the conversion block
     *
     * @var int
     */
    private $_orderId;

    /**
     * Grabs the order ID of the previously placed order.
     *
     * @return int
     * @copyright Copyright © 2016 AddShoppers.com
     * 
     */
    public function getOrderId()
    {
        if($this->_orderId == null) {
            $this->_orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        }
        return $this->_orderId;
    }

    /**
     * Gets the amount of the last placed order.
     *
     * @return float
     * @copyright Copyright © 2016 AddShoppers.com
     * 
     */
    public function getAmount()
    {
        return round(Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId())->subtotal, 2);
    }

    public function getSellShareSection()
    {
        if($this->config->getSalesSharingEnabled()) {
            return $this->getPopupScript();
        }
    }

    private function getPopupScript()
    {
        $html = <<<HTML
<script type="text/javascript">
    AddShoppersTracking = {
        auto: true,
        header: "{$this->getHeaderOption()}",
        image: "{$this->getImageOption()}",
        url: "{$this->getUrlOption()}",
        name: "{$this->getNameOption()}",
        description: "{$this->getDescOption()}"
    }
</script>
HTML;
        return $html;
    }
    
    private function getHeaderOption()
    {
        return $this->config->getPopupTitle();
    }

    private function getImageOption()
    {
        return $this->config->getShareImage();
    }

    private function getUrlOption()
    {
        return $this->config->getShareUrl();
    }

    private function getNameOption()
    {
        return $this->config->getShareTitle();
    }

    private function getDescOption()
    {
        return $this->config->getShareDescription();
    }
}
