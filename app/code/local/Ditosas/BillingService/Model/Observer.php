<?php
class Ditosas_BillingService_Model_Observer {

    public function billing ($observer) {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();

        $json = [];
        $json['NumFactura'] = $invoice->getIncrementId();
        $json['CodAsesor'] = '1';
        $json['CodCliente'] = '1';
        $json['Fc'] = $invoice->getCreatedAt();
        $json['Tp'] = '1';
        $json['Fnt'] = '9';
        $json['detalle'] = array();

        foreach($invoice->getAllItems() as $item){
            $child = array(
                'CodArt' => $item->getSku(),
                'Cant' => $item->getQty(),
                'UM' => "UN"
            );

            array_push($json['detalle'], $child);
        }

        Mage::log(date('d/m/Y H:i:s')." Bill # ".$invoice->getIncrementId()."\n",null,'ws-billing.log');
        Mage::log(date('d/m/Y H:i:s')." SENT JSON: ".json_encode($json)."\n \n",null,'ws-billing.log');


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
        Mage::log("",null,"ws-billing.log");
        Mage::log(date('d/m/Y H:i:s')." RECEIVED JSON: ".$result." \n \n",null,'ws-billing.log');

        $result = json_decode($result);

        if($result->MsgID=="-1"){
            Mage::log(date('d/m/Y H:i:s').': Bill #'.$invoice->getIncrementId().' Error: '.$result->MsgStr,null,'ws-billing.log');
        } else {
            Mage::log(date('d/m/Y H:i:s').': Bill #'.$invoice->getIncrementId().' created successfully',null,'ws-billing.log');
        }

        Mage::log("\n ================================================ \n \n",null,'ws-billing.log');

    }
}