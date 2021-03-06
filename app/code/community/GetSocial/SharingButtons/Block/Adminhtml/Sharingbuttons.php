<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    GetSocial
 * @package     GetSocial_SharingButtons
 * @copyright   Copyright (c) 2015 GetSocial (http://getsocial.io/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php

class GetSocial_SharingButtons_Block_Adminhtml_SharingButtons extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getSharingButtons()     
    {
        if (!$this->hasData('sharingbuttons')) {
            $this->setData('sharingbuttons', Mage::registry('sharingbuttons'));
        }
        return $this->getData('sharingbuttons');
    }
}
