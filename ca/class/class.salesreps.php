<?php
include_once("class.ar.php");

class SalesReps extends AR {

	function insertSalesReps($arr) {
		if ($lastid = $this->insertAR("slsreps", $arr)) return $lastid;
		return false;
	}

	function updateSalesReps($code, $arr) {
		if ($this->updateAR("slsreps", "slsrep_code", $code, $arr)) return true;
		return false;
	}

	function getSalesReps($code) {
		$this->query = "SELECT * FROM slsreps WHERE slsrep_code = '$code' LIMIT 1 ";
		if ($arr = $this->getAR($code)) return $arr[0];
		return false;
	}

	function getSalesRepsFields() {
		$this->query = "SELECT * FROM slsreps LIMIT 0 ";
		if ($arr = $this->getARFields($this->query)) return $arr;
		return false;
	}

	function getLastSalesReps($filter="") {
		$this->query = "SELECT * FROM slsreps ORDER BY slsrep_code DESC LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstSalesReps($filter="") {
		$this->query = "SELECT * FROM slsreps ORDER BY slsrep_code LIMIT 1 ";
		$arr = $this->getAR();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextSalesReps($code, $filter="") {
		$this->query = "SELECT * FROM slsreps WHERE slsrep_code > '$code' ORDER BY slsrep_code LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevSalesReps($code, $filter="") {
		$this->query = "SELECT * FROM slsreps WHERE slsrep_code  < '$code' ORDER BY slsrep_code DESC LIMIT 1 ";
		$arr = $this->getAR($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getSalesRepsFields();	
				//print_r($cols);
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getSalesReps($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevSalesReps($code, $filter);
					if (!$rec) $rec = $this->getFirstSalesReps($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextSalesReps($code, $filter);
					if (!$rec) $rec = $this->getLastSalesReps($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstSalesReps($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastSalesReps($filter);
				} else {
					$rec = $this->getSalesReps($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastSalesReps($filter);
				} else {
					$rec = $this->getFirstSalesReps($filter);
				}
			}
		}
		return $rec;
	}

	function getSalesRepsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM slsreps ORDER BY slsrep_code  ";
			else if ($condition == "name") $this->query = "SELECT * FROM slsreps ORDER BY slsrep_name  ";
			else $this->query = "SELECT * FROM slsreps ORDER BY slsrep_code ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM slsreps WHERE slsrep_code  LIKE '$filtertext%' ORDER BY slsrep_code ";
			else if ($condition == "name") $this->query = "SELECT * FROM slsreps WHERE slsrep_name  LIKE '$filtertext%' ORDER BY slsrep_name  ";
			else $this->query = "SELECT * FROM slsreps ORDER BY slsrep_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAR();
	}

	function getSalesRepsRows() {
		$this->query = "SELECT count(slsrep_code) AS numrows FROM slsreps ";
		$arr = $this->getAR();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>