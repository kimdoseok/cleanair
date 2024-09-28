<?php
include_once("class.ic.php");

class Unit extends IC {

	function insertUnit($arr) {
		if ($lastid = $this->insertIC("units", $arr)) return $lastid;
		return false;
	}

	function updateUnit($code, $arr) {
		if ($this->updateIC("units", "unit_code", $code, $arr)) return true;
		return false;
	}

	function deleteUnit($code) {
		$query = "delete from units where unit_code='$code'";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getUnit($code="") {
		$code = trim($code);
		if (empty($code)) return false;
		$this->query = "SELECT * FROM units WHERE unit_code = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getUnitFields() {
		$this->query = "SELECT * FROM units LIMIT 0 ";
		if ($arr = $this->getICFields($this->query)) return $arr;
		return false;
	}

	function getLastUnit($filter="") {
		$this->query = "SELECT * FROM units ORDER BY unit_code DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstUnit($filter="") {
		$this->query = "SELECT * FROM units ORDER BY unit_code LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextUnit($code, $filter="") {
		$this->query = "SELECT * FROM units WHERE unit_code > '$code' ORDER BY unit_code LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevUnit($code, $filter="") {
		$this->query = "SELECT * FROM units WHERE unit_code  < '$code' ORDER BY unit_code DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getUnitFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getUnit($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevUnit($code, $filter);
					if (!$rec) $rec = $this->getFirstUnit($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextUnit($code, $filter);
					if (!$rec) $rec = $this->getLastUnit($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstUnit($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastUnit($filter);
				} else {
					$rec = $this->getUnit($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastUnit($filter);
				} else {
					$rec = $this->getFirstUnit($filter);
				}
			}
		}
		return $rec;
	}

	function getUnitList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;

		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition == "code") $this->query = "SELECT * FROM units ORDER BY unit_code, unit_name ";
			else if ($condition == "name") $this->query = "SELECT * FROM units ORDER BY  unit_name ";
			else if ($condition == "desc") $this->query = "SELECT * FROM units ORDER BY unit_desc, unit_name ";
			else $this->query = "SELECT * FROM units ORDER BY unit_code, unit_name ";
		} else {
			if ($condition == "code") $this->query = "SELECT * FROM units WHERE unit_code LIKE '$filtertext%' ORDER BY unit_code, unit_name";
			else if ($condition == "name") $this->query = "SELECT * FROM units WHERE unit_name LIKE '$filtertext%' ORDER BY  unit_name";
			else if ($condition == "desc") $this->query = "SELECT * FROM units WHERE unit_desc  LIKE '$filtertext%' ORDER BY unit_desc, unit_name ";
			else $this->query = "SELECT * FROM units ORDER BY unit_code, unit_name";
		}
		if ($reverse == "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getUnitRows() {
		$this->query = "SELECT count(unit_code) AS numrows FROM units ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}

}
?>