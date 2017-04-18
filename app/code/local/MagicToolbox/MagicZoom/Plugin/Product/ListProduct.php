<?php

namespace MagicToolbox\MagicZoom\Plugin\Product;

/**
 * Plugin for \Magento\Catalog\Block\Product\ListProduct
 */
class ListProduct
{
    /**
     * Helper
     *
     * @var \MagicToolbox\MagicZoom\Helper\Data
     */
    protected $magicToolboxHelper = null;

    /**
     * MagicZoom module core class
     *
     * @var \MagicToolbox\MagicZoom\Classes\MagicZoomModuleCoreClass
     *
     */
    protected $toolObj = null;

    /**
     * Html data
     * @var array
     */
    protected $mtHtmlData = [];

    /**
     * Disable flag
     * @var bool
     */
    protected $isDisabled = true;

    /**
     * @param \MagicToolbox\MagicZoom\Helper\Data $magicToolboxHelper
     */
    public function __construct(
        \MagicToolbox\MagicZoom\Helper\Data $magicToolboxHelper
    ) {
        $this->magicToolboxHelper = $magicToolboxHelper;
        $this->toolObj = $this->magicToolboxHelper->getToolObj();
        $this->toolObj->params->setProfile('category');
        $this->isDisabled = !$this->toolObj->params->checkValue('enable-effect', 'Yes', 'category');
    }

    /**
     * Retrieve loaded category collection
     *
     * @param \Magento\Catalog\Block\Product\ListProduct $listProductBlock
     * @param \Magento\Eav\Model\Entity\Collection\AbstractCollection $productCollection
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection
     */
    public function afterGetLoadedProductCollection(\Magento\Catalog\Block\Product\ListProduct $listProductBlock, $productCollection)
    {
        if ($this->isDisabled) {
            return $productCollection;
        }
        $this->magicToolboxHelper->setListProductBlock($listProductBlock);
        foreach ($productCollection as $product) {
            $id = $product->getId();
            if (!isset($this->mtHtmlData[$id])) {
                $this->mtHtmlData[$id] = $this->magicToolboxHelper->getHtmlData($product);
            }
        }
        return $productCollection;
    }

    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Block\Product\ListProduct $listProductBlock
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return array
     */
    public function beforeGetImage(\Magento\Catalog\Block\Product\ListProduct $listProductBlock, $product, $imageId, $attributes = [])
    {
        if (!$this->isDisabled) {
            $attributes['data-magiczoom'] = $product->getId();
        }
        return [
            $product, $imageId, $attributes
        ];
    }

    /**
     * Produce and return block's html output
     *
     * @param \Magento\Catalog\Block\Product\ListProduct $listProductBlock
     * @param string $html
     * @return string
     */
    public function afterToHtml(\Magento\Catalog\Block\Product\ListProduct $listProductBlock, $html)
    {
        if ($this->isDisabled) {
            return $html;
        }
        $patternBegin = '<a(?=\s|>)[^>]*+>[^<]*+'.
            '<span(?=\s|>)[^>]*+>[^<]*+'.
            '<span(?=\s|>)[^>]*+>[^<]*+'.
            '<img(?=\s)[^>]+?(?<=\s)data\-magiczoom="';
        $patternEnd = '"[^>]++>[^<]*+'.
            '</span>[^<]*+'.
            '</span>[^<]*+'.
            '</a>';
        foreach ($this->mtHtmlData as $id => $_html) {
            if (empty($_html)) {
                continue;
            }
            $html = preg_replace("#{$patternBegin}{$id}{$patternEnd}#", $_html, $html);
        }

        return $html;
    }
}
