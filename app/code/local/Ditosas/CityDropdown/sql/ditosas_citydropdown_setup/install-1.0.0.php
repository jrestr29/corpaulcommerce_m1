<?php
$installer = $this;
$installer->startSetup();

$tableName = $installer->getTable('ditosas_citydropdown/city');

if (!$installer->getConnection()->isTableExists($tableName)) {
    $table = $installer->getConnection()
        ->newTable($tableName)
        ->addColumn('city_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'PK')
        ->addColumn('locale', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ), 'name locale')
        ->addColumn('country_id', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ), 'city country identifier')
        ->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'city region identifier')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable'  => false,
        ), 'city name')
        ->addForeignKey(
            $installer->getFkName($tableName, 'region_id', $this->getTable('directory_country_region') ,'region_id'),
            'region_id',
            $installer->getTable('directory_country_region'),
            'region_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        );

    $installer->getConnection()->createTable($table);
}



$installer->endSetup();