<?php
namespace Isbc\Prayertimes\models;

use Isbc\Prayertimes\PrayerIds;
use Isbc\Prayertimes\models\CsvBaseModel;


/*
*   This class is responsible for providing data for MainTvView view.
 */
class CsvMainTvModel extends CsvBaseModel

{
    #private HelperClass $helperClass;
    private PrayerIds $pids; 
    private array $mainTvDataset;

    private string $active_prayer_alert = "See you tomorrow inSha'Alla!";
    private int $active_prayer_id=-1;
    private float $refresh_timer;

    private string $friday_alert = "";

    private \DateTimeZone $local_tz;
    private \DateTimeZone $utc_tz;
    private \DateTime $now_utc;
    private \DateTime $now_local;
    private \DateTime $today;
    private \DateTime $tomorrow;

    public function __construct ()
    {
        parent::__construct();
        $this->pids = new PrayerIds;
        $this->local_tz = new \DateTimeZone($this->f3->get('LocalTimeZone'));
        $this->utc_tz = new \DateTimeZone("UTC");
        $this->now_local = new \DateTime("now", $this->local_tz);
        $this->now_utc = new \DateTime("now", $this->utc_tz);
        $this->today = new \DateTime("today");
        $this->tomorrow = new \DateTime("tomorrow");
        $this->mainTvDataset = $this->getDatasetByDates($this->today,$this->tomorrow);
        $this->set_active_prayer($this->mainTvDataset[0]["calls"] ,0);
        $this->set_friday_message();
    }
#_______________________________________________________________

    private function set_active_prayer() : bool  {
        
        # the first prayers set should be for today, This is the only set to be evaluated:

        $iqama_columns = $this->prayers_list->getIqamaColNames();
        $azan_columns = $this->prayers_list->getAzanColNames();
        $calls = $this->mainTvDataset[0]["calls"];
        for ($x = $this->pids::Fajr; $x < $this->pids::Isha + 1; $x++) {
            $now_local = $this->now_local;
            if ($x == $this->pids::Sunrise) {
                $iqamatime = new \DateTime(($this->mainTvDataset[0]["DateG"])
                ." ".($calls[$azan_columns[$x]]));
            } else {
                $iqamatime = new \DateTime(($this->mainTvDataset[0]["DateG"])
                ." ".($calls[$iqama_columns[$x]]));;
            }
            $hold_off_min = $this->f3->get('NextPrayerHoldOffMin');
            $iqamatime_plus_holdoffmin = (clone $iqamatime)->modify("+$hold_off_min min");
            $prayer_name = $this->mainTvDataset[0]["prayers_eng_names"][$x];

            # after Isha prayer is done and until midnight:
            if ( $x == $this->pids::Isha && $iqamatime_plus_holdoffmin < $now_local ){
                $this->refresh_timer = $this->helperClass->TimeToMidnight($this->now_local, $this->helperClass::TimeIntervalMinute);
                $this->updateMainTvDataset($x);
                return true;
            }

            if ($iqamatime > $now_local) {
                $this->active_prayer_id = $x;
                $this->refresh_timer = $this->timeToIqama($iqamatime, TRUE);
                
                if ($x == $this->pids::Sunrise) { # Sunrise message:
                    $this->active_prayer_alert = "Time remaining until $prayer_name is ";
                } else # other prayers message:
                {
                    $this->active_prayer_alert = "Time remaining until iqamatul-$prayer_name is ";
                }
                $this->updateMainTvDataset($x);
                return true;
            } elseif ($iqamatime_plus_holdoffmin > $now_local) {
                $this->active_prayer_id = $x;
                $this->refresh_timer = $this->timeToIqama($iqamatime_plus_holdoffmin, TRUE);
                if ($x == $this->pids::Sunrise) { # Sunrise message:
                    $this->active_prayer_alert = "Time remaining until $prayer_name PRAYER is ";
                } else { # Other prayers message.
                    $this->active_prayer_alert = "$prayer_name prayer is running. ";
                }
                $this->updateMainTvDataset($x);
                return true;
            }
        }
        return false;
    }
#________________________________________________________________
private function timeToIqama (\DateTime $iqamatime, bool $in_minutes = FALSE) : float|false
    {
        # this function will return the interval between now and the iqama time.
        # next if condition is true when the time is passed Isha and before midnight
        # so we can set the referesh timer to midnight.
        if ( ($this->now_local->format("j")) != ($iqamatime->format("j"))){
            #print_r("first IF<br>");
            $tomorrow = new \DateTime("tomorrow"); #tomorrow midnight
            $interval = date_diff($this->now_local, $tomorrow);

            if ($in_minutes) {
                return ($tomorrow->getTimestamp() - $this->now_local->getTimestamp()) / 60 ;    
            } else{
                return $interval->format("%H:%I (hr:min)");
            }
        }elseif ($iqamatime > $this->now_local) {
            $interval = date_diff($this->now_local, $iqamatime);
            if ($in_minutes) {
                return ($iqamatime->getTimestamp() - $this->now_local->getTimestamp() )/ 60;    
            } else{
                return $interval->format("%H:%I (hr:min)");
            }           
        
        } else {            
            return FALSE;	
        }
    }
#________________________________________________________________

    private function updateMainTvDataset($pid) {
        $this->mainTvDataset[0]["active_prayer_id"] = $pid;
        $this->mainTvDataset[0]["active_prayer_alert"] = $this->active_prayer_alert;
        $this->mainTvDataset[0]["active_prayer_timer"]=$this->refresh_timer;
    }

#__________________________________________________________________________________#
private function set_friday_message()
{
    # naming convention fp = friday_prayers, dt means date_time, d means date, t means time
    # Ref: https://www.php.net/manual/en/datetime.format.php
    # The reason for using utc timezone for the Jumuah time calculation is
    # to avoid the impact of daylight saving (DST) change. Jumuah prayers
    # is at 12:30pm during standard time and at 1:30pm during DST but it
    # it remains the same time if the calculations was based off the UTC
    # then converted to local timezone at the end.

    $friday_prayers_msg = ""; $msgWithoutDate = FALSE;

    $now_utc = $this->now_utc;

    if ($now_utc->format("D")=="Fri") {
        $next_fp_dt_utc = new \DateTime("Today 20:30", $this->utc_tz);
        $msgWithoutDate = TRUE;
    }
        else {
            $next_fp_dt_utc = new \DateTime("next fri 20:30", $this->utc_tz);
            $friday_prayers_msg = "Next Jumuah Prayers ";
    };
    $next_fp_dt_local = (clone $next_fp_dt_utc)->setTimezone($this->local_tz);
    $next_fp_d_local = $next_fp_dt_local->format('F jS');
    $next_fp_t_local = $next_fp_dt_local->format($this->f3->get('TimeFormat'));
    
    if ($msgWithoutDate == TRUE){
        $friday_prayers_msg = "Today's Jumuah Prayer's Khutba starts at $next_fp_t_local.";
    } else{
        $friday_prayers_msg = "Next Jumuah Prayers: $next_fp_d_local. KHUTBA starts at $next_fp_t_local.";
    };

    $this->friday_alert = $friday_prayers_msg;

    $this->mainTvDataset[0]["friday_prayer_alert"] = $this->friday_alert;
    
}

#_______________________________________________________________



public function getMainTvDataset(): array|null
{
    return $this->mainTvDataset;
}

}