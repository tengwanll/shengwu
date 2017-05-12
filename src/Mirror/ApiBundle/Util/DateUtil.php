<?php

namespace Mirror\ApiBundle\Util;

class DateUtil {
	public static function format($date, $format = 'Y-m-d H:i:s') {
		if (! $date)
			return '';
		try {
			$str = $date->format ( $format );
			return $str;
		} catch ( \Exception $e ) {
			return '';
		}
	}
	public static function add($date, $str) {
		$d = clone $date;
		try {
			return $d->add ( new \DateInterval ( $str ) );
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public static function sub($date, $str) {
		try {
            $result=$date->sub ( new \DateInterval ( $str ) );
			return $result;
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public static function addDay($date, $day) {
		if ($date) {
			$str = sprintf ( 'P%dD', $day );
			return DateUtil::add ( $date, $str );
		} else {
			return null;
		}
	}
	public static function subDay($date, $day) {
		if ($date) {
			$str = sprintf ( 'P%dD', $day );
			return DateUtil::sub ( $date, $str );
		} else
			return null;
	}
	public static function addMonth($date, $month) {
		$str = sprintf ( 'P%dM', $month );
		return DateUtil::add ( $date, $str );
	}
	public static function subMonth($date, $month) {
		$str = sprintf ( 'P%dM', $month );
		return DateUtil::sub ( $date, $str );
	}
	public static function getMonthFirstDay($date) {
		$date->setTime ( 0, 0, 0 );
		$year = $date->format ( 'Y' );
		$month = $date->format ( 'm' );
		$day = 1;
		$date->setDate ( $year, $month, $day );
		return $date;
	}
	public static function getNextMonthFirstDay($date) {
		$date = DateUtil::getMonthFirstDay ( $date );
		$date = DateUtil::addMonth ( $date, 1 );
		return $date;
	}
	public static function getNoTimeDate($date = null) {
		if ($date == null)
			$date = new \DateTime ();
		$date->setTime ( 0, 0, 0 );
		return $date;
	}
	public static function createDateFromFormat($str, $format = 'Y-m-d H:i:s') {
		$date = \DateTime::createFromFormat ( $format, $str );
		return $date;
	}
	public static function setTime($date, $hour = 0, $minutes = 0, $second = 0) {
		if ($date) {
			$date->setTime ( $hour, $minutes, $second );
			return $date;
		} else {
			return null;
		}
	}
	public static function getYear($date) {
		return DateUtil::format ( $date, 'Y' );
	}
	public static function getMonth($date) {
		return DateUtil::format ( $date, 'm' );
	}
	public static function getDay($date) {
		return DateUtil::format ( $date, 'd' );
	}
	public static function getHour($date) {
		return DateUtil::format ( $date, 'H' );
	}
	public static function getMinute($date) {
		return DateUtil::format ( $date, 'i' );
	}
	public static function getSecond($date) {
		return DateUtil::format ( $date, 's' );
	}
}