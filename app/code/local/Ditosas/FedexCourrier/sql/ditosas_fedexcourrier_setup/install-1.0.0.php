<?php

$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ditosas_fedexcourrier/costos');

if (!$installer->getConnection()->isTableExists($tableName)) {
    $table = $installer->getConnection()
        ->newTable($tableName)
        ->addColumn('costo_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'PK')
        ->addColumn('country_id', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ), 'country')
        ->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'region')
        ->addColumn('city_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'city    ')
        ->addColumn('cost', Varien_Db_Ddl_Table::TYPE_FLOAT, null, array(
            'nullable'  => false,
        ), 'Shipping cost to destination')
        ->addForeignKey(
            $installer->getFkName($tableName, 'region_id', 'directory_country_region','region_id'),
            'region_id',
            $installer->getTable('directory_country_region'),
            'region_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $installer->getFkName($tableName, 'city_id', 'directory_country_region_city','city_id'),
            'city_id',
            $installer->getTable('directory_country_region_city'),
            'city_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        );

    $installer->getConnection()->createTable($table);
}

$installer->endSetup();