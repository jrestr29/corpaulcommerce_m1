<?php
class Ditosas_ReasingUserGroup_Model_Observer
{

    public function reasing (Varien_Event_Observer $observer) {
        $orderId = $observer->getData('order_ids')[0];
        $order = Mage::getModel('sales/order')->load($orderId);
        $customer  = Mage::getModel('customer/customer')->load($order->getData('customer_id'));
        $customer->setData('group_id', 1);
        $customer->save();
    }

}