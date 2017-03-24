<?php
ini_set('max_execution_time', 3600);
ini_set('memory_limit','768M');
error_reporting(E_ALL);
define('MAGENTO', realpath(dirname(__FILE__)));
define('ENABLE_LOG',true);
require_once MAGENTO . '/../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); //Init magento

//Check if WS is requested through browser
$browser = ($_GET['prtbwsr']) ? true : false;

//First query webservice prices
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "http://190.14.230.242:60020/ServicioMovilDITO_v2/ServicioMovilDITO.svc/GetPrecios");
$result=curl_exec($ch);
curl_close($ch);

$prices = json_decode($result);

if(ENABLE_LOG)
    Mage::log('----Execution date '.date('d/m/Y H:i:s'),null,'webservices-price.log');

if($browser){
    echo '<br>----Execution date '.date('d/m/Y H:i:s');
    flush();
    ob_flush();
}

foreach($prices->Precios as $precio) {
    $sku = $precio->CodPro;
    $productPrice = $precio->PVN;

    //$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
    $product = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('price')
                ->addAttributeToFilter('sku',$sku);

    if($product->count()==0){
        if(ENABLE_LOG)
            Mage::log('Product sku '.$sku.' not found on Magento',null,'webservices-price.log');

        if($browser){
            echo '<br>Product sku '.$sku.' not found on Magento';
            flush();
            ob_flush();
        }

        continue;
    }

    $product = $product->getFirstItem();

    $product->setPrice($productPrice)
        ->save();

    if(ENABLE_LOG)
        Mage::log('Product sku '.$sku.' price updated to $'.$productPrice,null,'webservices-price.log');

    if($browser){
        echo '<br>Product sku '.$sku.' price updated to $'.$productPrice;
        flush();
        ob_flush();
    }
}

if($browser){
    echo '<br>Execution finished at '.date('d/m/Y H:i:s');
    flush();
    ob_flush();
}