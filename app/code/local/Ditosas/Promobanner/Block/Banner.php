<?php
class Ditosas_Promobanner_Block_Banner extends Mage_Core_Block_Template
{
    public function checkIfFirst()
    {
        if(isset($_COOKIE['first_visit_buenosmotivos']))
        {
            return false;
        } else {
            return true;
        }
    }

    public function setCookie()
    {
        setcookie("first_visit_buenosmotivos", true, time()+10800);
    }
}