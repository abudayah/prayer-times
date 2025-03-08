<?php

use Isbc\Prayertimes\CsvBaseModel;


$reader = new CsvBaseModel;

$d1 = (new \DateTime("today"))->format($App::CsvDateFormatStr);
$d2 = (new \DateTime("today + 1 day"))->format($App::CsvDateFormatStr);
$d3 = (new \DateTime("today + 2 days"))->format($App::CsvDateFormatStr);
$d4 = (new \DateTime("today + 3 days"))->format($App::CsvDateFormatStr);
$d5 = (new \DateTime("today + 4 days"))->format($App::CsvDateFormatStr);
$d6 = (new \DateTime("today + 5 days"))->format($App::CsvDateFormatStr);
$d7 = (new \DateTime("today + 6 days"))->format($App::CsvDateFormatStr);
$d8 = (new \DateTime("today + 7 days"))->format($App::CsvDateFormatStr);
$d9 = (new \DateTime("today + 8 days"))->format($App::CsvDateFormatStr);
$d10 = (new \DateTime("today + 9 days"))->format($App::CsvDateFormatStr);
$d11 = (new \DateTime("today + 10 day"))->format($App::CsvDateFormatStr);
$d12 = (new \DateTime("today + 11 days"))->format($App::CsvDateFormatStr);

$start_date = new \DateTime("today");
$end_date = new \DateTime("today + 1 days");
#$dataset = $reader->getDataset(); 
# $dataset = $reader->getDataset(0,$d1, $d2, $d3, $d4, $d5, $d6, $d7, $d8, $d9, $d10, $d11, $d12);
#$dataset = $reader->getDataset(0,$d1, $d2);

$dataset = $reader->getDatasetByDates($start_date, $end_date);

if ( $dataset != false ) { 
    print_r (count($dataset));
    print("<br>");
    print_r($dataset); 
} else {
    print "FALSE RETURNED!!";
}