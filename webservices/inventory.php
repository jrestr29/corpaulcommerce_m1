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
curl_setopt($ch, CURLOPT_URL, "http://190.14.230.242:60020/ServicioMovilDITO_v2/ServicioMovilDITO.svc/GetInventario/0040000002");
$result=curl_exec($ch);
curl_close($ch);

$inventory = json_decode($result);
$fail = 0;
$successfull = 0;

if(ENABLE_LOG)
    Mage::log('----Execution date '.date('d/m/Y H:i:s'),null,'webservices-inventory');

foreach($inventory->Inventario as $inventario) {
    $sku = $inventario->Codigo;
    $qty = $inventario->Cantidad;

    $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);

    if(is_null($product) ||  !$product){
        $fail++;
        continue;
    }

    $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());

    $stockItem->setData('qty',$qty);
    $stockItem->save();
    $product->save();

    $product->setPrice($productPrice)
        ->save();

    $successfull++;
}

if(ENABLE_LOG){
    Mage::log($successfull.' products where updated',null,'webservices-inventory');
    Mage::log($fail.' products where not updated',null,'webservices-inventory');
}