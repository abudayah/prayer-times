<?php

namespace Isbc\Prayertimes\controllers;

use Isbc\Prayertimes\PrayerIds;
use Isbc\Prayertimes\HelperClass;
use Isbc\Prayertimes\models;
use Isbc\Prayertimes\views;

class MainTvController
{
    private $ds;
    
    function act()
    {
        $f3=\Base::instance();

        $myModel = new models\CsvMainTvModel();

        $this->ds = $myModel->getMainTvDataset();

        if(!is_null($this->ds)) 
        {
            $myview = new views\MainTvView();
            $myview->display($this->ds);
        } else {
            
        }
    }
}
