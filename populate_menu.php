<?php
$mageFilename = 'app/Mage.php';
require_once $mageFilename;

Mage::setIsDeveloperMode(true);

ini_set('display_errors', 1);

umask(0);
Mage::app('admin');
Mage::register('isSecureArea', 1);

$parentId = '2';

//Main categories
$categories[0] = "tecnología";
$categories[1] = "bonos & tarjetas";
$categories[2] = "corporativos";
$categories[3] = "hogar & decoracion";
$categories[4] = "ofertas";
$categories[5] = "promocionales";
$categories[6] = "regalos";
$categories[7] = "novedades";

//Subcategories
$subcategories[0] = array("novedades","nuevos");
$subcategories[1] = array("navidad","tarjetas");
$subcategories[2] = array("navidad","faroles");
$subcategories[3] = array("navidad","candelarios");
$subcategories[4] = array("navidad","lluvia de sobres");
$subcategories[5] = array("regalos","para oficina");
$subcategories[6] = array("regalos","para hombre");
$subcategories[7] = array("regalos","para mujer");
$subcategories[8] = array("regalos","para niños");
$subcategories[9] = array("regalos","de viaje");
$subcategories[10] = array("regalos","para ordenar");
$subcategories[11] = array("regalos","tecnología");
$subcategories[12] = array("regalos","vinos y quesos");
$subcategories[13] = array("promocionales","escritura");
$subcategories[14] = array("promocionales","libretas");
$subcategories[15] = array("promocionales","calendarios");
$subcategories[16] = array("promocionales","tecnología");
$subcategories[17] = array("promocionales","oficina");
$subcategories[18] = array("promocionales","tarjeteros");
$subcategories[19] = array("promocionales","tableros");
$subcategories[20] = array("promocionales","llaveros");
$subcategories[21] = array("promocionales","herramientas");
$subcategories[22] = array("promocionales","linternas");
$subcategories[23] = array("promocionales","cajas de seguridad");
$subcategories[24] = array("promocionales","alcancias");
$subcategories[25] = array("promocionales","bebidas");
$subcategories[26] = array("promocionales","vinos y quesos");
$subcategories[27] = array("tecnología","memorias");
$subcategories[28] = array("tecnología","speakers");
$subcategories[29] = array("bonos & tarjetas","bonos");
$subcategories[30] = array("bonos & tarjetas","tarjetas");
$subcategories[31] = array("corporativos","vinos y quesos");
$subcategories[32] = array("corporativos","picin");
$subcategories[33] = array("corporativos","café");
$subcategories[34] = array("corporativos","té");
$subcategories[35] = array("corporativos","sombrillas");
$subcategories[36] = array("corporativos","relojes");
$subcategories[37] = array("corporativos","timer");
$subcategories[38] = array("corporativos","calendarios");
$subcategories[39] = array("corporativos","viajes");
$subcategories[40] = array("corporativos","monederos");
$subcategories[41] = array("corporativos","llaveros");
$subcategories[42] = array("corporativos","herramientas");
$subcategories[43] = array("corporativos","tecnología");
$subcategories[44] = array("hogar & decoracion","vinos y quesos");
$subcategories[45] = array("hogar & decoracion","picinc");
$subcategories[46] = array("hogar & decoracion","café");
$subcategories[47] = array("hogar & decoracion","té");
$subcategories[48] = array("hogar & decoracion","cocina");
$subcategories[49] = array("hogar & decoracion","jardín");
$subcategories[50] = array("hogar & decoracion","alcancias");
$subcategories[51] = array("hogar & decoracion","cajas de seguridad");
$subcategories[52] = array("hogar & decoracion","relojes");
$subcategories[53] = array("hogar & decoracion","tableros");
$subcategories[54] = array("hogar & decoracion","portarretratos");
$subcategories[55] = array("hogar & decoracion","candelabros");


//first process main categories
foreach($categories as $category) {
    try{
        $test_category = Mage::getModel('catalog/category')
            ->loadByAttribute('name', $category);

        if(!$test_category) {
            $new_category = Mage::getModel('catalog/category');
            $new_category->setName($category);
            $new_category->setIsActive(1);
            $new_category->setIsAnchor(1); //for active anchor
            $new_category->setStoreId(Mage::app()->getStore()->getId());

            $parentCategory = Mage::getModel('catalog/category')->load(2);
            $new_category->setPath($parentCategory->getPath());
            $new_category->save();
        }

    } catch(Exception $e) {
        echo $e->getMessage();
        die();
    }
}

foreach($subcategories as $subcategory) {
    $parentCategory = Mage::getModel('catalog/category')
        ->loadByAttribute('name', $subcategory[0]);

    if($parentCategory) {
        $checkCategory = Mage::getModel('catalog/category')->getCollection()
            ->addFieldToFilter('name', array('eq' => $subcategory[1]))
            ->addFieldToFilter('path', array('like' => '%'.$parentCategory->getPath().'%'))
            ->load()
            ->getData();

        if(sizeof($checkCategory) == 0) {
            $new_category = Mage::getModel('catalog/category');
            $new_category->setName($subcategory[1]);
            $new_category->setIsActive(1);
            $new_category->setDisplayMode('PRODUCTS');
            $new_category->setIsAnchor(1); //for active anchor
            $new_category->setStoreId(Mage::app()->getStore()->getId());
            $new_category->setPath($parentCategory->getPath());
            $new_category->save();
        }
    }
}