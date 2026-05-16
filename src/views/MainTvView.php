<?php
namespace Isbc\Prayertimes\views;
use Isbc\Prayertimes\models\AdminModel;

class MainTvView
{
    public function display()
    {
        $f3 = \Base::instance();
        $f3->set('content', 'src/views/html/ContentLayout.html');
        $f3->set('LeftSide', 'src/views/html/LeftSide.html');
        $f3->set('Duaa', 'src/views/html/Duaa.html');
        $f3->set('Posters', 'src/views/html/Posters.html');
        $f3->set('PrayerTimes', 'src/views/html/PrayerTimes.html');

        $db = $f3->get('DB');
        $adminModel = new AdminModel($db);
        $posters = $adminModel->getImages();
        $f3->set('posters', $posters);

        echo \Template::instance()->render('src/views/html/BaseLayout.html');
    }
}
