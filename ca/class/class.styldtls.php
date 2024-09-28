<?php
include_once("class.ic.php");

class StylDtls extends IC {

	var $table;

	function insertStylDtls($arr) {
		if ($lastid = $this->insertIC("styldtls", $arr)) return $lastid;
		return false;
	}

	function updateStylDtls($code, $arr) {
		if ($this->updateIC("styldtls", "styldtl_id", $code, $arr)) return true;
		return false;
	}

	function deleteStylDtlsSC($code) {
		$query = "DELETE FROM styldtls WHERE styldtl_styl_code = '$code' ";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getStylDtls($code) {
		$this->query = "SELECT * FROM styldtls WHERE styldtl_id = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}
	function getStylDtlsPO($code) {
		$this->query = "SELECT * FROM styldtls d, styles s WHERE d.styldtl_styl_code=s.styl_code AND s.styl_po_no = '$code' ";
		if ($arr = $this->getIC($code)) return $arr;
		return false;
	}

	function getStylDtlsSCPO($po, $sc) {
		$this->query = "SELECT * FROM styldtls d, styles s WHERE d.styldtl_styl_code = s.styl_code AND s.styl_po_no = '$po' AND styldtl_styl_code = '$sc' ";
		if ($arr = $this->getIC($code)) return $arr;
		return false;
	}

	function getStylDtlsICSCPO($ic, $po) {
		$this->query = "SELECT * FROM styldtls d, styles s, items i WHERE i.item_code = s.stydtl_item_code AND d.styldtl_styl_code = s.styl_code AND s.styl_po_no = '$po' AND d.styldtl_item_code = '$ic' ";
		if ($arr = $this->getIC($code)) return $arr;
		return false;
	}

	function getStylDtlsFields() {
		$this->query = "SELECT * FROM styldtls LIMIT 0 ";
		if ($arr = $this->getICFields()) return $arr;
		return false;
	}

	function getLastStylDtls($filter="") {
		$this->query = "SELECT * FROM styldtls ORDER BY styldtl_id DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstStylDtls($filter="") {
		$this->query = "SELECT * FROM styldtls ORDER BY styldtl_id LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextStylDtls($code, $filter="") {
		$this->query = "SELECT * FROM styldtls WHERE s.styldtl_id > '$code' ORDER BY styldtl_id LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevStylDtls($code, $filter="") {
		$this->query = "SELECT * FROM styldtls WHERE d.styldtl_id  < '$code' ORDER BY styldtl_id DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getStylDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getStylDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevStylDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstStylDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextStylDtls($code, $filter);
					if (!$rec) $rec = $this->getLastStylDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstStylDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastStylDtls($filter);
				} else {
					$rec = $this->getStylDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastStylDtls($filter);
				} else {
					$rec = $this->getFirstStylDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getStylDtlsList($filter="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		if (empty($filter)) $this->query = "SELECT * FROM styldtls ORDER BY styldtl_id ";
		else $this->query = "SELECT * FROM styldtls  WHERE styldtl_styl_code='$filter' ORDER BY styldtl_id ";
		if ($reverse == "t") $this->query .= " DESC ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getStylDtlsRows() {
		$this->query = "SELECT count(styldtl_id) AS numrows FROM styldtls ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>