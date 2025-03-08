<?php

namespace Isbc\Prayertimes\views;

class HomeView
{
    function display()
    {
        $f3 = \Base::instance();
        $f3->set('content','src/views/html/HomeContent.html');
        $f3->set('footer','no');
        echo \Template::instance()->render('src/views/html/BaseLayout.html');
    }
}

?>