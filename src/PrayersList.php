<?php

namespace Isbc\Prayertimes;

use Isbc\Prayertimes\PrayerIds;

class PrayersList
{
    public $PrayersList;
    private PrayerIds $PIds;
    public function __construct()
    {
        $this->PIds = new PrayerIds;
        $this->PrayersList = [
            ["id" => $this->PIds::Fajr, "NameEng" => "Fajr", "NameAra" => "فجر", "AzanCol" => "fajr1", "IqamaCol" => "fajr2"],
            ["id" => $this->PIds::Sunrise, "NameEng" => "Sunrise", "NameAra" => "شروق", "AzanCol" => "sunrise", "IqamaCol" => ""],
            ["id" => $this->PIds::Dhuhr, "NameEng" => "Dhuhr", "NameAra" => "ظهر", "AzanCol" => "dhuhr1", "IqamaCol" => "dhuhr2"],
            ["id" => $this->PIds::Asr, "NameEng" => "Asr", "NameAra" => "عصر", "AzanCol" => "asr1", "IqamaCol" => "asr2"],
            ["id" => $this->PIds::Maghreb, "NameEng" => "Maghreb", "NameAra" => "مغرب", "AzanCol" => "maghreb1", "IqamaCol" => "maghreb2"],
            ["id" => $this->PIds::Isha, "NameEng" => "Isha", "NameAra" => "عشاء", "AzanCol" => "isha1", "IqamaCol" => "isha2"]
        ];
    }

    public function getIdsNamesEng()
    {
        return array_column($this->PrayersList, "NameEng", "id");
    }

    public function getIdsNamesAra()
    {
        return array_column($this->PrayersList, "NameAra", "id");
    }
    public function getAzanColNames() : array
    {
        return array_column($this->PrayersList, "AzanCol", "id");
    }
    public function getIqamaColNames() : array
    {
        return array_column($this->PrayersList, "IqamaCol", "id");
    }

}
