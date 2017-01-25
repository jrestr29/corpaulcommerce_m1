<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    INSERT INTO  `{$this->getTable('directory_country_region')}` (`country_id`,`default_name`) 
    VALUES ('CO','ANTIOQUIA'), ('CO','ATLANTICO'), ('CO','BOGOTA'), ('CO','BOLIVAR'), ('CO','BOYACA'), ('CO','CALDAS'), ('CO','CAQUETA'), ('CO','CAUCA'), ('CO','CESAR'), ('CO','CORDOBA'), ('CO','CUNDINAMARCA'), ('CO','CHOCO'), ('CO','HUILA'), ('CO','LA GUAJIRA'), ('CO','MAGDALENA'), ('CO','META'), ('CO','NARIÑO'), ('CO','N. DE SANTANDER'), ('CO','QUINDIO'), ('CO','RISARALDA'), ('CO','SANTANDER'), ('CO','SUCRE'), ('CO','TOLIMA'), ('CO','VALLE DEL CAUCA'), ('CO','ARAUCA'), ('CO','CASANARE'), ('CO','PUTUMAYO'), ('CO','SAN ANDRES'), ('CO','AMAZONAS'), ('CO','GUAINIA'), ('CO','GUAVIARE'), ('CO','VAUPES');
");

$installer->endSetup();
