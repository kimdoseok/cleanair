<?php
include_once("class.ar.php");

class CustShips extends AR {

	var $active;

	function insertCustShips($arr) {
		if ($lastid = $this->insertAR("custships", $arr)) return $lastid;
		return false;
	}

	function updateCustShips($code, $arr) {
		if ($this->updateAR("custships", "custship_id", $code, $arr)) return true;
		return false;
	}

	function updateCustShipsAmt($code, $field, $amt) {
		$this->query = "UPDATE custships SET $field = $field + $amt WHERE custship_id='$code' ";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function deleteCustShips($code) {
		$this->query = "DELETE FROM custships WHERE custship_id='$code' ";
		if ($this->updateARRaw()) return true;
		else return false;
	}

	function getCustShips($code, $cust) {
		if ($this->active == "t") $aw = "AND custship_active = 't' ";
		$aw .= "AND custship_cust_code = '$cust' ";
		$this->query = "SELECT * FROM custships WHERE custship_id = '$code' $aw LIMIT 1 ";
//echo $this->query."<br>";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getCustShipsFields() {
		$this->query = "SELECT * FROM custships LIMIT 0 ";
		$arr = $this->getARFields($this->query);
		$xarr = array();
		if ($arr) {
			for ($i=0;$i<count($arr);$i++) {
				$key = $arr[$i];
				$xarr[$key]="";
			}
			return $xarr;
		}
		return false;
	}

	function getLastCustShips($filter="") {
		if ($this->active == "t") $aw = "WHERE custship_active = 't' ";
		$this->query = "SELECT * FROM custships $aw ORDER BY custship_id DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstCustShips($filter="") {
		if ($this->active == "t") $aw = "WHERE custship_active = 't' ";
		$this->query = "SELECT * FROM custships $aw ORDER BY custship_id LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextCustShips($code, $filter="") {
		if ($this->active == "t") $aw = "AND custship_active = 't' ";
		$this->query = "SELECT * FROM custships WHERE custship_id > '$code' $aw ORDER BY custship_id LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevCustShips($code, $filter="") {
		if ($this->active == "t") $aw = "AND custship_active = 't' ";
		$this->query = "SELECT * FROM custships WHERE custship_id  < '$code' $aw ORDER BY custship_id DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$rec = $this->getCustShipsFields();	
			} else {
				$rec = $this->getCustShips($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevCustShips($code, $filter);
					if (!$rec) $rec = $this->getFirstCustShips($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextCustShips($code, $filter);
					if (!$rec) $rec = $this->getLastCustShips($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstCustShips($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastCustShips($filter);
				} else {
					$rec = $this->getCustShips($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastCustShips($filter);
				} else {
					$rec = $this->getFirstCustShips($filter);
				}
			}
		}
		return $rec;
	}

	function getCustShipsList($code, $condition="", $filtertext="", $reverse="f", $page=1, $limit=2000) {
		if ($page < 1) $page = 0;
		else $page--;

		$wh = "WHERE custship_cust_code = '$code' ";
		if ($this->active == "t") {
			$wh .= "AND custship_active = 't' ";
			$aw .= "AND custship_active = 't' ";
		}
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM custships $wh ORDER BY custship_id  ";
			else if ($condition == "name") $this->query = "SELECT * FROM custships $wh ORDER BY custship_name  ";
			else if ($condition == "addr") $this->query = "SELECT * FROM custships $wh ORDER BY custship_addr1  ";
			else if ($condition == "city") $this->query = "SELECT * FROM custships $wh ORDER BY custship_city  ";
			else if ($condition == "tel") $this->query = "SELECT * FROM custships $wh ORDER BY custship_tel  ";
			else $this->query = "SELECT * FROM custships $wh ORDER BY custship_id ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM custships WHERE custship_id LIKE '$filtertext%' $aw ORDER BY custship_id ";
			else if ($condition == "name") $this->query = "SELECT * FROM custships WHERE custship_name LIKE '$filtertext%' $aw ORDER BY custship_name  ";
			else if ($condition == "addr") $this->query = "SELECT * FROM custships WHERE custship_addr1 LIKE '$filtertext%' $aw ORDER BY custship_addr1 ";
			else if ($condition == "city") $this->query = "SELECT * FROM custships WHERE custship_city LIKE '$filtertext%' $aw ORDER BY custship_city ";
			else if ($condition == "tel") $this->query = "SELECT * FROM custships WHERE custship_tel LIKE '$filtertext%' $aw ORDER BY custship_tel ";
			else $this->query = "SELECT * FROM custships WHERE custship_id  LIKE '$filtertext%' $aw ORDER BY custship_id ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
//echo $this->query."<br>";
		return $this->getAR();
	}

	function getCustShipsRows($code, $condition="", $filtertext="") {
		$wh = "WHERE custship_cust_code = '$code' ";
		if ($this->active == "t") {
			$wh .= "AND custship_active = 't' ";
			$aw .= "AND custship_active = 't' ";
		}
		if (empty($filtertext) || !isset($filtertext)) {
			$this->query = "SELECT count(custship_id) AS numrows FROM custships $wh ";
		} else {
			if ($condition == "code") $this->query = "SELECT count(custship_id) AS numrows FROM custships WHERE custship_id LIKE '$filtertext%' $aw";
			else if ($condition == "name") $this->query = "SELECT count(custship_id) AS numrows FROM custships WHERE custship_name  LIKE '$filtertext%' $aw";
			else if ($condition == "addr") $this->query = "SELECT count(custship_id) AS numrows FROM custships WHERE custship_addr1 LIKE '$filtertext%' $aw ";
			else if ($condition == "city") $this->query = "SELECT count(custship_id) AS numrows FROM custships WHERE custship_city LIKE '$filtertext%' $aw ";
			else if ($condition == "tel") $this->query = "SELECT count(custship_id) AS numrows FROM custships WHERE custship_tel LIKE '$filtertext%' $aw ";
			else $this->query = "SELECT count(custship_id) AS numrows FROM custships WHERE custship_id LIKE '$filtertext%' $aw";
		}
//echo $this->query."<br>";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>