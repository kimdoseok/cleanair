<?php
include_once("class.ic.php");

class Styles extends IC {

	function insertStyles($arr) {
		if ($lastid = $this->insertIC("styles", $arr)) return $lastid;
		return false;
	}

	function updateStyles($code, $arr) {
		if ($this->updateIC("styles", "styl_code", $code, $arr)) return true;
		return false;
	}

	function getStyles($code) {
		$this->query = "SELECT * FROM styles WHERE styl_code = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getStylesFields() {
		$this->query = "SELECT * FROM styles LIMIT 0 ";
		if ($arr = $this->getICFields()) return $arr;
		return false;
	}

	function getLastStyles($filter="") {
		$this->query = "SELECT * FROM styles ORDER BY styl_code DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstStyles($filter="") {
		$this->query = "SELECT * FROM styles ORDER BY styl_code LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextStyles($code, $filter="") {
		$this->query = "SELECT * FROM styles WHERE styl_code > '$code' ORDER BY styl_code LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevStyles($code, $filter="") {
		$this->query = "SELECT * FROM styles WHERE styl_code  < '$code' ORDER BY styl_code DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getStylesFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getStyles($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevStyles($code, $filter);
					if (!$rec) $rec = $this->getFirstStyles($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextStyles($code, $filter);
					if (!$rec) $rec = $this->getLastStyles($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstStyles($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastStyles($filter);
				} else {
					$rec = $this->getStyles($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastStyles($filter);
				} else {
					$rec = $this->getFirstStyles($filter);
				}
			}
		}
		return $rec;
	}

	function getStylesList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$this->query = "SELECT * FROM styles ORDER BY styl_code ";
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getStylesRows() {
		$this->query = "SELECT count(styl_code) AS numrows FROM styles ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>