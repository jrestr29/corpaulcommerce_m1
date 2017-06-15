<?php
/**
 * Copyright © 2016 AddShoppers.com
 * 
 * */
class Addshoppers_Marketingtools_Helper_Config
{
    private $storeCode;

    public function __construct($storeCode = null)
    {
        if (isset($storeCode)) {
            $this->storeCode = $storeCode;
        }
        else {
            $this->storeCode = null;
        }
    }

    public function setEnabled($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/enabled', $value);
    }
    
    public function getEnabled()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/enabled');
    }
    
    public function setUrl($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/url', $value);
    }
    
    public function getUrl()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/url');
    }
    
    public function setPlatform($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/platform', $value);
    }
    
    public function getPlatform()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/platform');
    }
    
    public function setActive($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/active', $value);
    }
    
    public function getActive()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/active');
    }
    
    public function setEmail($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/email', $value);
    }
    
    public function getEmail()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/email');
    }
    
    public function setPassword($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/password', $value);
    }
    
    public function getPassword()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/password');
    }
    
    public function setPhone($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/phone', $value);
    }
    
    public function getPhone()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/phone');
    }
    
    public function setCategory($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/category', $value);
    }
    
    public function getCategory()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/category');
    }
    
    public function setApiKey($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/account_id', $value);
    }
    
    public function getApiKey()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/account_id');
    }

    public function setApiSecret($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/api_secret', $value);
    }
    
    public function getApiSecret()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/api_secret');
    }
    
    public function setShopId($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/shopid', $value);
    }
    
    public function getShopId()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/shopid');
    }
    
    public function setSchemaEnabled($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/use_schema', $value);
    }
    
    public function getSchemaEnabled()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/use_schema');
    }
    
    public function setSocialEnabled($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/social', $value);
    }
    
    public function getSocialEnabled()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/social');
    }
    
    public function setOpenGraphEnabled($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/opengraph', $value);
    }
    
    public function getOpenGraphEnabled()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/opengraph');
    }
    
    public function setSalesSharingEnabled($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/sales_sharing_enable', $value);
    }
    
    public function getSalesSharingEnabled()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/sales_sharing_enable');
    }
    
    public function setPopupTitle($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/popup_title', $value);
    }
    
    public function getPopupTitle()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/popup_title');
    }
    
    public function setShareImage($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/image_share', $value);
    }
    
    public function getShareImage()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/image_share');
    }
    
    public function setShareUrl($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/url_share', $value);
    }
    
    public function getShareUrl()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/url_share');
    }
    
    public function setShareTitle($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/title_share', $value);
    }
    
    public function getShareTitle()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/title_share');
    }
    
    public function setShareDescription($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/description_share', $value);
    }
    
    public function getShareDescription()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/description_share');
    }
    
    public function setButtonsCode($value)
    {
        $this->saveConfigForStore('addshoppers_marketingtools/settings/button_code', $value);
    }
    
    public function getButtonsCode()
    {
        return $this->getConfigForStore('addshoppers_marketingtools/settings/button_code');
    }

    private function getConfigForStore($path)
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $storeId = ($this->storeCode != null) ? Mage::app()->getStore($this->storeCode)->getId() : Mage::app()->getStore()->getId();

        $query = "SELECT value FROM " . $resource->getTableName('core_config_data') . " WHERE scope_id = '" . $storeId . "' AND path = '" . $path . "'";

        $results = $readConnection->fetchCol($query);

        if(count($results) == 0) {
            $query = "SELECT value FROM " . $resource->getTableName('core_config_data') . " WHERE scope_id = '0' AND path = '" . $path . "'";
            $results = $readConnection->fetchCol($query);
        }

        return isset($results[0]) ? $results[0] : "";
    }

    private function saveConfigForStore($path, $value)
    {
        $configModel = Mage::getResourceModel('core/config');
        $storeId = ($this->storeCode == 'default') ? 0 : Mage::app()->getStore($this->storeCode)->getId();
        $scope = ($this->storeCode != 'default' && $storeId != 0) ? 'stores' : 'default';
        $configModel->saveConfig($path, $value, $scope, $storeId);
    }
}
