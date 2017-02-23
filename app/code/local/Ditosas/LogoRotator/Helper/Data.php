<?php

class Ditosas_LogoRotator_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getLogoImgSrc() {
        $logoFiles = scandir(Mage::getDesign()->getSkinBaseDir().'/images/media/logos');
        unset($logoFiles[0]);
        unset($logoFiles[1]);
        $logoFiles = array_values($logoFiles); //Reindex Array

        return $logoFiles[array_rand($logoFiles)];
    }
}