<?php
include_once("class.ar.php");

class TaxRates extends AR {

	function insertTaxRates($arr) {
		if ($lastid = $this->insertAR("taxrates", $arr)) return $lastid;
		return false;
	}

	function updateTaxRates($code, $arr) {
		if ($this->updateAR("taxrates", "taxrate_code", $code, $arr)) return true;
		return false;
	}

	function deleteTaxRates($code) {
		$query = "delete from taxrates where taxrate_code='$code'";
		if ($this->updateARRaw($query)) return true;
		return false;
	}

	function getTaxRates($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM taxrates WHERE taxrate_code = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getTaxRatesFields() {
		$this->query = "SELECT * FROM taxrates LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return false;
	}

	function getLastTaxRates($filter="") {
		$this->query = "SELECT * FROM taxrates ORDER BY taxrate_code DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstTaxRates($filter="") {
		$this->query = "SELECT * FROM taxrates ORDER BY taxrate_code LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextTaxRates($code, $filter="") {
		$this->query = "SELECT * FROM taxrates WHERE taxrate_code > '$code' ORDER BY taxrate_code LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevTaxRates($code, $filter="") {
		$this->query = "SELECT * FROM taxrates WHERE taxrate_code  < '$code' ORDER BY taxrate_code DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getTaxRatesFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getTaxRates($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevTaxRates($code, $filter);
					if (!$rec) $rec = $this->getFirstTaxRates($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextTaxRates($code, $filter);
					if (!$rec) $rec = $this->getLastTaxRates($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstTaxRates($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastTaxRates($filter);
				} else {
					$rec = $this->getTaxRates($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastTaxRates($filter);
				} else {
					$rec = $this->getFirstTaxRates($filter);
				}
			}
		}
		return $rec;
	}

	function getTaxRatesList($condition="", $filtertext="", $reverse="f", $page=1, $limit=200) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM taxrates ORDER BY taxrate_code  ";
			else if ($condition == "desc") $this->query = "SELECT * FROM taxrates ORDER BY taxrate_desc  ";
			else $this->query = "SELECT * FROM taxrates ORDER BY taxrate_code ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM taxrates WHERE taxrate_code LIKE '$filtertext%' ORDER BY taxrate_code ";
			else if ($condition == "desc") $this->query = "SELECT * FROM taxrates WHERE taxrate_desc  LIKE '$filtertext%' ORDER BY taxrate_desc  ";
			else $this->query = "SELECT * FROM taxrates ORDER BY taxrate_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getTaxRatesRows() {
		$this->query = "SELECT count(taxrate_code) AS numrows FROM taxrates ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}
?>