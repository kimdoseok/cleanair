<?php
include_once("class.ap.php");

class Vends extends AP {

	function insertVends($arr) {
		if ($lastid = $this->insertAP("vends", $arr)) return $lastid;
		return -1;
	}

	function updateVends($code, $arr) {
		if ($this->updateAP("vends", "vend_code", $code, $arr)) return true;
		return false;
	}

	function incVendNo($code) {
		$this->query = "SELECT vend_last_no FROM vends WHERE vend_code = '$code' LIMIT 1 ";
//echo $this->query."<br>";
		if ($arr = $this->getAP()) $next_num = $arr[0]["vend_last_no"]+1;
		else $next_num = 1;
		$this->query = "UPDATE vends SET vend_last_no = $next_num WHERE vend_code = '$code' ";
//echo $this->query."<br>";
		if ($this->updateAPRaw()) return $next_num;
		return -1;

	}

	function getVends($code) {
		$this->query = "SELECT * FROM vends WHERE vend_code = '$code' LIMIT 1 ";
		//echo $this->query;
		if ($arr = $this->getAP($code)) return $arr[0];
		return array();
	}

	function getVendsFields() {
		$this->query = "SELECT * FROM vends LIMIT 0 ";
		if ($arr = $this->getAPFields($this->query)) return $arr;
		return array();
	}

	function getLastVends($filter="") {
		$this->query = "SELECT * FROM vends ORDER BY vend_code DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return array();
	}

	function getFirstVends($filter="") {
		$this->query = "SELECT * FROM vends ORDER BY vend_code LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextVends($code, $filter="") {
		$this->query = "SELECT * FROM vends WHERE vend_code > '$code' ORDER BY vend_code LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevVends($code, $filter="") {
		$this->query = "SELECT * FROM vends WHERE vend_code  < '$code' ORDER BY vend_code DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getvendsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getVends($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevvends($code, $filter);
					if (!$rec) $rec = $this->getFirstVends($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextVends($code, $filter);
					if (!$rec) $rec = $this->getLastVends($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstVends($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastVends($filter);
				} else {
					$rec = $this->getVends($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastVends($filter);
				} else {
					$rec = $this->getFirstVends($filter);
				}
			}
		}
		return $rec;
	}

	function getVendsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$select = "SELECT * ";
		$from = "FROM vends ";
		$where = "WHERE 1 ";
		$orderby = "";
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $orderby = "ORDER BY vend_code ";
			else if ($condition == "name") $orderby = "ORDER BY vend_name ";
			else if ($condition == "addr") $orderby = "ORDER BY vend_addr1 ";
			else if ($condition == "city") $orderby = "ORDER BY vend_city ";
			else if ($condition == "tel") $orderby = "ORDER BY vend_tel ";
			else $orderby = "ORDER BY vend_code ";
		} else {
			if ($condition == "code") {
				$where .= "AND vend_code LIKE '$filtertext%' ";
				$orderby = "ORDER BY vend_code ";
			} else if ($condition == "name") {
				$where .= "AND vend_name LIKE '$filtertext%' ";
				$orderby = "ORDER BY vend_name ";
			} else if ($condition == "addr") {
				$where .= "AND vend_addr1 LIKE '$filtertext%' ";
				$orderby = "ORDER BY vend_addr1 ";
			} else if ($condition == "city") {
				$where .= "AND vend_city LIKE '$filtertext%' ";
				$orderby = "ORDER BY vend_city ";
			} else if ($condition == "tel") {
				$where .= "AND vend_tel LIKE '$filtertext%' ";
				$orderby = "ORDER BY vend_tel ";
			} else {
				$orderby = "ORDER BY vend_code ";
			}
		}

		if ($reverse == "t" && !empty($orderby)) $orderby .= " DESC ";
		
		$offset = $page * $limit;
		$limit = " LIMIT $offset, $limit ";
		$this->query = "$select $from $where $orderby $limit ";
//echo $this->query;
		return $this->getAP();
	}

	function getVendsRows() {
		$this->query = "SELECT count(vend_code) AS numrows FROM vends ";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>