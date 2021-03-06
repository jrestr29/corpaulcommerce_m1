<?php
	ini_set('max_execution_time', 7200);
	ini_set('memory_limit','1024M');
	error_reporting(E_ALL);
	define('MAGENTO', realpath(dirname(__FILE__)));
	define('ENABLE_LOG',true);

	require_once MAGENTO . '/../app/Mage.php';
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); //Init magento

	//Check if WS is requested through browser
	$browser = (isset($_GET['prtbwsr'])) ? true : false;
	$folder = MAGENTO . '/../var/log/' . date('d-m-Y');
	$execTime = date('H-i-s');

	//Check if log is active, then create folder structure
	if(ENABLE_LOG) { 
		if(!file_exists($folder)) 
			mkdir($folder, 0755);
	}

	//Retreive web services
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "http://190.14.230.242:60020/ServicioMovilDITO_v2/ServicioMovilDITO.svc/GetInventario/0040000001");
	$resultInventory = curl_exec($ch);
	curl_close($ch);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "http://190.14.230.242:60020/ServicioMovilDITO_v2/ServicioMovilDITO.svc/GetPrecios");
	$resultPrices = curl_exec($ch);
	curl_close($ch);

	$inventory = json_decode($resultInventory);
	$prices = json_decode($resultPrices);
	$fail = 0;
	$successfull = 0;

	if(ENABLE_LOG) {
		Mage::log('Recovered ' . count($prices->Precios) . ' registers', null, date('d-m-Y') . '/price_' . $execTime . '.log');
	    Mage::log('Recovered ' . count($inventory->Inventario) . ' registers', null, date('d-m-Y') . '/inventory_' . $execTime . '.log');
	}

	if($browser){
	    echo '<br>----Execution date '.date('d/m/Y ') . $execTime;
	    flush();
	    ob_flush();
	}

	$data = array(); //Midex data from Prices and Inventory web services

	foreach ($prices->Precios as $precio) {
		$data[$precio->CodPro] = round($precio->PVN,0);
	}

	$skus = array_keys($data);

	$products = Mage::getResourceModel('catalog/product_collection')
					->addAttributeToSelect(array('sku', 'price'))
					->addAttributeToFilter(
				        'sku', array('in' => $skus)
				    )
				    ->load();

	foreach ($products as $p) {
		$p->setPrice($data[$p->getSku()]);

		if(ENABLE_LOG)
	        Mage::log('Product sku ' . $p->getSku() . ' price updated to $' . $data[$p->getSku()], null, date('d-m-Y') . '/price_' . $execTime . '.log');

	    if($browser){
	        echo '<br>Product sku ' . $p->getSku() . ' price updated to $' . $data[$p->getSku()];
	        flush();
	        ob_flush();
	    }
	}

	$products->save();

	if($browser) {
        echo '<br><br>----------------------------------------------------<br><br>';
        flush();
        ob_flush();
    }

    unset($data);

	/************ INVENTORY PROCESS ************/
    foreach ($inventory->Inventario as $inventario) {
		$data[$inventario->Codigo] = $inventario->Cantidad;
	}

	$skus = array_keys($data);

	$products = Mage::getResourceModel('catalog/product_collection')
					->addAttributeToSelect(array('entity_id', 'sku', 'qty'))
					->addAttributeToFilter(
				        'sku', array('in' => $skus)
				    )
				    ->load();

	$dataTmp = array();

	foreach ($products as $p) {
		$dataTmp[$p->entity_id] = $data[$p->sku];
	}

	$data = $dataTmp;
	unset($dataTmp);

	$stockItem = Mage::getResourceModel('cataloginventory/stock_item_collection')
				->addFieldToSelect(array('product_id', 'is_in_stock', 'qty'))
				->addProductsFilter($products)
				->load();

	foreach ($stockItem as $item) {
		$qty = $data[$item->product_id];
		$item->setQty($qty);
		
		if($qty == 0) {
			$item->setIsInStock('0');
	    } else {
	    	$item->setIsInStock('1');
	    }

	    $product = Mage::getResourceModel('catalog/product_collection')
	    ->addAttributeToSelect(array('sku'))
	    ->addAttributeToFilter(
		        'entity_id', $item->product_id
		    )
	    ->load()
	    ->getFirstItem();

	    if(ENABLE_LOG)
	        Mage::log('Product sku ' . $product->getSku() . ' inventory updated to ' . $qty, null, date('d-m-Y') . '/inventory_' . $execTime . '.log');

	    if($browser){
	        echo '<br>Product sku ' . $product->getSku() . ' inventory updated to ' . $qty;
	        flush();
	        ob_flush();
	    }
	}
	$stockItem->save();

return ; 
