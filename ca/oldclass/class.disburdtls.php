<?php
include_once("class.ap.php");

class DisburDtls extends AP {

	function insertDisburDtls($arr) {
		if ($lastid = $this->insertAP("disburdtls", $arr)) return $lastid;
		return false;
	}

	function updateDisburDtls($code, $arr) {
		if ($this->updateAP("disburdtls", "disburdtl_id", $code, $arr)) return true;
		return false;
	}

	function deleteDisburDtlsPI($code) {
		$query = "DELETE FROM disburdtls WHERE disburdtl_disbur_id = '$code' ";
		if ($this->updateAPRaw($query)) return true;
		return false;
	}

	function getDisburDtls($code) {
		$this->query = "SELECT * FROM disburdtls WHERE disburdtl_id = '$code' LIMIT 1 ";
		if ($arr = $this->getAP($code)) return $arr[0];
		return false;
	}

	function getDisburDtlsFields() {
		$this->query = "SELECT * FROM disburdtls LIMIT 0 ";
		if ($arr = $this->getAPFields()) return $arr;
		return false;
	}

	function getLastDisburDtls($filter) {
		$this->query = "SELECT * FROM disburdtls WHERE disburdtl_disbur_id='$filter' ORDER BY disburdtl_id DESC LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstDisburDtls($filter) {
		$this->query = "SELECT * FROM disburdtls WHERE disburdtl_disbur_id='$filter' ORDER BY disburdtl_id LIMIT 1 ";
		$arr = $this->getAP();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextDisburDtls($code, $filter) {
		$this->query = "SELECT * FROM disburdtls WHERE disburdtl_id > '$code' AND disburdtl_disbur_id='$filter' ORDER BY disburdtl_id LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevDisburDtls($code, $filter) {
		$this->query = "SELECT * FROM disburdtls WHERE disburdtl_id  < '$code' AND disburdtl_disbur_id='$filter' ORDER BY disburdtl_id DESC LIMIT 1 ";
		$arr = $this->getAP($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getDisburDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getDisburDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevDisburDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstDisburDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextDisburDtls($code, $filter);
					if (!$rec) $rec = $this->getLastDisburDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstDisburDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastDisburDtls($filter);
				} else {
					$rec = $this->getDisburDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastDisburDtls($filter);
				} else {
					$rec = $this->getFirstDisburDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getDisburDtlsList($condition="", $filtertext="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filtertext) || !isset($filtertext)) {
			if ($condition != "all") $this->query = "SELECT * FROM disburdtls WHERE disburdtl_disbur_id='$condition' ORDER BY disburdtl_id  ";
			else $this->query = "SELECT * FROM disburdtls ORDER BY disburdtl_id ";
		} else {
			if ($condition != "all") $this->query = "SELECT * FROM disburdtls WHERE disburdtl_disbur_id='$condition' ORDER BY disburdtl_id  ";
			else $this->query = "SELECT * FROM disburdtls ORDER BY disburdtl_id ";
		}
		if ($reverse != "t") $this->query .= " DESC ";
		
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getAP();
	}

	function getDisburDtlsRows($disbur_id="") {
		if (empty($disbur_id)) $this->query = "SELECT count(disburdtl_id) AS numrows FROM disburdtls ";
		else $this->query = "SELECT count(disburdtl_id) AS numrows FROM disburdtls WHERE disburdtl_disbur_id='$disbur_id'";
		$arr = $this->getAP();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>