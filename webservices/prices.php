<?php
error_reporting(E_ALL);
define('MAGENTO', realpath(dirname(__FILE__)));
define('ENABLE_LOG',true);
require_once MAGENTO . '/../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); //Init magento

//First query webservice prices
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "http://190.14.230.242:60020/ServicioMovilDITO_v2/ServicioMovilDITO.svc/GetPrecios");
$result=curl_exec($ch);
curl_close($ch);

$prices = json_decode($result);

if(ENABLE_LOG)
    Mage::log('----Execution date '.date('d/m/Y H:i:s'),null,'webservices-price');

foreach($prices->Precios as $precio) {
    $sku = $precio->CodPro;
    $productPrice = $precio->PV;

    $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);

    if(is_null($product) ||  !$product){
        if(ENABLE_LOG)
            Mage::log('Product sku '.$sku.' not found on Magento',null,'webservices-price');

        continue;
    }


    $product->setPrice($productPrice)
        ->save();

    if(ENABLE_LOG)
        Mage::log('Product sku '.$sku.' price updated to $'.$productPrice,null,'webservices-price');
}