<?php
class Datex {

	function usaDate($mdate) {
		if (empty($mdate)) {
			return "";
		} else {
			return preg_replace("/(19|20)?(\d{2})-(\d{1,2})-(\d{1,2})/","\\3/\\4/\\1\\2",$mdate ?? "");
		}
	}

	function usaDateShort($mdate) {
		return preg_replace("/(19|20)?(\d{2})-(\d{1,2})-(\d{1,2})/","\\3/\\4/\\2",$mdate ?? "");
	}

	function isoDate($udate) {
		return preg_replace("/(\d{1,2})\/(\d{1,2})\/(19|20)?(\d{2})/","\\3\\4-\\1-\\2",$udate ?? "");
	}

	function isoDateShort($udate) {
		return preg_replace("/(\d{1,2})\/(\d{1,2})\/(19|20)?(\d{2})/","\\4-\\1-\\2",$udate ?? "");
	}

	function isUsaDate($mdate) {
		if (preg_match("/(\d{1,2})\/(\d{1,2})\/(19|20)?(\d{2})/",$mdate ?? "")) return true;
		else return false;
	}

	function isIsoDate($udate) {
		if (preg_match("/(19|20)?(\d{2})-(\d{1,2})-(\d{1,2})/",$udate ?? "")) return true;
		else return false;
	}

	function toUsaDate($mdate) {
		if ($this->isIsoDate($mdate)) $mdate = $this->usaDate($mdate ?? "");
		return $mdate ?? "";
	}

	function toIsoDate($mdate) {
		if ($this->isUsaDate($mdate)) $mdate = $this->isoDate($mdate ?? "");
		return $mdate ?? "";
	}

	function toShort($mdate) {
		if ($this->isUsaDate($mdate)) {
			return preg_replace("/(\d{1,2})\/(\d{1,2})\/(19|20)?(\d{2})/","\\1/\\2/\\4",$mdate ?? "");
		} else if ($this->isIsoDate($mdate)) {
			return preg_replace("/(19|20)?(\d{2})-(\d{1,2})-(\d{1,2})/","\\2-\\3-\\4",$mdate ?? "");
		} else {
			return $mdate;
		}
	}

	function getToday() {
		return date("m/d/Y");
	}
	function getUsaToday() {
		return date("m/d/Y");
	}
	function getIsoToday() {
		return date("Y-m-d");
	}

	function getIsoDate($fromdate="", $days=0, $dir="b") { // b for backward & f for forward
		if (empty($fromdate)) $fromdate = date("Y-m-d");
		$ts = strtotime($fromdate ?? "");
		if ($dir == "b") $ts -= 86400 * $days;
		else $ts += 86400 * $days;
		return date ("Y-m-d", $ts);
	}

	function nextWeekDay($wday, $fromday) {
		$xday = date("D", strtotime($fromday));
		$year = date("Y", strtotime($fromday));
		$month = date("m", strtotime($fromday))+0;
		$day = date("j", strtotime($fromday));

		if (strtolower($xday) == "mon") $xpos = 1;
		else if (strtolower($xday) == "tue") $xpos = 2;
		else if (strtolower($xday) == "wed") $xpos = 3;
		else if (strtolower($xday) == "thu") $xpos = 4;
		else if (strtolower($xday) == "fri") $xpos = 5;
		else if (strtolower($xday) == "sat") $xpos = 6;
		else if (strtolower($xday) == "sun") $xpos = 7;
		else return false;

		if (strtolower($wday) == "mon") $wpos = 1;
		else if (strtolower($wday) == "tue") $wpos = 2;
		else if (strtolower($wday) == "wed") $wpos = 3;
		else if (strtolower($wday) == "thu") $wpos = 4;
		else if (strtolower($wday) == "fri") $wpos = 5;
		else if (strtolower($wday) == "sat") $wpos = 6;
		else if (strtolower($wday) == "sun") $wpos = 7;
		else return false;

		$dday = $wpos - $xpos;
		if ($dday < 0) $dday += 7;
		return date("m/d/Y", mktime(0,0,0,$month,$day+$dday,$year));
	}

	function getWeekday($uday) {
		if (empty($uday)) return "";
		else return strtolower(date("D", strtotime($this->isoDate($uday ?? ""))));
	}
}


?>