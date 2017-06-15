<?php
/**
 * Copyright © 2016 AddShoppers.com
 * @author Santiago Moreno <smoreno91@gmail.com>
 * 
 * */
class Addshoppers_Marketingtools_Block_Adminhtml_System_Config_Fieldset_Info 
	extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

		protected $_template = 'addshoppers_marketingtools/system/config/fieldset/info.phtml';

		public function render(Varien_Data_Form_Element_Abstract $element) {
        	return $this->toHtml();
    	}  	
}