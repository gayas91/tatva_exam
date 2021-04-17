<?php
namespace App\View\Helper;
 
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;


class CustomHelper extends Helper {

    public function totalRecurrence($data = array()) {
    	//return 1;

    	if($data->recurrence=='first_repeat') {
    		if(($data->select_two=='1') && ($data->select_one=='1' || $data->select_one=='2' || $data->select_one=='3' || $data->select_one=='4')) {
    			$startDate = ($data->start_date);
    			$endDate = $data->end_date;

    			$diff = strtotime($startDate) - strtotime($endDate);
    			$diff = ($diff/$data->select_one);
    			$diff = (int)$diff;
    			return abs(round($diff / 86400));
    		}
    		if(($data->select_two=='2') && ($data->select_one=='1' || $data->select_one=='2' || $data->select_one=='3' || $data->select_one=='4')) {
    			$startDate = ($data->start_date);
    			$endDate = $data->end_date;

    			$HowManyWeeks = date( 'W', strtotime($endDate) ) - date( 'W', strtotime($startDate) );

    			$diff = ($HowManyWeeks/$data->select_one);
    			$diff = (int)$diff;
    			return $diff;
    		}
    		if(($data->select_two=='3') && ($data->select_one=='1' || $data->select_one=='2' || $data->select_one=='3' || $data->select_one=='4')) {
    			$startDate = ($data->start_date);
    			$endDate = $data->end_date;

				$ts1 = strtotime($startDate);
				$ts2 = strtotime($endDate);

				$year1 = date('Y', $ts1);
				$year2 = date('Y', $ts2);

				$month1 = date('m', $ts1);
				$month2 = date('m', $ts2);

				$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

    			$diff = ($diff/$data->select_one);
    			$diff = (int)$diff;
    			return $diff;
    		}
    		if(($data->select_two=='4') && ($data->select_one=='1' || $data->select_one=='2' || $data->select_one=='3' || $data->select_one=='4')) {
    			$startDate = ($data->start_date);
    			$endDate = $data->end_date;

				$ts1 = strtotime($startDate);
				$ts2 = strtotime($endDate);

				$diff = $ts2 - $ts1;
				$secPerYear = 60*60*24*365;
				$years = round($diff / $secPerYear);

    			$rslt = ($years/$data->select_one);
    			$rslt = (int)$rslt;
    			return $rslt;
    		}

    	} else {
    		//return 'Gayas';

    		$startDate = ($data->start_date);
			$endDate = $data->end_date;

			$ts1 = strtotime($startDate);
			$ts2 = strtotime($endDate);

			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);

			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);

			$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
			$diff= $diff+1;
			$totalMonth = $data->select_five;
			if($diff >= $totalMonth){
                $count= $totalMonth;
			}else{
				$count= $diff;
			}
			return $count;
    	}
    }

    public function getDate($start, $end, $weekday = 0) {
    	$weekday = "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday";
    	$arr_weekday = explode(",", $weekday);
    	$start = strtotime("+0 day", strtotime($start));
    	$end = strtotime($end);

    	$dateArr = array();
    	$friday = strtotime($weekday, $start);
    	while($friday <= $end)
    	{
    		$dateArr[] = date("Y-m-d", $friday);
    		$friday = strtotime("+1 weeks", $friday);
    	}
    	$dateArr[] = date("Y-m-d", $friday);

    	return $dateArr;
    }
}