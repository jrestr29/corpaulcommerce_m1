<?php
/**
 * Common methods for all blocks
 * 
 * Copyright © 2016 AddShoppers.com
 * 
 * */
class Addshoppers_Marketingtools_Block_Abstract extends Mage_Core_Block_Template
{
    /**
     *
     * @var Addshoppers_Marketingtools_Helper_Config 
     */
    protected $config;
    
    public function __construct()
    {
        $this->config = new Addshoppers_Marketingtools_Helper_Config();
    }

    /**
     * Returns the store account ID
     *
     * @return string AddShoppers Account ID
     */
    public function getAccountId()
    {
        return $this->config->getApiKey();
    }

    /**
     * Returns shop ID
     * 
     * @return string AddShoppers Shop ID
     */
    public function getShopId()
    {
        return $this->config->getShopId();
    }
}