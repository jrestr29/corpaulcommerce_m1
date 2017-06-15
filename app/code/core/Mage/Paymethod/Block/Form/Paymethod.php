<?php
/**
 * Magento
 * Hace llamado a plantilla que carga el botón de pago, al elegir el método de pago
 * @author     Ejepe
 */
class Bcommerce_Paymethod_Block_Form_Paymethod extends Mage_Payment_Block_Form
{
	protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymethod/form/paymethod.phtml');
    }
}