<?php
namespace Isbc\Prayertimes\models;

use Isbc\Prayertimes\CsvDataSchema;
use Isbc\Prayertimes\helpers\HelperClass;
use Isbc\Prayertimes\PrayersList;

/*
*   This class is responsible for reading the CSV from the CSV source specified 
*   in the AppSettings. It will retrive either the whole content of the file 
*   or certain dates, date range or period.
 */
class CsvBaseModel

{
    protected \Base $f3;
    protected CsvDataSchema $CsvSchema;
    protected PrayersList $prayers_list;
    protected HelperClass $helperClass;

    private string $dataFile;
    private array $baseDataset;
    
    private int $minColIndex;
    private int $maxColIndex;

    protected array $PrayersNamesEng;
    protected array $PrayersNamesAra;
    protected array $AzanColNames;
    protected array $IqamaColNames;

    public function __construct ()
    {
        $this->f3 = \Base::instance();
        $this->dataFile= $this->f3->get('DataFile');
        $this->helperClass = new HelperClass;
        
        $this->CsvSchema = new CsvDataSchema;
        $this->minColIndex = $this->CsvSchema->FristColumnIndex();
        $this->maxColIndex = $this->CsvSchema->LastColumnIndex();
        
        $this->prayers_list = new PrayersList;
        $this->PrayersNamesEng = $this->prayers_list->getIdsNamesEng();
        $this->PrayersNamesAra = $this->prayers_list->getIdsNamesAra();
        $this->AzanColNames = $this->prayers_list->getAzanColNames();
        $this->IqamaColNames = $this->prayers_list->getIqamaColNames();
        
    }
    #_______________________________________________________________

    # getDateset function has two optional arguments; if none of them
    # was provided or only of them, the function will return
    # the whole dateset which is not recommended for performance and
    # resources untilization reasons.
    # csv data is an associative array, it will be searched using
    # the $colIndex provided, the records where the search column equals to
    # any of the given search $values will be saved to the returned array.

    public function getDataset(int $colIndex = 0, ...$values): array|false
    {
        # check $colIndex
        if ($colIndex < $this->minColIndex || $colIndex > $this->maxColIndex 
        ) {
            return false;
        }
        $header=[];
        if (($handle = fopen($this->dataFile, "r")) !== false
        ) {
            $header; $x = 0;
            while (($data = fgetcsv($handle, 256, ",")) !== false) {
                if ((count($header) == 0)) {
                    $data[0] = $this->helperClass->removeBOM($data[0]);
                    $header = $data;
                } elseif ((count($values) == 0) xor (in_array($data[$colIndex], $values))) {
                    $ass_arr = array_combine($header,$data);
                    $this->AddDatasetItem($ass_arr,$x);
                    ++$x;
                }
            }

            fclose($handle);

            if ( isset($this->baseDataset) ) {
                return $this->baseDataset;
            } else {
                return false;
            };
        } else {
            return false;
        }
    }
#________________________________________________________________

    protected function AddDatasetItem(array $dataItem, int $parentIndex) : void
    {
        $this->baseDataset[$parentIndex]["DateG"] = $dataItem["DateG"];
        $this->baseDataset[$parentIndex]["DateH"] = $dataItem["DateH"];        
        unset($dataItem["DateG"]);
        unset($dataItem["DateH"]);
        $this->baseDataset[$parentIndex]["prayers_eng_names"] = $this->PrayersNamesEng;
        $this->baseDataset[$parentIndex]["prayers_ara_names"] = $this->PrayersNamesAra;
        $this->baseDataset[$parentIndex]["AzanColNames"] = $this->AzanColNames;
        $this->baseDataset[$parentIndex]["IqamaColNames"] = $this->IqamaColNames;
        $this->baseDataset[$parentIndex]["calls"] = $dataItem;
    }
#________________________________________________________________

    public function getDatasetByDates(\DateTime $start_date, \DateTime $end_date) : array|false 
    {
        if ($start_date > $end_date) return false;
        
        $period = array($start_date); $x=1; $next_date = clone $start_date;
        
        while ($next_date < $end_date)
        {
            $next_date = (clone $start_date)->modify("+ $x day");
            $period[$x] = $next_date;
            $x += 1;
        }
        $func = function(\DateTime $d){return $d->format($this->f3->get('CsvDateFormat'));};
        $period_strings = array_map($func,$period);
        if (($dataset = $this->getDataset(0, ...$period_strings))!=false){
            return $dataset;
        };
        return false;
    }
#________________________________________________________________
}