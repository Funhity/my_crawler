<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计算两个时间戳之间的天数间隔
 * 计算方法是进位的，开始时间=100, 0 <= 结束时间 < 86400 为第一天；
 */
if ( ! function_exists('datetime_dayinterval'))
{

	function datetime_dayinterval($timestampBegin, $timestampEnd, $ignoreWeekends=false){

//		$begin = strtotime($startDate);
//		$end   = strtotime($endDate);
		$begin = $timestampBegin;
		$end = $timestampEnd;
		if ($begin > $end) {
			echo "startdate is in the future! <br />";
			return 0;
		} else {
			$no_days  = 0;
			$weekends = 0;
			while ($begin <= $end) {
				$no_days++; // no of days in the given interval
				$what_day = date("N", $begin);
				if ($what_day > 5 && $ignoreWeekends) { // 6 and 7 are weekend days
					$weekends++;
				};
				$begin += 86400; // +1 day
			};
			$working_days = $no_days - $weekends;

			return $working_days;
		}
		return $key;
	}
}


/**
 * 计算两个时间戳0点之间的天数(东八区)
 * 也就是说忽略开始时间和结束时间当天的秒数，都归到0:00, 然后再计算天数差；
 */
if ( ! function_exists('datetime_dayinterval_byday'))
{

	function datetime_dayinterval_byday($timestampBegin, $timestampEnd, $ignoreWeekends=false, $zone=8){

		$beginDate = date("Ymd", $timestampBegin+28800);
		$endDate = date("Ymd", $timestampEnd+28800);

		// 忽略当天经过的秒数，获得当天0点的时间戳，回到以天为粒度的对比维度；
		$begin = strtotime($beginDate.' 00:00:00UTC');
		$end = strtotime($endDate.' 00:00:00UTC');

		if ($begin > $end) {
			return 0;
		} else {
			$no_days  = 0;
			$weekends = 0;
			while ($begin <= $end) {
				$no_days++; // no of days in the given interval
				$what_day = date("N", $begin);
				if ($what_day > 5 && $ignoreWeekends) { // 6 and 7 are weekend days
					$weekends++;
				};
				$begin += 86400; // +1 day
			};
			$working_days = $no_days - $weekends;

			return $working_days;
		}
	}
}
