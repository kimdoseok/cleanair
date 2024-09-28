<?php
include_once("class.ic.php");

class UnitMeasures extends IC {

	function insertUnitMeasures($arr) {
		if ($lastid = $this->insertIC("units", $arr)) return $lastid;
		return false;
	}

	function updateUnitMeasures($code, $arr) {
		if ($this->updateIC("units", "unit_code", $code, $arr)) return true;
		return false;
	}

	function deleteUnitMeasures($code) {
		$this->query = "DELETE FROM units WHERE unit_code = '$code' ";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getUnitMeasures($code) {
		$this->query = "SELECT * FROM units WHERE unit_code = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getUnitMeasuresFields() {
		$this->query = "SELECT * FROM units LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) return $arr;
		return false;
	}

	function getLastUnitMeasures($filter="") {
		$this->query = "SELECT * FROM units ORDER BY unit_code DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstUnitMeasures($filter="") {
		$this->query = "SELECT * FROM units ORDER BY unit_code LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextUnitMeasures($code, $filter="") {
		$this->query = "SELECT * FROM units WHERE unit_code > '$code' ORDER BY unit_code LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevUnitMeasures($code, $filter="") {
		$this->query = "SELECT * FROM units WHERE unit_code  < '$code' ORDER BY unit_code DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getUnitMeasuresFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getUnitMeasures($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevUnitMeasures($code, $filter);
					if (!$rec) $rec = $this->getFirstUnitMeasures($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextUnitMeasures($code, $filter);
					if (!$rec) $rec = $this->getLastUnitMeasures($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstUnitMeasures($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastUnitMeasures($filter);
				} else {
					$rec = $this->getUnitMeasures($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastUnitMeasures($filter);
				} else {
					$rec = $this->getFirstUnitMeasures($filter);
				}
			}
		}
		return $rec;
	}

	function getUnitMeasuresList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM units ORDER BY unit_code  ";
			else if ($condition == "name") $this->query = "SELECT * FROM units ORDER BY unit_name  ";
			else $this->query = "SELECT * FROM units ORDER BY unit_code ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM units WHERE unit_code  LIKE '$filtertext%' ORDER BY unit_code ";
			else if ($condition == "name") $this->query = "SELECT * FROM units WHERE unit_name  LIKE '$filtertext%' ORDER BY unit_name  ";
			else $this->query = "SELECT * FROM units ORDER BY unit_code ";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getUnitMeasuresRows() {
		$this->query = "SELECT count(unit_code) AS numrows FROM units ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>