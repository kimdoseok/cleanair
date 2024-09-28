<?php
include_once("class.ic.php");

class ItmBuilDtls extends IC {

	var $table;

	function insertItmBuilDtls($arr) {
		if ($lastid = $this->insertIC("itmbldtls", $arr)) return $lastid;
		return false;
	}

	function updateItmBuilDtls($code, $arr) {
		if ($this->updateIC("itmbldtls", "itmbldtl_id", $code, $arr)) return true;
		return false;
	}

	function deleteItmBuilDtlsHdr($code) {
		$query = "DELETE FROM itmbldtls WHERE itmbldtl_itmbuild_id = '$code' ";
		if ($this->updateICRaw($query)) return true;
		return false;
	}

	function getItmBuilDtls($code) {
		$this->query = "SELECT * FROM itmbldtls WHERE itmbldtl_id = '$code' LIMIT 1 ";
		if ($arr = $this->getIC($code)) return $arr[0];
		return false;
	}

	function getItmBuilDtlsFields() {
		$this->query = "SELECT * FROM itmbldtls LIMIT 0 ";
		if ($arr = $this->getICFields()) return $arr;
		return false;
	}

	function getLastItmBuilDtls($filter="") {
		$this->query = "SELECT * FROM itmbldtls ORDER BY itmbldtl_id DESC LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getFirstItmBuilDtls($filter="") {
		$this->query = "SELECT * FROM itmbldtls ORDER BY itmbldtl_id LIMIT 1 ";
		$arr = $this->getIC();
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getNextItmBuilDtls($code, $filter="") {
		$this->query = "SELECT * FROM itmbldtls WHERE s.itmbldtl_id > '$code' ORDER BY itmbldtl_id LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getPrevItmBuilDtls($code, $filter="") {
		$this->query = "SELECT * FROM itmbldtls WHERE d.itmbldtl_id  < '$code' ORDER BY itmbldtl_id DESC LIMIT 1 ";
		$arr = $this->getIC($code);
		if (!empty($arr)) return $arr[0];
		return false;
	}

	function getTextFields($direction="", $code="", $filter="") {
		$rec = array();
		if (empty($direction)) {
			if (empty($code)) {
				$cols = $this->getItmBuilDtlsFields();	
				for ($i=0;$i<count($cols);$i++) {
					$name = $cols[$i];
					$rec[$name] = "";
				}
			} else {
				$rec = $this->getItmBuilDtls($code);
			}
		} else {
			if (!empty($code)) {
				if ($direction == -1) {
					$rec = $this->getPrevItmBuilDtls($code, $filter);
					if (!$rec) $rec = $this->getFirstItmBuilDtls($filter);
				} else if ($direction == 1) {
					$rec = $this->getNextItmBuilDtls($code, $filter);
					if (!$rec) $rec = $this->getLastItmBuilDtls($filter);
				} else if ($direction == -2) {
					$rec = $this->getFirstItmBuilDtls($filter);
				} else if ($direction == 2) {
					$rec = $this->getLastItmBuilDtls($filter);
				} else {
					$rec = $this->getItmBuilDtls($code, $filter);
				}
			} else {
				if ($direction == 2) {
					$rec = $this->getLastItmBuilDtls($filter);
				} else {
					$rec = $this->getFirstItmBuilDtls($filter);
				}
			}
		}
		return $rec;
	}

	function getItmBuilDtlsList($code, $filter="", $reverse="f", $page=1, $limit=20) {
		if ($page < 1) $page = 0;
		else $page--;
		$this->query = "SELECT * FROM itmbldtls  WHERE itmbldtl_itmbuild_id='$code' ORDER BY itmbldtl_id ";
		if ($reverse == "t") $this->query .= " DESC ";
		$offset = $page * $limit;
		$this->query .= " LIMIT $offset, $limit ";
		return $this->getIC();
	}

	function getItmBuilDtlsRows($code) {
		$this->query = "SELECT count(itmbldtl_id) AS numrows FROM itmbldtls WHERE itmbldtl_itmbuild_id='$code' ";
		$arr = $this->getIC();
		if ($arr) return $arr[0]["numrows"];
		else return false;
	}


}

?>