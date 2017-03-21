<?php
class Ditosas_BillingService_Model_Observer {

    public function billing ($observer) {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        $json = [];
        $json['NuMFactura'] = $invoice->getIncrementId();
        $json['CodAsesor'] = 'N/A';
        $json['CodCliente'] = $order->getCustomerName();
        $json['Fc'] = $invoice->getCreatedAt();
        $json['Tp'] = 'N/A';
        $json['Fnt'] = 8;
        $json['detalle'] = array();

        foreach($invoice->getAllItems() as $item){
            $child = array(
                'CodArt' => $item->getSku(),
                'Cant' => $item->getQty(),
                'UM' => "UN"
            );

            array_push($json['detalle'], $child);
        }

        $data_string = json_encode($json);

        $ch = curl_init('http://190.14.230.242:60020/ServicioMovilDITO_v2/ServicioMovilDITO.svc/IngresarFactura');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        $result = json_decode($result);

        if($result->MsgID=="-1"){
            Mage::log(date('d/m/Y H:i:s').': Bill #'.$invoice->getIncrementId().' Error: '.$result->MsgStr,null,'ws-billing.log');
        } else {
            Mage::log(date('d/m/Y H:i:s').': Bill #'.$invoice->getIncrementId().' created successfully',null,'ws-billing.log');
        }
    }
}