<?php
    $installer = $this;
    $installer->startSetup();

    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');

    $entityTypeId     = $setup->getEntityTypeId('customer');
    $attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
    $attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

    //Setup "Tipo de documento" attribute
    $installer->addAttribute("customer", "tipodocumentoidentidad",  array(
        "type"     => "int",
        "label"    => "Tipo documento de identidad",
        "input"    => "select",
        "source"   => "",
        "visible"  => true,
        "required" => true,
        "default" => "",
        "frontend" => "",
        "unique"     => false,
        "note"       => "Tipo de documento de identidad legal",
        'source'        => 'eav/entity_attribute_source_table',
        'option' => [
            'values' => [
                '1' => 'Cedula de ciudadanía',
                '2' => 'Cedula de extranjería',
                '3' => 'Tarjeta de identidad',
                '4' => 'Documento único de identidad',
                '5' => 'Pasaporte',
                '6' => 'NIT'
            ]
        ]
    ));

    $attribute   = Mage::getSingleton("eav/config")->getAttribute("customer", "tipodocumentoidentidad");

    $setup->addAttributeToGroup(
        $entityTypeId,
        $attributeSetId,
        $attributeGroupId,
        'tipodocumentoidentidad',
        '32'  //sort_order
    );

    $used_in_forms=array();

    $used_in_forms[]="adminhtml_customer";
    $used_in_forms[]="checkout_register";
    $used_in_forms[]="customer_account_create";
    $used_in_forms[]="customer_account_edit";
    $used_in_forms[]="adminhtml_checkout";

    $attribute->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 32);

     $attribute->save();

    //Setup "Documento de identidad" attribute
    $installer->addAttribute("customer", "documentoidentidad",  array(
        "type"     => "varchar",
        "backend"  => '',
        "label"    => "Documento de identidad",
        "input"    => "text",
        "source"   => "",
        "visible"  => true,
        "required" => true,
        "default" => "",
        "frontend" => "",
        "unique"     => false,
        "note"       => "Documento de identidad legal"
    ));

    $attribute   = Mage::getSingleton("eav/config")->getAttribute("customer", "documentoidentidad");

    $setup->addAttributeToGroup(
        $entityTypeId,
        $attributeSetId,
        $attributeGroupId,
        'tipodocumentoidentidad',
        '33'  //sort_order
    );

    $used_in_forms=array();

    $used_in_forms[]="adminhtml_customer";
    $used_in_forms[]="checkout_register";
    $used_in_forms[]="customer_account_create";
    $used_in_forms[]="customer_account_edit";
    $used_in_forms[]="adminhtml_checkout";

    $attribute->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 33);

    $attribute->save();

    $installer->endSetup();