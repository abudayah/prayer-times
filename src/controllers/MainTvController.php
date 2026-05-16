<?php

namespace Isbc\Prayertimes\controllers;

use Isbc\Prayertimes\views;

class MainTvController
{
    function act()
    {
        $myview = new views\MainTvView();
        $myview->display();
    }
}
