<?php
class Ditosas_BillingService_Model_Observer {

    public function billing ($observer) {
        $invoice = $observer->getEvent()->getInvoice();
    }

}