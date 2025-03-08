<?php
namespace Isbc\Prayertimes\helpers;

Class HelperClass
{

#   BOM = Byte-order mark. 
#   more info: https://en.wikipedia.org/wiki/Byte_order_mark
#________________________________________________________________

public const TimeIntervalDay = 0;
public const TimeIntervalHour = 1;
public const TimeIntervalMinute = 2;
public const TimeIntervalSecond =4;

public function removeBOM($string) : string
{
    return preg_replace('/^\xEF\xBB\xBF/', '', $string);
}
#________________________________________________________________
public function TimeToMidnight(\DateTime $start_time, int $time_interval_option) :float {

    $tomorrow_local = new \DateTime("tomorrow"); #tomorrow midnight   
    $divisor = null;

        switch ($time_interval_option) {
            case $this::TimeIntervalSecond:
                $divisor = 1;
                break;
            case $this::TimeIntervalMinute:
                $divisor = 60;
                break;
            case $this::TimeIntervalHour:
                $divisor = 60 * 60;
            case $this::TimeIntervalDay:
                $divisor = 60 * 60 * 25;
                break;
        }

    return ((
        $tomorrow_local->getTimestamp() - 
        $start_time->getTimestamp()) / $divisor) ;

}
#__________________________________________________________________________________#

public static function format_date(string $g_date): string 
{
    $f3 = \Base::instance();
    $date_html = new \DateTime($g_date);
    $html_date_format = $f3->get('HtmlLongDateFormat');
    return $date_html->format($html_date_format);
}
#__________________________________________________________________________________#

public function format_time(string $time) :string
{
    $f3 = \Base::instance();
    $date = new \DateTime($time);
    $format = $f3->get('TimeFormat');
    return $date->format($format);
}


}