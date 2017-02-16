<?php

$installer = $this;
$installer->startSetup();

$regions = Mage::getModel('directory/region')->getResourceCollection()
    ->addCountryFilter('CO')
    ->load();

foreach ($regions as $region) {
    $sql = "INSERT INTO `{$this->getTable('directory_country_region_name')}`  (`locale`,`region_id`,`name`) VALUES ('en_US', ".$region->getRegionId().", '".$region->getDefaultName()."')";
    $installer->run($sql);
}

$installer->endSetup();