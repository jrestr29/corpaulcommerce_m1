<?php
ini_set('max_execution_time', 3600);
ini_set('memory_limit','768M');
error_reporting(E_ALL);
define('MAGENTO', realpath(dirname(__FILE__)));
define('ENABLE_LOG',true);
require_once MAGENTO . '/../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); //Init magento

//Check if WS is requested through browser
$browser = (isset($_GET['prtbwsr'])) ? true : false;


//First query webservice inventory
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
    Mage::log('----Execution date '.date('d/m/Y H:i:s'),null,'webservices-inventory.log');

if($browser){
    echo '<br>----Execution date '.date('d/m/Y H:i:s');
    flush();
    ob_flush();
}

foreach($inventory->Inventario as $inventario) {
    $sku = $inventario->Codigo;
    $qty = $inventario->Cantidad;

    $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);

    if(is_null($product) ||  !$product){
        $fail++;

        if($browser){
            echo '<br>Product with sku '.$sku.' not found';
            flush();
            ob_flush();
        }

        continue;
    }

    $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());

    $stockItem->setData('qty',$qty);
    $stockItem->save();
    $product->save();

    $product->setPrice($productPrice)
        ->save();

    if($browser){
        echo '<br>Inventory of product with sku '.$sku.' has been updated to '.$qty;
        flush();
        ob_flush();
    }

    $successfull++;
}

if(ENABLE_LOG){
    Mage::log($successfull.' products where updated',null,'webservices-inventory.log');
    Mage::log($fail.' products where not updated',null,'webservices-inventory.log');
}

if($browser){
    echo '<br><br>------------------------------------------------';
    echo '<br>Execution finished at '.date('d/m/Y H:i:s');
    echo '<br>'.$successfull.' products where updated';
    echo '<br>'.$fail.' products where not updated';
    flush();
    ob_flush();
}