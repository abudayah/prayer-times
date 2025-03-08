<?php

namespace Isbc\Prayertimes;

class CsvDataSchema
{
    public $Schema;

    public function __construct()
    {
        $this->Schema = [
            ["col_index" => 0, "col_descr" => "Gregorian Date", "rel" => "parent", "db_name" => "DateG"],
            ["col_index" => 1, "col_descr" => "Hijri Date", "rel" => "parent", "db_name" => "DateH"],
            ["col_index" => 2, "col_descr" => "Fajr Azan", "rel" => "child", "db_name" =>"fajr1"],
            ["col_index" => 3, "col_descr" => "Fajr Iqama", "rel" => "child", "db_name" =>"fajr2"],
            ["col_index" => 4, "col_descr" => "Sunrise", "rel" => "child", "db_name"=>"sunrise"],
            ["col_index" => 5, "col_descr" => "Dhuhr Azan", "rel" => "child", "db_name"=>"dhuhr1"],
            ["col_index" => 6, "col_descr" => "Dhur Iqama", "rel" => "child", "db_name"=>"dhuhr2"],
            ["col_index" => 7, "col_descr" => "Asr Azan", "rel" => "child", "db_name"=>"asr1"],
            ["col_index" => 8, "col_descr" => "Asr Iqama", "rel" => "child", "db_name"=>"asr2"],
            ["col_index" => 9, "col_descr" => "Maghreb Azan", "rel" => "child", "db_name"=>"maghreb1"],
            ["col_index" => 10, "col_descr" => "Maghreb Iqama", "rel" => "child", "db_name"=>"maghreb2"],
            ["col_index" => 11, "col_descr" => "Isha Azan", "rel" => "child", "db_name"=>"isha1"],
            ["col_index" => 12, "col_descr" => "Isha Iqama", "rel" => "child", "db_name"=>"isha2"]
        ];
    }
    public function getDataFieldIndex(string $php_name = "dDate"): string
    {
        foreach ($this->Schema as $field) {
            if ($field["php_name"] == $php_name) return $field["col_index"];
        }
    }

    public function getDescrCol()
    {
        return array_column($this->Schema, "col_desc");
    }
    public function getIndexesCol()
    {
        return array_column($this->Schema, "col_index");
    }
    public function FristColumnIndex(): int
    {
        return $this->getIndexesCol()[0];
    }
    public function LastColumnIndex(): int
    {
        $index_arr = $this->getIndexesCol();
        return $index_arr[count($index_arr) - 1];
    }
}
