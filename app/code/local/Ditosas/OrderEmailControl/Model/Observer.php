<?php
class Ditosas_OrderEmailControl_Model_Observer 
{
	public function processEmail(Varien_Event_Observer $observer)
	{
		$orderIds = $observer->getData('order_ids');

		foreach($orderIds as $_orderId)
		{
			$order = Mage::getModel('sales/order')->load($_orderId);
			$invoiceCollection = $order->getInvoiceCollection();

			try {
				foreach($invoiceCollection as $invoice) { 
				    $invoice->sendEmail(true,'');
				 }

				$order->getSendConfirmation(null);
				$order->sendNewOrderEmail();
			} catch(Exception $e) {
				var_dump($e->getMessage());
			}
		}
	}
}